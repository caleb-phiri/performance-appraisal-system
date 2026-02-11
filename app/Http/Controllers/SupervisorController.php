<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appraisal;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class SupervisorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the supervisor dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $supervisor = Auth::user();
        
        // Check if user is onboarded
        if (!$supervisor->is_onboarded) {
            return redirect()->route('onboarding.survey')
                ->with('info', 'Please complete your profile setup first.');
        }
        
        // Check if user is supervisor (adjust based on your user model)
        // Assuming you have a 'user_type' field or 'role' field
        $isSupervisor = false;
        if (isset($supervisor->user_type) && $supervisor->user_type === 'supervisor') {
            $isSupervisor = true;
        } elseif (isset($supervisor->role) && ($supervisor->role === 'supervisor' || $supervisor->role === 'admin')) {
            $isSupervisor = true;
        } elseif (User::where('supervisor_id', $supervisor->id)->exists()) {
            $isSupervisor = true;
        }
        
        if (!$isSupervisor) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have access to the supervisor dashboard.');
        }
        
        // Get team members - adjust based on your database structure
        $team = User::where('supervisor_id', $supervisor->id)
            ->orWhere('manager_id', $supervisor->employee_number)
            ->where('is_onboarded', true)
            ->get();
            
        // Get team employee numbers
        $teamEmployeeNumbers = $team->pluck('employee_number')->toArray();
        
        // Initialize empty paginators for no team members case
        $pendingAppraisals = new LengthAwarePaginator([], 0, 5, null, [
            'path' => request()->url(),
            'pageName' => 'pending_page'
        ]);
        
        $approvedAppraisals = new LengthAwarePaginator([], 0, 5, null, [
            'path' => request()->url(),
            'pageName' => 'approved_page'
        ]);
        
        // Get pending appraisals only if team has members
        if (!empty($teamEmployeeNumbers)) {
            $pendingAppraisals = Appraisal::whereIn('employee_number', $teamEmployeeNumbers)
                ->where('status', 'submitted')
                ->with('user')
                ->orderBy('updated_at', 'desc')
                ->paginate(5, ['*'], 'pending_page');
                
            $approvedAppraisals = Appraisal::whereIn('employee_number', $teamEmployeeNumbers)
                ->where('status', 'approved')
                ->with('user')
                ->orderBy('updated_at', 'desc')
                ->paginate(5, ['*'], 'approved_page');
        }
        
        // Calculate statistics
        $stats = [
            'team_size' => $team->count(),
            'avg_final_score' => 0,
        ];
        
        // Get team leave requests
        $pendingLeaves = collect();
        $upcomingLeaves = collect();
        $leaveStats = [
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
            'cancelled' => 0,
            'total' => 0,
            'avg_days' => 0,
        ];

        if (!empty($teamEmployeeNumbers)) {
            $pendingLeaves = Leave::whereIn('employee_number', $teamEmployeeNumbers)
                ->where('status', 'pending')
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
                
            $upcomingLeaves = Leave::whereIn('employee_number', $teamEmployeeNumbers)
                ->where('status', 'approved')
                ->where('start_date', '>=', now())
                ->with('user')
                ->orderBy('start_date', 'asc')
                ->limit(5)
                ->get();
            
            // Calculate leave statistics
            $leaveStats['pending'] = Leave::whereIn('employee_number', $teamEmployeeNumbers)
                ->where('status', 'pending')
                ->count();
                
            $leaveStats['approved'] = Leave::whereIn('employee_number', $teamEmployeeNumbers)
                ->where('status', 'approved')
                ->count();
                
            $leaveStats['rejected'] = Leave::whereIn('employee_number', $teamEmployeeNumbers)
                ->where('status', 'rejected')
                ->count();
                
            $leaveStats['cancelled'] = Leave::whereIn('employee_number', $teamEmployeeNumbers)
                ->where('status', 'cancelled')
                ->count();
                
            $leaveStats['total'] = Leave::whereIn('employee_number', $teamEmployeeNumbers)
                ->count();
                
            // Calculate average days
            $totalDays = Leave::whereIn('employee_number', $teamEmployeeNumbers)
                ->where('status', 'approved')
                ->sum('total_days');
                
            $approvedCount = Leave::whereIn('employee_number', $teamEmployeeNumbers)
                ->where('status', 'approved')
                ->count();
                
            $leaveStats['avg_days'] = $approvedCount > 0 ? round($totalDays / $approvedCount, 1) : 0;
        }
        
        return view('supervisor.dashboard', compact(
            'supervisor',
            'team',
            'pendingAppraisals',
            'approvedAppraisals',
            'stats',
            'pendingLeaves',
            'upcomingLeaves',
            'leaveStats'
        ));
    }

    /**
     * Show team members list.
     */
    public function team()
    {
        $supervisor = Auth::user();
        
        // Check if user is supervisor
        $isSupervisor = $this->checkIfSupervisor($supervisor);
        if (!$isSupervisor) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have access to the supervisor dashboard.');
        }
        
        $team = User::where('supervisor_id', $supervisor->id)
            ->orWhere('manager_id', $supervisor->employee_number)
            ->where('is_onboarded', true)
            ->paginate(10);

        return view('supervisor.team', compact('supervisor', 'team'));
    }

    /**
     * Show reports page.
     */
    public function reports()
    {
        $supervisor = Auth::user();
        
        // Check if user is supervisor
        $isSupervisor = $this->checkIfSupervisor($supervisor);
        if (!$isSupervisor) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have access to the supervisor dashboard.');
        }
        
        return view('supervisor.reports', compact('supervisor'));
    }

    /**
     * Show leave management page.
     */
    public function leaves(Request $request)
    {
        $supervisor = Auth::user();
        
        // Check if user is supervisor
        $isSupervisor = $this->checkIfSupervisor($supervisor);
        if (!$isSupervisor) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have access to the supervisor dashboard.');
        }
        
        // Get team members
        $team = User::where('supervisor_id', $supervisor->id)
            ->orWhere('manager_id', $supervisor->employee_number)
            ->where('is_onboarded', true)
            ->get();
            
        $teamEmployeeNumbers = $team->pluck('employee_number')->toArray();
        
        // Get leaves with filters
        $query = Leave::whereIn('employee_number', $teamEmployeeNumbers)
            ->with('user');
            
        // Apply status filter
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Apply type filter
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('leave_type', $request->type);
        }
        
        // Apply date filter
        if ($request->has('start_date') && $request->start_date) {
            $query->where('start_date', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date) {
            $query->where('end_date', '<=', $request->end_date);
        }
        
        // Apply search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('employee_number', 'like', "%{$search}%");
                })
                  ->orWhere('reason', 'like', "%{$search}%");
            });
        }
        
        $leaves = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get leave statistics
        $leaveStats = [
            'pending' => Leave::whereIn('employee_number', $teamEmployeeNumbers)
                ->where('status', 'pending')
                ->count(),
            'approved' => Leave::whereIn('employee_number', $teamEmployeeNumbers)
                ->where('status', 'approved')
                ->count(),
            'rejected' => Leave::whereIn('employee_number', $teamEmployeeNumbers)
                ->where('status', 'rejected')
                ->count(),
            'cancelled' => Leave::whereIn('employee_number', $teamEmployeeNumbers)
                ->where('status', 'cancelled')
                ->count(),
            'total' => Leave::whereIn('employee_number', $teamEmployeeNumbers)
                ->count(),
        ];
        
        // Calculate average days
        $totalDays = Leave::whereIn('employee_number', $teamEmployeeNumbers)
            ->where('status', 'approved')
            ->sum('total_days');
            
        $approvedCount = Leave::whereIn('employee_number', $teamEmployeeNumbers)
            ->where('status', 'approved')
            ->count();
            
        $leaveStats['avg_days'] = $approvedCount > 0 ? round($totalDays / $approvedCount, 1) : 0;
        
        return view('supervisor.leaves', compact(
            'supervisor',
            'leaves', 
            'leaveStats'
        ));
    }

    /**
     * Approve a leave request.
     */
    public function approveLeave(Request $request, $id)
    {
        $supervisor = Auth::user();
        
        // Check if user is supervisor
        $isSupervisor = $this->checkIfSupervisor($supervisor);
        if (!$isSupervisor) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }
        
        $leave = Leave::findOrFail($id);
        
        // Check if supervisor manages this employee
        // Try multiple ways to check supervisor relationship
        $isTeamMember = User::where('employee_number', $leave->employee_number)
            ->where(function($query) use ($supervisor) {
                $query->where('supervisor_id', $supervisor->id)
                      ->orWhere('manager_id', $supervisor->employee_number);
            })
            ->exists();
            
        if (!$isTeamMember) {
            return response()->json([
                'success' => false,
                'message' => 'You can only manage leaves for your team members.'
            ], 403);
        }
        
        // Check if leave is pending
        if ($leave->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This leave request is no longer pending.'
            ], 400);
        }
        
        // Approve the leave
        $leave->update([
            'status' => 'approved',
            'approved_by' => $supervisor->employee_number ?? $supervisor->id,
            'approved_at' => now(),
            'remarks' => $request->remarks ?? null
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Leave request approved successfully!'
        ]);
    }

    /**
     * Reject a leave request.
     */
    public function rejectLeave(Request $request, $id)
    {
        $supervisor = Auth::user();
        
        // Check if user is supervisor
        $isSupervisor = $this->checkIfSupervisor($supervisor);
        if (!$isSupervisor) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }
        
        $leave = Leave::findOrFail($id);
        
        // Check if supervisor manages this employee
        $isTeamMember = User::where('employee_number', $leave->employee_number)
            ->where(function($query) use ($supervisor) {
                $query->where('supervisor_id', $supervisor->id)
                      ->orWhere('manager_id', $supervisor->employee_number);
            })
            ->exists();
            
        if (!$isTeamMember) {
            return response()->json([
                'success' => false,
                'message' => 'You can only manage leaves for your team members.'
            ], 403);
        }
        
        // Check if leave is pending
        if ($leave->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This leave request is no longer pending.'
            ], 400);
        }
        
        // Validate remarks
        $request->validate([
            'remarks' => 'required|string|min:10|max:500'
        ]);
        
        // Reject the leave
        $leave->update([
            'status' => 'rejected',
            'approved_by' => $supervisor->employee_number ?? $supervisor->id,
            'approved_at' => now(),
            'remarks' => $request->remarks
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Leave request rejected successfully!'
        ]);
    }

    /**
     * Cancel an approved leave.
     */
    public function cancelLeave(Request $request, $id)
    {
        $supervisor = Auth::user();
        
        // Check if user is supervisor
        $isSupervisor = $this->checkIfSupervisor($supervisor);
        if (!$isSupervisor) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }
        
        $leave = Leave::findOrFail($id);
        
        // Check if supervisor manages this employee
        $isTeamMember = User::where('employee_number', $leave->employee_number)
            ->where(function($query) use ($supervisor) {
                $query->where('supervisor_id', $supervisor->id)
                      ->orWhere('manager_id', $supervisor->employee_number);
            })
            ->exists();
            
        if (!$isTeamMember) {
            return response()->json([
                'success' => false,
                'message' => 'You can only manage leaves for your team members.'
            ], 403);
        }
        
        // Check if leave is approved
        if ($leave->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Only approved leaves can be cancelled.'
            ], 400);
        }
        
        // Validate remarks
        $request->validate([
            'remarks' => 'required|string|min:10|max:500'
        ]);
        
        // Cancel the leave
        $leave->update([
            'status' => 'cancelled',
            'remarks' => $request->remarks
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Leave cancelled successfully!'
        ]);
    }

    /**
     * Check if user is supervisor.
     */
    private function checkIfSupervisor($user)
    {
        // Method 1: Check user_type field
        if (isset($user->user_type) && $user->user_type === 'supervisor') {
            return true;
        }
        
        // Method 2: Check role field
        if (isset($user->role) && ($user->role === 'supervisor' || $user->role === 'admin')) {
            return true;
        }
        
        // Method 3: Check if user has team members
        if (User::where('supervisor_id', $user->id)->exists()) {
            return true;
        }
        
        // Method 4: Check if user has team members by employee_number
        if (User::where('manager_id', $user->employee_number)->exists()) {
            return true;
        }
        
        return false;
    }

    /**
     * Quick rate an employee.
     */
    public function rateEmployee(Request $request)
    {
        $supervisor = Auth::user();
        
        // Only supervisors can rate
        $isSupervisor = $this->checkIfSupervisor($supervisor);
        if (!$isSupervisor) {
            return response()->json([
                'success' => false,
                'message' => 'Only supervisors can rate employees.'
            ], 403);
        }
        
        $validated = $request->validate([
            'employee_number' => 'required|exists:users,employee_number',
            'category' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comments' => 'required|string|min:5',
        ]);
        
        // Check if supervisor manages this employee
        $isTeamMember = User::where('employee_number', $validated['employee_number'])
            ->where(function($query) use ($supervisor) {
                $query->where('supervisor_id', $supervisor->id)
                      ->orWhere('manager_id', $supervisor->employee_number);
            })
            ->exists();
            
        if (!$isTeamMember) {
            return response()->json([
                'success' => false,
                'message' => 'You can only rate employees in your team.'
            ], 403);
        }
        
        // Save the rating
        return response()->json([
            'success' => true,
            'message' => 'Rating submitted successfully!',
            'data' => $validated
        ]);
    }
}