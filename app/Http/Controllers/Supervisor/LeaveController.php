<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Get authenticated supervisor
            $supervisor = Auth::user();
            
            // Get supervisor's team members
            $teamMemberIds = User::where('supervisor_id', $supervisor->id)
                ->pluck('id')
                ->toArray();
            
            // Base query for supervisor's team leaves
            $query = Leave::with(['user' => function($q) {
                $q->select('id', 'name', 'employee_number', 'department');
            }])
            ->whereIn('user_id', $teamMemberIds)
            ->latest();
            
            // Apply filters
            if ($request->filled('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('type') && $request->type !== 'all') {
                $query->where('leave_type', $request->type);
            }
            
            if ($request->filled('start_date')) {
                $query->whereDate('start_date', '>=', $request->start_date);
            }
            
            if ($request->filled('end_date')) {
                $query->whereDate('end_date', '<=', $request->end_date);
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('employee_number', 'like', "%{$search}%");
                });
            }
            
            // Get paginated results
            $leaves = $query->paginate(15)->withQueryString();
            
            // Calculate statistics
            $stats = [
                'total' => Leave::whereIn('user_id', $teamMemberIds)->count(),
                'pending' => Leave::whereIn('user_id', $teamMemberIds)->where('status', 'pending')->count(),
                'approved' => Leave::whereIn('user_id', $teamMemberIds)->where('status', 'approved')->count(),
                'rejected' => Leave::whereIn('user_id', $teamMemberIds)->where('status', 'rejected')->count(),
                'cancelled' => Leave::whereIn('user_id', $teamMemberIds)->where('status', 'cancelled')->count(),
                'avg_days' => number_format(Leave::whereIn('user_id', $teamMemberIds)
                    ->where('status', 'approved')
                    ->avg('total_days') ?? 0, 1)
            ];
            
            return view('supervisor.leaves', [
                'leaves' => $leaves,
                'leaveStats' => $stats
            ]);
            
        } catch (\Exception $e) {
            Log::error('Supervisor leaves index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load leaves. Please try again.');
        }
    }
    
    public function show(Leave $leave)
    {
        // Authorization check
        $this->authorizeSupervisor($leave);
        
        return view('leaves.show', compact('leave'));
    }
    
    public function approve(Request $request, Leave $leave)
    {
        try {
            // Authorization check
            if (!$this->authorizeSupervisor($leave)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to approve this leave.'
                ], 403);
            }
            
            // Validation
            if ($leave->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This leave request has already been processed.'
                ], 400);
            }
            
            DB::beginTransaction();
            
            try {
                // Update leave status
                $leave->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                    'approved_by' => Auth::id(),
                    'rejection_reason' => null
                ]);
                
                // Update employee leave balance if applicable
                if ($leave->leave_type === 'annual') {
                    $this->updateLeaveBalance($leave);
                }
                
                // Send notification (you can implement this later)
                // $leave->user->notify(new LeaveApproved($leave));
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Leave request approved successfully!',
                    'data' => [
                        'leave_id' => $leave->id,
                        'status' => 'approved',
                        'approved_at' => now()->toDateTimeString()
                    ]
                ]);
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Leave approval transaction error: ' . $e->getMessage());
                
                return response()->json([
                    'success' => false,
                    'message' => 'Database error occurred. Please try again.'
                ], 500);
            }
            
        } catch (\Exception $e) {
            Log::error('Leave approval error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while approving the leave. Please try again.'
            ], 500);
        }
    }
    
    public function reject(Request $request, Leave $leave)
    {
        try {
            $request->validate([
                'remarks' => 'required|string|max:500'
            ]);
            
            // Authorization check
            if (!$this->authorizeSupervisor($leave)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to reject this leave.'
                ], 403);
            }
            
            if ($leave->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This leave request has already been processed.'
                ], 400);
            }
            
            DB::beginTransaction();
            
            try {
                $leave->update([
                    'status' => 'rejected',
                    'rejected_at' => now(),
                    'rejected_by' => Auth::id(),
                    'rejection_reason' => $request->remarks
                ]);
                
                // Send notification
                // $leave->user->notify(new LeaveRejected($leave, $request->remarks));
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Leave request rejected successfully!'
                ]);
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Leave rejection transaction error: ' . $e->getMessage());
                
                return response()->json([
                    'success' => false,
                    'message' => 'Database error occurred. Please try again.'
                ], 500);
            }
            
        } catch (\Exception $e) {
            Log::error('Leave rejection error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while rejecting the leave.'
            ], 500);
        }
    }
    
    public function cancel(Request $request, Leave $leave)
    {
        try {
            $request->validate([
                'remarks' => 'required|string|max:500'
            ]);
            
            // Authorization check
            if (!$this->authorizeSupervisor($leave)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to cancel this leave.'
                ], 403);
            }
            
            if ($leave->status !== 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only approved leaves can be cancelled.'
                ], 400);
            }
            
            // Check if leave has already started
            if (now()->greaterThanOrEqualTo($leave->start_date)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel leave that has already started.'
                ], 400);
            }
            
            DB::beginTransaction();
            
            try {
                $leave->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancelled_by' => Auth::id(),
                    'cancellation_reason' => $request->remarks
                ]);
                
                // Restore leave balance if applicable
                if ($leave->leave_type === 'annual') {
                    $this->restoreLeaveBalance($leave);
                }
                
                // Send notification
                // $leave->user->notify(new LeaveCancelled($leave, $request->remarks));
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Leave cancelled successfully!'
                ]);
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Leave cancellation transaction error: ' . $e->getMessage());
                
                return response()->json([
                    'success' => false,
                    'message' => 'Database error occurred. Please try again.'
                ], 500);
            }
            
        } catch (\Exception $e) {
            Log::error('Leave cancellation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while cancelling the leave.'
            ], 500);
        }
    }
    
    /**
     * Check if supervisor is authorized for this leave
     */
    private function authorizeSupervisor(Leave $leave): bool
    {
        return $leave->user && $leave->user->supervisor_id === Auth::id();
    }
    
    /**
     * Update employee leave balance after approval
     */
    private function updateLeaveBalance(Leave $leave): void
    {
        // Assuming you have a leave_balances table
        // You might need to adjust this based on your actual database structure
        $balance = $leave->user->leaveBalance;
        
        if ($balance) {
            $balance->update([
                'annual_leave_taken' => $balance->annual_leave_taken + $leave->total_days,
                'annual_leave_remaining' => $balance->annual_leave_remaining - $leave->total_days,
                'updated_at' => now()
            ]);
        }
    }
    
    /**
     * Restore leave balance after cancellation
     */
    private function restoreLeaveBalance(Leave $leave): void
    {
        $balance = $leave->user->leaveBalance;
        
        if ($balance) {
            $balance->update([
                'annual_leave_taken' => $balance->annual_leave_taken - $leave->total_days,
                'annual_leave_remaining' => $balance->annual_leave_remaining + $leave->total_days,
                'updated_at' => now()
            ]);
        }
    }
}