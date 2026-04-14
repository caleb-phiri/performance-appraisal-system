<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appraisal;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

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
     * Display the supervisor dashboard with hierarchical reporting.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function dashboard()
    {
        $supervisor = Auth::user();
        
        // Check if user is onboarded
        if (!$supervisor->is_onboarded) {
            return redirect()->route('onboarding.survey')
                ->with('info', 'Please complete your profile setup first.');
        }
        
        // Check if user is supervisor
        if (!$this->isSupervisor($supervisor)) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have access to the supervisor dashboard.');
        }
        
        // Get all users in the reporting hierarchy (direct and indirect reports)
        $allSubordinates = $this->getAllSubordinates($supervisor->employee_number);
        
        // Get team members (direct reports only for display)
        $team = User::where('manager_id', $supervisor->employee_number)
            ->where('is_onboarded', true)
            ->orderBy('name')
            ->get();
        
        // Get all users in hierarchy for data queries
        $hierarchyEmployeeNumbers = collect($allSubordinates)->pluck('employee_number')->toArray();
        
        // Initialize empty collections for no team members case
        $pendingAppraisals = collect();
        $approvedAppraisals = collect();
        
        // Get appraisals only if team has members
        if (!empty($hierarchyEmployeeNumbers)) {
            // Get pending appraisals from all users in hierarchy
            $pendingAppraisals = Appraisal::with('user')
                ->whereIn('employee_number', $hierarchyEmployeeNumbers)
                ->where('status', 'submitted')
                ->orderBy('updated_at', 'desc')
                ->get();
            
            // Get approved appraisals from all users in hierarchy
            $approvedAppraisals = Appraisal::with('user')
                ->whereIn('employee_number', $hierarchyEmployeeNumbers)
                ->where('status', 'approved')
                ->orderBy('updated_at', 'desc')
                ->get();
        }
        
        // ===== NEW: Subordinate Supervisors Feature =====
        // Get supervisors who report to the current supervisor (managers where this user is their manager)
        $subordinateSupervisors = User::where('manager_id', $supervisor->employee_number)
            ->where('user_type', 'supervisor')
            ->where('is_onboarded', true)
            ->orderBy('name')
            ->get();
        
        // Get all appraisals from subordinate supervisors
        $subordinateAppraisals = collect();
        $subordinateSupervisorsCount = $subordinateSupervisors->count();
        $subordinateTotalAppraisals = 0;
        $subordinatePendingAppraisals = 0;
        $subordinateApprovedAppraisals = 0;
        $subordinateRejectedAppraisals = 0;
        $subordinateAvgScore = 0;
        $uniquePeriods = collect();
        
        if ($subordinateSupervisorsCount > 0) {
            $subordinateEmployeeNumbers = $subordinateSupervisors->pluck('employee_number')->toArray();
            
            $subordinateAppraisals = Appraisal::with('user')
                ->whereIn('employee_number', $subordinateEmployeeNumbers)
                ->orderBy('created_at', 'desc')
                ->get();
            
            $subordinateTotalAppraisals = $subordinateAppraisals->count();
            $subordinatePendingAppraisals = $subordinateAppraisals->where('status', 'submitted')->count();
            $subordinateApprovedAppraisals = $subordinateAppraisals->where('status', 'approved')->count();
            $subordinateRejectedAppraisals = $subordinateAppraisals->where('status', 'rejected')->count();
            
            // Calculate average score from approved appraisals
            $approvedSubordinateAppraisals = $subordinateAppraisals->where('status', 'approved');
            if ($approvedSubordinateAppraisals->count() > 0) {
                $subordinateAvgScore = $approvedSubordinateAppraisals->avg('final_score') ?? 0;
            }
            
            // Get unique periods for filter
            $uniquePeriods = $subordinateAppraisals->pluck('period')->unique()->filter();
        }
        // ===== END NEW =====
        
        // Calculate team statistics
        $stats = [
            'team_size' => count($allSubordinates),
            'direct_reports' => $team->count(),
            'indirect_reports' => count($allSubordinates) - $team->count(),
            'avg_final_score' => $approvedAppraisals->avg('final_score') ?? 0,
        ];
        
        // Get leave statistics for all subordinates
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

        if (!empty($hierarchyEmployeeNumbers)) {
            // Get pending leaves for display
            $pendingLeaves = Leave::with('user')
                ->whereIn('employee_number', $hierarchyEmployeeNumbers)
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            // Get upcoming approved leaves
            $upcomingLeaves = Leave::with('user')
                ->whereIn('employee_number', $hierarchyEmployeeNumbers)
                ->where('status', 'approved')
                ->where('start_date', '>=', now())
                ->orderBy('start_date')
                ->limit(5)
                ->get();
            
            // Calculate leave statistics
            $leaveStats['pending'] = Leave::whereIn('employee_number', $hierarchyEmployeeNumbers)
                ->where('status', 'pending')
                ->count();
            
            $leaveStats['approved'] = Leave::whereIn('employee_number', $hierarchyEmployeeNumbers)
                ->where('status', 'approved')
                ->count();
            
            $leaveStats['rejected'] = Leave::whereIn('employee_number', $hierarchyEmployeeNumbers)
                ->where('status', 'rejected')
                ->count();
            
            $leaveStats['cancelled'] = Leave::whereIn('employee_number', $hierarchyEmployeeNumbers)
                ->where('status', 'cancelled')
                ->count();
            
            $leaveStats['total'] = Leave::whereIn('employee_number', $hierarchyEmployeeNumbers)
                ->count();
            
            // Calculate average days
            $totalDays = Leave::whereIn('employee_number', $hierarchyEmployeeNumbers)
                ->where('status', 'approved')
                ->sum('total_days');
            
            $approvedCount = Leave::whereIn('employee_number', $hierarchyEmployeeNumbers)
                ->where('status', 'approved')
                ->count();
            
            $leaveStats['avg_days'] = $approvedCount > 0 ? round($totalDays / $approvedCount, 1) : 0;
        }
        
        // Build hierarchical team structure for display
        $teamHierarchy = $this->buildTeamHierarchy($supervisor->employee_number);
        
        return view('supervisor.dashboard', compact(
            'supervisor',
            'team',
            'allSubordinates',
            'teamHierarchy',
            'pendingAppraisals',
            'approvedAppraisals',
            'stats',
            'leaveStats',
            'pendingLeaves',
            'upcomingLeaves',
            'hierarchyEmployeeNumbers',
            // New variables for subordinate supervisors
            'subordinateSupervisors',
            'subordinateAppraisals',
            'subordinateSupervisorsCount',
            'subordinateTotalAppraisals',
            'subordinatePendingAppraisals',
            'subordinateApprovedAppraisals',
            'subordinateRejectedAppraisals',
            'subordinateAvgScore',
            'uniquePeriods'
        ));
    }

    /**
     * Export subordinate appraisals as CSV.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\JsonResponse
     */
    public function exportSubordinateAppraisals(Request $request)
    {
        $supervisor = Auth::user();
        
        if (!$this->isSupervisor($supervisor)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Get supervisors who report to the current supervisor
        $subordinateSupervisors = User::where('manager_id', $supervisor->employee_number)
            ->where('user_type', 'supervisor')
            ->where('is_onboarded', true)
            ->get();
        
        if ($subordinateSupervisors->isEmpty()) {
            return response()->json(['error' => 'No subordinate supervisors found'], 404);
        }
        
        // Build query for appraisals
        $query = Appraisal::whereIn('employee_number', $subordinateSupervisors->pluck('employee_number'))
            ->with('user');
        
        // Apply filters
        if ($request->filled('supervisor')) {
            $query->where('employee_number', $request->supervisor);
        }
        
        if ($request->filled('period')) {
            $query->where('period', $request->period);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $appraisals = $query->orderBy('created_at', 'desc')->get();
        
        if ($appraisals->isEmpty()) {
            return response()->json(['error' => 'No appraisals found'], 404);
        }
        
        // Create CSV export
        $filename = 'subordinate_appraisals_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($appraisals) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Add headers
            fputcsv($file, [
                'Supervisor Name',
                'Employee Number',
                'Job Title',
                'Department',
                'Period',
                'Self Score',
                'Final Score',
                'Status',
                'Submitted Date',
                'Approved/Rejected Date'
            ]);
            
            // Add data rows
            foreach ($appraisals as $appraisal) {
                fputcsv($file, [
                    $appraisal->user->name ?? 'Unknown',
                    $appraisal->employee_number,
                    $appraisal->user->job_title ?? 'N/A',
                    $appraisal->user->department ?? 'N/A',
                    $appraisal->period,
                    number_format($appraisal->self_score ?? 0, 2) . '%',
                    number_format($appraisal->final_score ?? 0, 2) . '%',
                    ucfirst($appraisal->status),
                    $appraisal->created_at->format('Y-m-d H:i:s'),
                    $appraisal->updated_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show team members list.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function team()
    {
        $supervisor = Auth::user();
        
        if (!$this->isSupervisor($supervisor)) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have access to the supervisor dashboard.');
        }
        
        // Get all subordinates in hierarchy
        $allSubordinates = $this->getAllSubordinates($supervisor->employee_number);
        $subordinateNumbers = collect($allSubordinates)->pluck('employee_number')->toArray();
        
        $team = User::whereIn('employee_number', $subordinateNumbers)
            ->where('is_onboarded', true)
            ->orderBy('name')
            ->paginate(10);

        return view('supervisor.team', compact('supervisor', 'team'));
    }

    /**
     * Show reports page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function reports()
    {
        $supervisor = Auth::user();
        
        if (!$this->isSupervisor($supervisor)) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have access to the supervisor dashboard.');
        }
        
        return view('supervisor.reports', compact('supervisor'));
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
    
    // Get per page value (default to 5)
    $perPage = $request->get('per_page', 5);
    
    // Build the query
    $query = Leave::whereIn('employee_number', $hierarchyEmployeeNumbers)
        ->with('user');
    
    // Apply all filters
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
    
    // Apply ordering
    $query->orderBy('created_at', 'desc');
    
    // Get paginated results - THIS IS THE KEY FIX
    // Use Laravel's built-in paginate instead of manual pagination
    $leaves = $query->paginate($perPage);
    
    // Preserve query parameters in pagination links
    $leaves->appends($request->query());
    
    // Debug logging
    \Log::info('Pagination Debug:', [
        'total_records' => $leaves->total(),
        'per_page' => $leaves->perPage(),
        'current_page' => $leaves->currentPage(),
        'last_page' => $leaves->lastPage(),
        'items_count' => $leaves->count(),
        'has_pages' => $leaves->hasPages() ? 'Yes' : 'No'
    ]);
    
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
     * Get appraisals for a specific team member.
     *
     * @param string $employeeNumber
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMemberAppraisals($employeeNumber)
    {
        $supervisor = Auth::user();
        
        if (!$this->isSupervisor($supervisor)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Verify this employee is in the supervisor's hierarchy
        $allSubordinates = $this->getAllSubordinates($supervisor->employee_number);
        $subordinateNumbers = collect($allSubordinates)->pluck('employee_number')->toArray();
        
        if (!in_array($employeeNumber, $subordinateNumbers) && $employeeNumber !== $supervisor->employee_number) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $appraisals = Appraisal::with('user')
            ->where('employee_number', $employeeNumber)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $member = User::where('employee_number', $employeeNumber)->first();
        
        return response()->json([
            'success' => true,
            'member' => $member,
            'appraisals' => $appraisals,
            'stats' => [
                'avg_score' => $appraisals->where('status', 'approved')->avg('final_score') ?? 0,
                'approved_count' => $appraisals->where('status', 'approved')->count(),
                'pending_count' => $appraisals->where('status', 'submitted')->count(),
                'draft_count' => $appraisals->where('status', 'draft')->count(),
            ]
        ]);
    }

    /**
     * Get team hierarchy data for AJAX.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeamHierarchy()
    {
        $supervisor = Auth::user();
        
        if (!$this->isSupervisor($supervisor)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $hierarchy = $this->buildTeamHierarchy($supervisor->employee_number);
        
        return response()->json([
            'success' => true,
            'hierarchy' => $hierarchy
        ]);
    }

    /**
     * Approve a leave request.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function approveLeave(Request $request, $id)
    {
        $supervisor = Auth::user();
        
        if (!$this->isSupervisor($supervisor)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }
        
        $leave = Leave::findOrFail($id);
        
        // Verify this employee is in the supervisor's hierarchy
        $allSubordinates = $this->getAllSubordinates($supervisor->employee_number);
        $subordinateNumbers = collect($allSubordinates)->pluck('employee_number')->toArray();
        
        if (!in_array($leave->employee_number, $subordinateNumbers)) {
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
        
        $leave->status = 'approved';
        $leave->approved_by = $supervisor->employee_number ?? $supervisor->id;
        $leave->approved_at = now();
        $leave->remarks = $request->remarks ?? 'Approved by supervisor';
        $leave->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Leave request approved successfully.'
        ]);
    }

    /**
     * Reject a leave request.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function rejectLeave(Request $request, $id)
    {
        $supervisor = Auth::user();
        
        if (!$this->isSupervisor($supervisor)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }
        
        $leave = Leave::findOrFail($id);
        
        // Verify this employee is in the supervisor's hierarchy
        $allSubordinates = $this->getAllSubordinates($supervisor->employee_number);
        $subordinateNumbers = collect($allSubordinates)->pluck('employee_number')->toArray();
        
        if (!in_array($leave->employee_number, $subordinateNumbers)) {
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
        
        $request->validate([
            'remarks' => 'required|string|min:10|max:500'
        ]);
        
        $leave->status = 'rejected';
        $leave->approved_by = $supervisor->employee_number ?? $supervisor->id;
        $leave->approved_at = now();
        $leave->remarks = $request->remarks;
        $leave->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Leave request rejected.'
        ]);
    }

    /**
     * Cancel an approved leave.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelLeave(Request $request, $id)
    {
        $supervisor = Auth::user();
        
        if (!$this->isSupervisor($supervisor)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }
        
        $leave = Leave::findOrFail($id);
        
        // Verify this employee is in the supervisor's hierarchy
        $allSubordinates = $this->getAllSubordinates($supervisor->employee_number);
        $subordinateNumbers = collect($allSubordinates)->pluck('employee_number')->toArray();
        
        if (!in_array($leave->employee_number, $subordinateNumbers)) {
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
        
        $request->validate([
            'remarks' => 'required|string|min:10|max:500'
        ]);
        
        $leave->status = 'cancelled';
        $leave->remarks = $request->remarks;
        $leave->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Leave cancelled successfully.'
        ]);
    }

    /**
     * Quick rate an employee.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function rateEmployee(Request $request)
    {
        $supervisor = Auth::user();
        
        if (!$this->isSupervisor($supervisor)) {
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
        
        // Verify this employee is in the supervisor's hierarchy
        $allSubordinates = $this->getAllSubordinates($supervisor->employee_number);
        $subordinateNumbers = collect($allSubordinates)->pluck('employee_number')->toArray();
        
        if (!in_array($validated['employee_number'], $subordinateNumbers)) {
            return response()->json([
                'success' => false,
                'message' => 'You can only rate employees in your team.'
            ], 403);
        }
        
        // Save the rating - implement your rating logic here
        // Example: AppraisalRating::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Rating submitted successfully!',
            'data' => $validated
        ]);
    }

    /**
     * Recursively get all subordinates (direct and indirect) for a supervisor.
     *
     * @param string $supervisorEmployeeNumber
     * @return array
     */
    private function getAllSubordinates($supervisorEmployeeNumber)
    {
        $allSubordinates = [];
        
        // Get direct reports
        $directReports = User::where('manager_id', $supervisorEmployeeNumber)
            ->where('is_onboarded', true)
            ->get();
        
        foreach ($directReports as $report) {
            $allSubordinates[] = $report;
            
            // If this report is also a supervisor, get their subordinates recursively
            if ($this->isSupervisor($report)) {
                $indirectReports = $this->getAllSubordinates($report->employee_number);
                $allSubordinates = array_merge($allSubordinates, $indirectReports);
            }
        }
        
        return $allSubordinates;
    }

    /**
     * Build hierarchical team structure for display.
     *
     * @param string $supervisorEmployeeNumber
     * @param int $level
     * @return array
     */
    private function buildTeamHierarchy($supervisorEmployeeNumber, $level = 0)
    {
        $hierarchy = [];
        
        $directReports = User::where('manager_id', $supervisorEmployeeNumber)
            ->where('is_onboarded', true)
            ->orderBy('name')
            ->get();
        
        foreach ($directReports as $report) {
            $memberData = [
                'user' => $report,
                'level' => $level,
                'children' => []
            ];
            
            // If this report is a supervisor, get their subordinates
            if ($this->isSupervisor($report)) {
                $memberData['children'] = $this->buildTeamHierarchy($report->employee_number, $level + 1);
            }
            
            $hierarchy[] = $memberData;
        }
        
        return $hierarchy;
    }

    /**
     * Check if user is supervisor.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    private function isSupervisor($user)
    {
        // Method 1: Check user_type field
        if (isset($user->user_type) && $user->user_type === 'supervisor') {
            return true;
        }
        
        // Method 2: Check role field
        if (isset($user->role) && ($user->role === 'supervisor' || $user->role === 'admin')) {
            return true;
        }
        
        // Method 3: Check if user has team members by manager_id
        if (User::where('manager_id', $user->employee_number)->where('is_onboarded', true)->exists()) {
            return true;
        }
        
        // Method 4: Check if user has team members by supervisor_id (alternative field)
        if (User::where('supervisor_id', $user->id)->where('is_onboarded', true)->exists()) {
            return true;
        }
        
        return false;
    }
}