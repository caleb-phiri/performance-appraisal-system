<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Notifications\LeaveRequestApproved;
use App\Notifications\LeaveRequestRejected;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeaveController extends Controller
{
    /**
     * Display a listing of the user's leaves.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's leaves using employee_number
        $leaves = Leave::where('employee_number', $user->employee_number)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Calculate leave statistics
        $totalLeaves = Leave::where('employee_number', $user->employee_number)->count();
        $pendingLeaves = Leave::where('employee_number', $user->employee_number)
            ->where('status', 'pending')
            ->count();
        $approvedLeaves = Leave::where('employee_number', $user->employee_number)
            ->where('status', 'approved')
            ->count();
        $rejectedLeaves = Leave::where('employee_number', $user->employee_number)
            ->where('status', 'rejected')
            ->count();
        
        // Get upcoming approved leaves
        $upcomingLeaves = Leave::where('employee_number', $user->employee_number)
            ->where('status', 'approved')
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit(5)
            ->get();
        
        return view('leave.index', compact(
            'leaves', 
            'totalLeaves', 
            'pendingLeaves', 
            'approvedLeaves', 
            'rejectedLeaves',
            'upcomingLeaves'
        ));
    }

    /**
     * Display a listing of leaves for supervisor.
     */
    public function supervisorIndex(Request $request)
    {
        $user = Auth::user();
        
        // Only supervisors can access
        if ($user->user_type !== 'supervisor') {
            abort(403, 'Unauthorized access.');
        }
        
        // Get leaves for employees under this supervisor
        $query = Leave::query();
        
        // If you have a relationship between supervisor and employees
        // $employeeNumbers = User::where('supervisor_id', $user->employee_number)->pluck('employee_number');
        // $query->whereIn('employee_number', $employeeNumbers);
        
        // Apply filters
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('type') && $request->type != 'all') {
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
            $query->where(function($q) use ($search) {
                $q->where('employee_name', 'like', "%{$search}%")
                  ->orWhere('employee_number', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%");
            });
        }
        
        // Get leaves with pagination
        $leaves = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Calculate statistics
        $leaveStats = [
            'total' => Leave::count(),
            'pending' => Leave::where('status', 'pending')->count(),
            'approved' => Leave::where('status', 'approved')->count(),
            'rejected' => Leave::where('status', 'rejected')->count(),
            'avg_days' => round(Leave::where('status', 'approved')->avg('total_days') ?? 0, 1),
        ];
        
        return view('supervisor.leaves', compact('leaves', 'leaveStats'));
    }
/**
 * Show leave management page.
 *
 * @param Request $request
 * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
 */
public function leaves(Request $request)
{
    $supervisor = Auth::user();
    
    if (!$this->isSupervisor($supervisor)) {
        return redirect()->route('dashboard')
            ->with('error', 'You do not have access to the supervisor dashboard.');
    }
    
    // Get all subordinates in hierarchy
    $allSubordinates = $this->getAllSubordinates($supervisor->employee_number);
    $hierarchyEmployeeNumbers = collect($allSubordinates)->pluck('employee_number')->toArray();
    
    // If no subordinates, return empty collection
    if (empty($hierarchyEmployeeNumbers)) {
        $leaves = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 5, 1);
        $leaveStats = [
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
            'cancelled' => 0,
            'total' => 0,
            'avg_days' => 0,
        ];
        return view('supervisor.leaves', compact('supervisor', 'leaves', 'leaveStats'));
    }
    
    // ===== MANUAL PAGINATION FIX =====
    try {
        $perPage = $request->get('per_page', 5);
        $currentPage = $request->get('page', 1);
        
        // Get a fresh query builder instance
        $query = Leave::whereIn('employee_number', $hierarchyEmployeeNumbers)
            ->with('user');
        
        // Reapply all filters
        if ($request->filled('employee_name')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->employee_name . '%');
            });
        }
        
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('type') && $request->type !== '') {
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
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('employee_number', 'like', "%{$search}%")
                       ->orWhere('job_title', 'like', "%{$search}%");
                })
                ->orWhere('reason', 'like', "%{$search}%");
            });
        }
        
        // Clone the query for counting
        $countQuery = clone $query;
        $total = $countQuery->count();
        
        // Log for debugging
        \Log::info('Manual Pagination - Total records: ' . $total);
        
        // Get the paginated results
        $results = $query->orderBy('created_at', 'desc')
            ->skip(($currentPage - 1) * $perPage)
            ->take($perPage)
            ->get();
        
        // Create manual paginator
        $leaves = new \Illuminate\Pagination\LengthAwarePaginator(
            $results,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        // Log pagination info
        \Log::info('Manual Pagination Info:', [
            'per_page' => $leaves->perPage(),
            'current_page' => $leaves->currentPage(),
            'last_page' => $leaves->lastPage(),
            'total' => $leaves->total(),
            'has_pages' => $leaves->hasPages()
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Pagination error: ' . $e->getMessage());
        $leaves = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 5, 1);
    }
    // ===== END MANUAL PAGINATION FIX =====
    
    // Get leave statistics
    $leaveStats = [
        'pending' => Leave::whereIn('employee_number', $hierarchyEmployeeNumbers)
            ->where('status', 'pending')
            ->count(),
        'approved' => Leave::whereIn('employee_number', $hierarchyEmployeeNumbers)
            ->where('status', 'approved')
            ->count(),
        'rejected' => Leave::whereIn('employee_number', $hierarchyEmployeeNumbers)
            ->where('status', 'rejected')
            ->count(),
        'cancelled' => Leave::whereIn('employee_number', $hierarchyEmployeeNumbers)
            ->where('status', 'cancelled')
            ->count(),
        'total' => Leave::whereIn('employee_number', $hierarchyEmployeeNumbers)
            ->count(),
    ];
    
    // Calculate average days
    $totalDays = Leave::whereIn('employee_number', $hierarchyEmployeeNumbers)
        ->where('status', 'approved')
        ->sum('total_days');
        
    $approvedCount = Leave::whereIn('employee_number', $hierarchyEmployeeNumbers)
        ->where('status', 'approved')
        ->count();
        
    $leaveStats['avg_days'] = $approvedCount > 0 ? round($totalDays / $approvedCount, 1) : 0;
    
    return view('supervisor.leaves', compact('supervisor', 'leaves', 'leaveStats'));
}
    /**
     * Show the form for creating a new leave request.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Get user's remaining leave days
        $leaveBalance = [
            'annual' => 21,
            'sick' => 14,
            'maternity' => 90,
            'paternity' => 10,
            'study' => 10,
            'unpaid' => 30,
            'emergency' => 5,
            'compassionate' => 5,
            'other' => 10,
        ];
        
        return view('leave.create', compact('user', 'leaveBalance'));
    }

   /**
 * Store a newly created leave request.
 */
public function store(Request $request)
{
    $user = Auth::user();
    
    $validated = $request->validate([
        'leave_type' => 'required|in:annual,sick,maternity,paternity,study,unpaid,emergency,compassionate,other',
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after_or_equal:start_date',
        'reason' => 'required|string|min:10|max:1000',
        'contact_address' => 'required|string|max:500',
        'contact_phone' => 'required|string|max:20',
    ]);

    try {
        DB::beginTransaction();

        // Calculate total days
        $start = Carbon::parse($validated['start_date']);
        $end = Carbon::parse($validated['end_date']);
        $totalDays = $start->diffInDays($end) + 1;

        Log::info('Checking leave for employee', [
            'employee_number' => $user->employee_number,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date']
        ]);

        // Check for overlapping leave requests using employee_number
        $existingLeave = Leave::where('employee_number', $user->employee_number)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($validated) {
                $query->where(function($q) use ($validated) {
                    $q->whereBetween('start_date', [$validated['start_date'], $validated['end_date']]);
                })->orWhere(function($q) use ($validated) {
                    $q->whereBetween('end_date', [$validated['start_date'], $validated['end_date']]);
                })->orWhere(function($q) use ($validated) {
                    $q->where('start_date', '<=', $validated['start_date'])
                      ->where('end_date', '>=', $validated['end_date']);
                })->orWhere(function($q) use ($validated) {
                    $q->where('start_date', '>=', $validated['start_date'])
                      ->where('end_date', '<=', $validated['end_date']);
                });
            })
            ->first();

        if ($existingLeave) {
            DB::rollBack();
            
            return back()->withErrors([
                'overlap' => 'You already have a ' . $existingLeave->status . ' leave request from ' . 
                    Carbon::parse($existingLeave->start_date)->format('d/m/Y') . ' to ' . 
                    Carbon::parse($existingLeave->end_date)->format('d/m/Y') . ' during this period.'
            ])->withInput();
        }

        // Get the employee's supervisor (manager)
        $supervisorNumber = null;
        if (isset($user->manager_id) && $user->manager_id) {
            $supervisorNumber = $user->manager_id;
        }

        // Get table columns to know what we can insert
        $columns = DB::getSchemaBuilder()->getColumnListing('leaves');
        Log::info('Leaves table columns:', $columns);

        // Prepare leave data - only include columns that exist
        $leaveData = [
            'employee_number' => $user->employee_number,
            'employee_name' => $user->name,
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'contact_address' => $validated['contact_address'],
            'contact_phone' => $validated['contact_phone'],
            'status' => 'pending',
        ];

        // Add optional columns if they exist
        if (in_array('job_title', $columns)) {
            $leaveData['job_title'] = $user->job_title ?? 'Not specified';
        }
        
        if (in_array('department', $columns)) {
            $leaveData['department'] = $user->department ?? 'Not specified';
        }
        
        if (in_array('applied_on', $columns)) {
            $leaveData['applied_on'] = now();
        }
        
        if (in_array('created_at', $columns)) {
            $leaveData['created_at'] = now();
        }
        
        if (in_array('updated_at', $columns)) {
            $leaveData['updated_at'] = now();
        }

        // Create leave
        $leave = Leave::create($leaveData);

        Log::info('Leave created successfully with ID: ' . $leave->id);

        // Create notification for the user - Check if user exists in users table
        try {
            DB::table('notifications')->insert([
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'type' => 'App\\Notifications\\LeaveRequestSubmitted',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'message' => 'Your leave application has been submitted successfully',
                    'leave_id' => $leave->id,
                    'leave_type' => $leave->leave_type,
                    'start_date' => $leave->start_date->format('d/m/Y'),
                    'end_date' => $leave->end_date->format('d/m/Y'),
                    'employee_number' => $user->employee_number
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            Log::info('Notification created for user: ' . $user->id);
        } catch (\Exception $notifError) {
            // Log notification error but don't fail the main transaction
            Log::error('Failed to create notification for user: ' . $notifError->getMessage(), [
                'user_id' => $user->id,
                'leave_id' => $leave->id
            ]);
        }

        // If employee has a supervisor, notify them too
        if ($supervisorNumber) {
            $supervisor = User::where('employee_number', $supervisorNumber)->first();
            if ($supervisor) {
                try {
                    DB::table('notifications')->insert([
                        'id' => (string) \Illuminate\Support\Str::uuid(),
                        'type' => 'App\\Notifications\\LeaveRequestForApproval',
                        'notifiable_type' => 'App\\Models\\User',
                        'notifiable_id' => $supervisor->id,
                        'data' => json_encode([
                            'message' => $user->name . ' has submitted a leave request for approval',
                            'leave_id' => $leave->id,
                            'employee_name' => $user->name,
                            'employee_number' => $user->employee_number,
                            'leave_type' => $leave->leave_type,
                            'start_date' => $leave->start_date->format('d/m/Y'),
                            'end_date' => $leave->end_date->format('d/m/Y')
                        ]),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    Log::info('Notification created for supervisor: ' . $supervisor->id);
                } catch (\Exception $supNotifError) {
                    Log::error('Failed to create notification for supervisor: ' . $supNotifError->getMessage());
                }
            }
        }

        DB::commit();

        return redirect()->route('leave.index')
            ->with('success', 'Leave request submitted successfully for Employee #' . $user->employee_number . '!');

    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('Leave application error: ' . $e->getMessage(), [
            'employee_number' => $user->employee_number,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()
            ->withInput()
            ->withErrors(['error' => 'An error occurred while submitting your application: ' . $e->getMessage()]);
    }
}

    /**
     * Display the specified leave request.
     */
    public function show(Leave $leave)
    {
        $user = Auth::user();
        
        // Check if user owns this leave or is a supervisor
        if ($leave->employee_number !== $user->employee_number && $user->user_type !== 'supervisor') {
            abort(403, 'Unauthorized access to this leave request.');
        }
        
        return view('leave.show', compact('leave'));
    }

    /**
     * Show the form for editing the specified leave request.
     */
    public function edit(Leave $leave)
    {
        $user = Auth::user();
        
        // Only allow editing if pending and owner
        if ($leave->employee_number !== $user->employee_number) {
            abort(403, 'Unauthorized access to edit this leave request.');
        }
        
        // Check if leave can be edited (pending status)
        if ($leave->status !== 'pending') {
            return redirect()->route('leave.show', $leave)
                ->with('error', 'Only pending leave requests can be edited.');
        }
        
        $leaveBalance = [
            'annual' => 21,
            'sick' => 14,
            'maternity' => 90,
            'paternity' => 10,
            'study' => 10,
            'unpaid' => 30,
            'emergency' => 5,
            'compassionate' => 5,
            'other' => 10,
        ];
        
        return view('leave.edit', compact('leave', 'leaveBalance'));
    }

    /**
     * Update the specified leave request.
     */
    public function update(Request $request, Leave $leave)
    {
        $user = Auth::user();
        
        // Check if user is authenticated
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }
        
        // Check if leave exists
        if (!$leave) {
            return redirect()->route('leave.index')->with('error', 'Leave request not found.');
        }
        
        // Check ownership
        if ($leave->employee_number !== $user->employee_number) {
            abort(403, 'Unauthorized access to update this leave request.');
        }
        
        // Check if leave can be updated (pending status)
        if ($leave->status !== 'pending') {
            return back()->with('error', 'Only pending leave requests can be updated. Current status: ' . $leave->status);
        }
        
        // Validate request
        $validated = $request->validate([
            'leave_type' => 'required|in:annual,sick,maternity,paternity,study,unpaid,emergency,compassionate,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:10|max:1000',
            'contact_address' => 'required|string|max:500',
            'contact_phone' => 'required|string|max:20',
        ]);

        try {
            DB::beginTransaction();

            // Calculate total days
            $start = Carbon::parse($validated['start_date']);
            $end = Carbon::parse($validated['end_date']);
            $totalDays = $start->diffInDays($end) + 1;

            // Check for overlapping leave requests (excluding current one)
            $existingLeave = Leave::where('employee_number', $user->employee_number)
                ->where('id', '!=', $leave->id)
                ->whereIn('status', ['pending', 'approved'])
                ->where(function ($query) use ($validated) {
                    $query->where(function($q) use ($validated) {
                        $q->whereBetween('start_date', [$validated['start_date'], $validated['end_date']]);
                    })->orWhere(function($q) use ($validated) {
                        $q->whereBetween('end_date', [$validated['start_date'], $validated['end_date']]);
                    })->orWhere(function($q) use ($validated) {
                        $q->where('start_date', '<=', $validated['start_date'])
                          ->where('end_date', '>=', $validated['end_date']);
                    })->orWhere(function($q) use ($validated) {
                        $q->where('start_date', '>=', $validated['start_date'])
                          ->where('end_date', '<=', $validated['end_date']);
                    });
                })
                ->first();

            if ($existingLeave) {
                DB::rollBack();
                return back()->withErrors([
                    'overlap' => 'You already have another ' . $existingLeave->status . ' leave request from ' . 
                        Carbon::parse($existingLeave->start_date)->format('d/m/Y') . ' to ' . 
                        Carbon::parse($existingLeave->end_date)->format('d/m/Y') . ' during this period.'
                ])->withInput();
            }

            // Update the leave
            $leave->update([
                'leave_type' => $validated['leave_type'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'total_days' => $totalDays,
                'reason' => $validated['reason'],
                'contact_address' => $validated['contact_address'],
                'contact_phone' => $validated['contact_phone'],
            ]);

            // Create notification for update
            $notificationData = [
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'type' => 'App\\Notifications\\LeaveRequestUpdated',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'message' => 'Your leave application has been updated successfully',
                    'leave_id' => $leave->id,
                    'leave_type' => $leave->leave_type,
                    'start_date' => $leave->start_date->format('d/m/Y'),
                    'end_date' => $leave->end_date->format('d/m/Y'),
                    'employee_number' => $user->employee_number
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Try to insert notification, but don't fail if it doesn't work
            try {
                DB::table('notifications')->insert($notificationData);
                Log::info('Notification inserted successfully');
            } catch (\Exception $notifError) {
                Log::error('Failed to insert notification: ' . $notifError->getMessage());
                // Continue with the transaction even if notification fails
            }

            DB::commit();

            return redirect()->route('leave.show', $leave)
                ->with('success', 'Leave request updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Leave update error: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while updating: ' . $e->getMessage()]);
        }
    }

    /**
     * Cancel the specified leave request.
     */
    public function cancel(Leave $leave)
    {
        $user = Auth::user();
        
        // Only allow cancellation if owner
        if ($leave->employee_number !== $user->employee_number) {
            abort(403, 'Unauthorized access to cancel this leave request.');
        }
        
        // Only allow cancellation if pending
        if ($leave->status !== 'pending') {
            return back()->with('error', 'Only pending leave requests can be cancelled.');
        }
        
        try {
            DB::beginTransaction();
            
            $leave->update([
                'status' => 'cancelled',
                'remarks' => 'Cancelled by employee',
            ]);

            // Create cancellation notification
            DB::table('notifications')->insert([
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'type' => 'App\\Notifications\\LeaveRequestCancelled',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'message' => 'Your leave application has been cancelled',
                    'leave_id' => $leave->id,
                    'employee_number' => $user->employee_number
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();
            
            return redirect()->route('leave.index')
                ->with('success', 'Leave request cancelled successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Leave cancellation error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while cancelling the request.');
        }
    }

    /**
     * Remove the specified leave request.
     */
    public function destroy(Leave $leave)
    {
        $user = Auth::user();
        
        // Only allow deletion if owner
        if ($leave->employee_number !== $user->employee_number) {
            abort(403, 'Unauthorized access to delete this leave request.');
        }
        
        // Only allow deletion if pending
        if ($leave->status !== 'pending') {
            return back()->with('error', 'Only pending leave requests can be deleted.');
        }
        
        $leave->delete();
        
        return redirect()->route('leave.index')
            ->with('success', 'Leave request deleted successfully!');
    }

    /**
     * Supervisor: Approve leave request (AJAX version for modal)
     */
    public function approve(Request $request, Leave $leave)
    {
        $user = Auth::user();
        
        // Only supervisors can approve
        if ($user->user_type !== 'supervisor') {
            return response()->json([
                'success' => false,
                'message' => 'Only supervisors can approve leave requests.'
            ], 403);
        }
        
        // Check if leave is pending
        if ($leave->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This leave request is not pending approval.'
            ], 400);
        }
        
        // Validate request for JSON
        $request->validate([
            'comments' => 'nullable|string|max:500',
        ]);
        
        try {
            DB::beginTransaction();
            
            $updateData = [
                'status' => 'approved',
                'approved_by' => $user->employee_number,
                'approved_at' => now(),
            ];
            
            // Add remarks if provided
            if ($request->has('comments') && !empty($request->comments)) {
                $updateData['remarks'] = $request->comments;
            }
            
            $leave->update($updateData);
            
            // Send notification to the employee
            $employee = User::where('employee_number', $leave->employee_number)->first();
            if ($employee) {
                DB::table('notifications')->insert([
                    'id' => (string) \Illuminate\Support\Str::uuid(),
                    'type' => 'App\\Notifications\\LeaveRequestApproved',
                    'notifiable_type' => 'App\\Models\\User',
                    'notifiable_id' => $employee->id,
                    'data' => json_encode([
                        'message' => 'Your leave request has been approved',
                        'leave_id' => $leave->id,
                        'approved_by' => $user->name,
                        'remarks' => $request->comments ?? 'No remarks',
                        'employee_number' => $leave->employee_number
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Leave request approved successfully!'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Leave approval error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while approving the request.'
            ], 500);
        }
    }

  /**
 * Supervisor: Reject leave request
 */
public function reject(Request $request, Leave $leave)
{
    $user = Auth::user();
    
    // Only supervisors can reject
    if ($user->user_type !== 'supervisor') {
        return response()->json([
            'success' => false,
            'message' => 'Only supervisors can reject leave requests.'
        ], 403);
    }
    
    // Check if leave is pending
    if ($leave->status !== 'pending') {
        return response()->json([
            'success' => false,
            'message' => 'This leave request is not pending approval.'
        ], 400);
    }
    
    // Validate request
    $validator = validator($request->all(), [
        'remarks' => 'required|string|min:5|max:500',
    ]);
    
    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }
    
    try {
        DB::beginTransaction();
        
        // Update the leave
        $leave->status = 'rejected';
        $leave->remarks = $request->remarks;
        $leave->rejected_by = $user->employee_number;
        $leave->rejected_at = now();
        $leave->save();
        
        // Find the employee - IMPORTANT: Check if employee exists
        $employee = User::where('employee_number', $leave->employee_number)->first();
        
        // Create notification for the employee ONLY if employee exists
        if ($employee) {
            try {
                DB::table('notifications')->insert([
                    'id' => (string) \Illuminate\Support\Str::uuid(),
                    'type' => 'App\\Notifications\\LeaveRequestRejected',
                    'notifiable_type' => 'App\\Models\\User',
                    'notifiable_id' => $employee->id,
                    'data' => json_encode([
                        'message' => 'Your leave request has been rejected',
                        'leave_id' => $leave->id,
                        'rejected_by' => $user->name,
                        'remarks' => $request->remarks,
                        'employee_number' => $leave->employee_number
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                Log::info('Notification created for employee: ' . $employee->employee_number);
            } catch (\Exception $notifError) {
                // Log notification error but don't fail the main transaction
                Log::error('Failed to create notification: ' . $notifError->getMessage(), [
                    'employee_id' => $employee->id,
                    'leave_id' => $leave->id
                ]);
            }
        } else {
            Log::warning('Employee not found for notification', [
                'employee_number' => $leave->employee_number,
                'leave_id' => $leave->id
            ]);
        }
        
        DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => 'Leave request rejected successfully!'
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Leave rejection error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'leave_id' => $leave->id
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while rejecting the request.'
        ], 500);
    }
}

    /**
     * Supervisor: Approve leave request (Form version for direct submission)
     */
    public function approveForm(Request $request, Leave $leave)
    {
        $user = Auth::user();
        
        // Only supervisors can approve
        if ($user->user_type !== 'supervisor') {
            abort(403, 'Only supervisors can approve leave requests.');
        }
        
        // Check if leave is pending
        if ($leave->status !== 'pending') {
            return back()->with('error', 'This leave request is not pending approval.');
        }
        
        $request->validate([
            'remarks' => 'nullable|string|max:500',
        ]);
        
        try {
            DB::beginTransaction();
            
            $updateData = [
                'status' => 'approved',
                'approved_by' => $user->employee_number,
                'approved_at' => now(),
            ];
            
            // Add remarks if provided
            if ($request->has('remarks') && !empty($request->remarks)) {
                $updateData['remarks'] = $request->remarks;
            }
            
            $leave->update($updateData);
            
            // Send notification to the employee
            $employee = User::where('employee_number', $leave->employee_number)->first();
            if ($employee) {
                DB::table('notifications')->insert([
                    'id' => (string) \Illuminate\Support\Str::uuid(),
                    'type' => 'App\\Notifications\\LeaveRequestApproved',
                    'notifiable_type' => 'App\\Models\\User',
                    'notifiable_id' => $employee->id,
                    'data' => json_encode([
                        'message' => 'Your leave request has been approved',
                        'leave_id' => $leave->id,
                        'approved_by' => $user->name,
                        'remarks' => $request->remarks ?? 'No remarks',
                        'employee_number' => $leave->employee_number
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('leave.show', $leave)
                ->with('success', 'Leave request approved successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Leave approval error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while approving the request.');
        }
    }

    /**
     * Supervisor: Reject leave request (Form version for direct submission)
     */
    public function rejectForm(Request $request, Leave $leave)
    {
        $user = Auth::user();
        
        // Only supervisors can reject
        if ($user->user_type !== 'supervisor') {
            abort(403, 'Only supervisors can reject leave requests.');
        }
        
        // Check if leave is pending
        if ($leave->status !== 'pending') {
            return back()->with('error', 'This leave request is not pending approval.');
        }
        
        // Validate remarks for form submission
        $request->validate([
            'remarks' => 'required|string|min:5|max:500',
        ]);
        
        try {
            DB::beginTransaction();
            
            $leave->update([
                'status' => 'rejected',
                'remarks' => $request->remarks,
                'rejected_by' => $user->employee_number,
                'rejected_at' => now(),
            ]);
            
            // Send notification to the employee
            $employee = User::where('employee_number', $leave->employee_number)->first();
            if ($employee) {
                DB::table('notifications')->insert([
                    'id' => (string) \Illuminate\Support\Str::uuid(),
                    'type' => 'App\\Notifications\\LeaveRequestRejected',
                    'notifiable_type' => 'App\\Models\\User',
                    'notifiable_id' => $employee->id,
                    'data' => json_encode([
                        'message' => 'Your leave request has been rejected',
                        'leave_id' => $leave->id,
                        'rejected_by' => $user->name,
                        'remarks' => $request->remarks,
                        'employee_number' => $leave->employee_number
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Leave request rejected successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Leave rejection error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while rejecting the request.');
        }
    }

    /**
     * Get potential next approvers for a leave request
     */
    public function getNextApprovers(Leave $leave)
    {
        $user = Auth::user();
        
        if ($user->user_type !== 'supervisor') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        try {
            // This is a placeholder - implement your logic to find next approvers
            $nextApprover = null;
            $potentialApprovers = [];
            
            // Example: Get all supervisors in the same department
            $employee = User::where('employee_number', $leave->employee_number)->first();
            if ($employee && $employee->department) {
                $potentialApprovers = User::where('user_type', 'supervisor')
                    ->where('department', $employee->department)
                    ->where('employee_number', '!=', $user->employee_number)
                    ->take(5)
                    ->get()
                    ->map(function($supervisor) {
                        return [
                            'name' => $supervisor->name,
                            'employee_number' => $supervisor->employee_number,
                            'job_title' => $supervisor->job_title ?? 'Supervisor',
                            'department' => $supervisor->department
                        ];
                    });
            }
            
            return response()->json([
                'success' => true,
                'next_approver' => $nextApprover,
                'potential_approvers' => $potentialApprovers
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get next approvers error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load approvers'
            ], 500);
        }
    }

    /**
     * Supervisor: Bulk approve leaves
     */
    public function bulkApprove(Request $request)
    {
        $user = Auth::user();
        
        if ($user->user_type !== 'supervisor') {
            abort(403, 'Only supervisors can perform bulk actions.');
        }
        
        $request->validate([
            'leave_ids' => 'required|array',
            'leave_ids.*' => 'exists:leaves,id',
            'remarks' => 'nullable|string|max:500',
        ]);
        
        $count = 0;
        
        try {
            DB::beginTransaction();
            
            foreach ($request->leave_ids as $id) {
                $leave = Leave::find($id);
                
                if ($leave && $leave->status === 'pending') {
                    $updateData = [
                        'status' => 'approved',
                        'approved_by' => $user->employee_number,
                        'approved_at' => now(),
                    ];
                    
                    if ($request->has('remarks') && !empty($request->remarks)) {
                        $updateData['remarks'] = $request->remarks;
                    }
                    
                    $leave->update($updateData);
                    
                    // Send notification to each employee
                    $employee = User::where('employee_number', $leave->employee_number)->first();
                    if ($employee) {
                        DB::table('notifications')->insert([
                            'id' => (string) \Illuminate\Support\Str::uuid(),
                            'type' => 'App\\Notifications\\LeaveRequestApproved',
                            'notifiable_type' => 'App\\Models\\User',
                            'notifiable_id' => $employee->id,
                            'data' => json_encode([
                                'message' => 'Your leave request has been approved',
                                'leave_id' => $leave->id,
                                'approved_by' => $user->name,
                                'remarks' => $request->remarks ?? 'No remarks',
                                'employee_number' => $leave->employee_number
                            ]),
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                    
                    $count++;
                }
            }
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', "{$count} leave requests approved successfully!");
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk approve error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred during bulk approval.');
        }
    }

    /**
     * Supervisor: Bulk reject leaves
     */
    public function bulkReject(Request $request)
    {
        $user = Auth::user();
        
        if ($user->user_type !== 'supervisor') {
            abort(403, 'Only supervisors can perform bulk actions.');
        }
        
        $request->validate([
            'leave_ids' => 'required|array',
            'leave_ids.*' => 'exists:leaves,id',
            'remarks' => 'required|string|min:5|max:500',
        ]);
        
        $count = 0;
        
        try {
            DB::beginTransaction();
            
            foreach ($request->leave_ids as $id) {
                $leave = Leave::find($id);
                
                if ($leave && $leave->status === 'pending') {
                    $leave->update([
                        'status' => 'rejected',
                        'remarks' => $request->remarks,
                        'rejected_by' => $user->employee_number,
                        'rejected_at' => now(),
                    ]);
                    
                    // Send notification to each employee
                    $employee = User::where('employee_number', $leave->employee_number)->first();
                    if ($employee) {
                        DB::table('notifications')->insert([
                            'id' => (string) \Illuminate\Support\Str::uuid(),
                            'type' => 'App\\Notifications\\LeaveRequestRejected',
                            'notifiable_type' => 'App\\Models\\User',
                            'notifiable_id' => $employee->id,
                            'data' => json_encode([
                                'message' => 'Your leave request has been rejected',
                                'leave_id' => $leave->id,
                                'rejected_by' => $user->name,
                                'remarks' => $request->remarks,
                                'employee_number' => $leave->employee_number
                            ]),
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                    
                    $count++;
                }
            }
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', "{$count} leave requests rejected successfully!");
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk reject error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred during bulk rejection.');
        }
    }

    /**
     * Get notifications for the current user
     */
    public function getNotifications()
    {
        $user = Auth::user();
        
        $notifications = DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', 'App\\Models\\User')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        $unreadCount = DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', 'App\\Models\\User')
            ->whereNull('read_at')
            ->count();
            
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markNotificationAsRead($id)
    {
        DB::table('notifications')
            ->where('id', $id)
            ->update(['read_at' => now()]);
            
        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead()
    {
        $user = Auth::user();
        
        DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', 'App\\Models\\User')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
            
        return response()->json(['success' => true]);
    }

    /**
     * API: Check leave availability for an employee
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'employee_number' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        try {
            // Check for existing leaves
            $existingLeave = Leave::where('employee_number', $request->employee_number)
                ->whereIn('status', ['pending', 'approved'])
                ->where(function ($query) use ($request) {
                    $query->where(function($q) use ($request) {
                        $q->whereBetween('start_date', [$request->start_date, $request->end_date]);
                    })->orWhere(function($q) use ($request) {
                        $q->whereBetween('end_date', [$request->start_date, $request->end_date]);
                    })->orWhere(function($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                          ->where('end_date', '>=', $request->end_date);
                    })->orWhere(function($q) use ($request) {
                        $q->where('start_date', '>=', $request->start_date)
                          ->where('end_date', '<=', $request->end_date);
                    });
                })
                ->first();

            if ($existingLeave) {
                return response()->json([
                    'available' => false,
                    'message' => 'Employee #' . $request->employee_number . ' already has a ' . $existingLeave->status . ' leave from ' . 
                        Carbon::parse($existingLeave->start_date)->format('d/m/Y') . ' to ' . 
                        Carbon::parse($existingLeave->end_date)->format('d/m/Y')
                ]);
            }

            return response()->json([
                'available' => true,
                'message' => 'Leave period is available'
            ]);

        } catch (\Exception $e) {
            Log::error('Availability check error: ' . $e->getMessage());
            return response()->json([
                'available' => false,
                'message' => 'Error checking availability'
            ], 500);
        }
    }
}