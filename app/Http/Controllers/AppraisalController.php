<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appraisal;
use App\Models\AppraisalKpa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use App\Notifications\PIPInitiatedNotification; 

class AppraisalController extends Controller
{
    /**
     * Show dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // For employees: show their appraisals
        if ($user->user_type === 'employee') {
            $appraisals = Appraisal::where('employee_number', $user->employee_number)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
                
            $stats = [
                'total' => Appraisal::where('employee_number', $user->employee_number)->count(),
                'submitted' => Appraisal::where('employee_number', $user->employee_number)
                    ->where('status', 'submitted')
                    ->count(),
                'draft' => Appraisal::where('employee_number', $user->employee_number)
                    ->where('status', 'draft')
                    ->count(),
                'approved' => Appraisal::where('employee_number', $user->employee_number)
                    ->where('status', 'approved')
                    ->count(),
            ];
            
            return view('dashboard', compact('appraisals', 'stats', 'user'));
        }
        
        // For supervisors: show ALL team members' appraisals (including indirect reports)
        if ($user->user_type === 'supervisor') {
            // Get ALL team members (direct reports, indirect reports, and assigned employees)
            $allTeamMembers = $this->getAllSubordinates($user->employee_number);
            
            // Also get employees from pivot table (assigned employees)
            $assignedEmployees = collect();
            if (method_exists($user, 'rateableEmployees')) {
                $assignedEmployees = $user->rateableEmployees()
                    ->when(method_exists($user->rateableEmployees()->getModel(), 'scopeActiveCompany'), function ($query) {
                        $query->activeCompany();
                    })
                    ->get();
            }
            
            // Merge all collections and remove duplicates
            $allTeamMembers = collect($allTeamMembers)
                ->merge($assignedEmployees)
                ->unique('employee_number');
            
            $teamEmployeeNumbers = $allTeamMembers->pluck('employee_number')->toArray();
            
            if (!empty($teamEmployeeNumbers)) {
                // Get recent appraisals
                $appraisals = Appraisal::whereIn('employee_number', $teamEmployeeNumbers)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
                    
                // Get pending approvals (submitted appraisals)
                $pendingApprovals = Appraisal::whereIn('employee_number', $teamEmployeeNumbers)
                    ->where('status', 'submitted')
                    ->count();
                    
                // Get approved appraisals count
                $approvedAppraisals = Appraisal::whereIn('employee_number', $teamEmployeeNumbers)
                    ->where('status', 'approved')
                    ->count();
                    
                $stats = [
                    'team_members' => $allTeamMembers->count(),
                    'pending_approvals' => $pendingApprovals,
                    'total_appraisals' => Appraisal::whereIn('employee_number', $teamEmployeeNumbers)->count(),
                    'approved_appraisals' => $approvedAppraisals,
                ];
            } else {
                $appraisals = collect();
                $stats = [
                    'team_members' => 0,
                    'pending_approvals' => 0,
                    'total_appraisals' => 0,
                    'approved_appraisals' => 0,
                ];
            }
            
            return view('dashboard', compact('appraisals', 'stats', 'allTeamMembers', 'user'));
        }
        
        // For admins: show all appraisals
        $appraisals = Appraisal::orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $stats = [
            'total' => Appraisal::count(),
            'submitted' => Appraisal::where('status', 'submitted')->count(),
            'draft' => Appraisal::where('status', 'draft')->count(),
            'approved' => Appraisal::where('status', 'approved')->count(),
        ];
        
        return view('dashboard', compact('appraisals', 'stats', 'user'));
    }
    

    /**
     * Initiate a Performance Improvement Plan
     */
    public function initiatePIP(Request $request, Appraisal $appraisal)
    {
        try {
            // Log the request for debugging
            Log::info('PIP Initiation Started', [
                'appraisal_id' => $appraisal->id,
                'request_data' => $request->all()
            ]);

            // Validate the request
            $validated = $request->validate([
                'pip_start_date' => 'required|date',
                'pip_end_date' => 'required|date|after:pip_start_date',
                'pip_plan' => 'required|string|min:10',
                'pip_supervisor_notes' => 'nullable|string',
            ]);

            // Update the appraisal with PIP information
            $appraisal->pip_initiated = true;
            $appraisal->pip_start_date = $validated['pip_start_date'];
            $appraisal->pip_end_date = $validated['pip_end_date'];
            $appraisal->pip_plan = $validated['pip_plan'];
            $appraisal->pip_supervisor_notes = $validated['pip_supervisor_notes'] ?? null;
            $appraisal->pip_initiated_at = now();
            $appraisal->pip_initiated_by = auth()->id();
            $appraisal->pip_status = 'active';
            
            // Save the appraisal
            $saved = $appraisal->save();
            
            Log::info('PIP Save Result', [
                'saved' => $saved,
                'appraisal_id' => $appraisal->id,
                'pip_initiated' => $appraisal->pip_initiated,
                'pip_start_date' => $appraisal->pip_start_date,
                'pip_end_date' => $appraisal->pip_end_date
            ]);

            if (!$saved) {
                throw new \Exception('Failed to save PIP data to database');
            }

            // Send notification to employee (optional)
            $this->sendPipNotification($appraisal);

            // Check if request expects JSON (AJAX request)
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Performance Improvement Plan initiated successfully!',
                    'data' => [
                        'pip_start_date' => $appraisal->pip_start_date->format('Y-m-d'),
                        'pip_end_date' => $appraisal->pip_end_date->format('Y-m-d'),
                        'pip_plan' => $appraisal->pip_plan
                    ]
                ]);
            }

            // For regular form submission
            return redirect()->back()->with('success', 'Performance Improvement Plan initiated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('PIP Validation Error', ['errors' => $e->errors()]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return redirect()->back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            Log::error('PIP Initiation Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to initiate PIP: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to initiate PIP: ' . $e->getMessage());
        }
    }

    /**
     * Send notification to employee about PIP
     */
    private function sendPipNotification($appraisal)
    {
        try {
            $employee = $appraisal->user;
            $supervisor = auth()->user();
            
            if ($employee && $employee->email) {
                // You can implement email notification here
                // For now, just log it
                Log::info('PIP Notification would be sent to: ' . $employee->email);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to send PIP notification: ' . $e->getMessage());
        }
    }

    /**
     * View PIP details (for the modal)
     */
    public function getPIPDetails(Appraisal $appraisal)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'pip_start_date' => $appraisal->pip_start_date?->format('M d, Y'),
                'pip_end_date' => $appraisal->pip_end_date?->format('M d, Y'),
                'pip_plan' => $appraisal->pip_plan,
                'pip_supervisor_notes' => $appraisal->pip_supervisor_notes,
                'pip_initiated_at' => $appraisal->pip_initiated_at?->format('M d, Y'),
                'pip_initiated_by' => $appraisal->pipInitiator?->name ?? 'System'
            ]
        ]);
    }




    /**
     * Helper method to check if user is assigned supervisor
     */
    private function checkIfAssignedSupervisor(Appraisal $appraisal, $user)
    {
        $employee = $appraisal->user;
        if (!$employee) return false;

        // Check rating supervisors
        if ($employee->ratingSupervisors) {
            foreach ($employee->ratingSupervisors as $supervisor) {
                $supervisorId = $supervisor->employee_number ?? $supervisor->id;
                if ($supervisorId == $user->employee_number) {
                    return true;
                }
            }
        }

        // Check manager_id fallback
        return $employee->manager_id === $user->employee_number;
    }



   /**
 * Display all PIPs in a table format
 */
public function pipManagement(Request $request)
{
    $user = auth()->user();
    
    // Build query based on user role
    $query = Appraisal::where('pip_initiated', true)
        ->with(['user', 'pipInitiator', 'kpas']);
    
    // Supervisors only see their team members
    if ($user->user_type === 'supervisor') {
        $employeeNumbers = $this->getTeamEmployeeNumbers($user);
        
        // If supervisor has no team members, return empty result
        if (empty($employeeNumbers)) {
            $pips = collect();
            $stats = [
                'total' => 0,
                'active' => 0,
                'completed' => 0,
            ];
            $departments = [];
            
            return view('pip.management', compact('pips', 'stats', 'departments'));
        }
        
        $query->whereIn('employee_number', $employeeNumbers);
    }
    
    // Apply filters
    if ($request->filled('status')) {
        $status = $request->status;
        if ($status === 'active') {
            $query->where('pip_end_date', '>=', now());
        } elseif ($status === 'completed') {
            $query->where('pip_end_date', '<', now());
        }
    }
    
    if ($request->filled('department')) {
        $query->whereHas('user', function($q) use ($request) {
            $q->where('department', $request->department);
        });
    }
    
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('employee_number', 'like', "%{$search}%")
              ->orWhereHas('user', function($sub) use ($search) {
                  $sub->where('name', 'like', "%{$search}%");
              });
        });
    }
    
    // Get paginated results
    $pips = $query->orderBy('pip_start_date', 'desc')
                  ->paginate(15)
                  ->withQueryString();
    
    // Get statistics
    $statsQuery = Appraisal::where('pip_initiated', true);
    if ($user->user_type === 'supervisor' && !empty($employeeNumbers ?? [])) {
        $statsQuery->whereIn('employee_number', $employeeNumbers);
    }
    
    $stats = [
        'total' => (clone $statsQuery)->count(),
        'active' => (clone $statsQuery)->where('pip_end_date', '>=', now())->count(),
        'completed' => (clone $statsQuery)->where('pip_end_date', '<', now())->count(),
    ];
    
    // Get departments for filter (admins only)
    $departments = [];
    if ($user->user_type === 'admin' || $user->user_type === 'hr') {
        $departments = User::whereNotNull('department')
            ->distinct()
            ->pluck('department');
    }
    
    return view('pip.management', compact('pips', 'stats', 'departments'));
}

/**
 * Get team employee numbers for supervisor
 */
private function getTeamEmployeeNumbers($supervisor)
{
    $employeeNumbers = [];
    
    if (!$supervisor || !$supervisor->employee_number) {
        return $employeeNumbers;
    }
    
    $supervisorNumber = $supervisor->employee_number;
    
    // Get direct reports (employees where manager_id matches supervisor's employee_number)
    $directReports = User::where('manager_id', $supervisorNumber)->get();
    
    foreach ($directReports as $employee) {
        if ($employee->employee_number) {
            $employeeNumbers[] = $employee->employee_number;
        }
    }
    
    // Get employees where this supervisor is in rating_supervisors
    // Fix: Use explicit table aliases to avoid ambiguous column
    $ratingReports = User::whereHas('ratingSupervisors', function($q) use ($supervisorNumber) {
        $q->where('employee_rating_supervisors.supervisor_id', $supervisorNumber);
    })->get();
    
    foreach ($ratingReports as $employee) {
        if ($employee->employee_number && !in_array($employee->employee_number, $employeeNumbers)) {
            $employeeNumbers[] = $employee->employee_number;
        }
    }
    
    return $employeeNumbers;
}

/**
 * Show single PIP details
 */
public function showPipDetails(Appraisal $appraisal)
{
    $this->authorizePipAccess($appraisal);
    
    // Get progress tracking
    $progress = $this->calculatePipProgress($appraisal);
    
    return view('pip.show', compact('appraisal', 'progress'));
}

/**
 * Update PIP status
 */
public function updatePipStatus(Request $request, Appraisal $appraisal)
{
    $this->authorizePipAccess($appraisal);
    
    $validated = $request->validate([
        'status' => 'required|in:active,completed,extended',
        'new_end_date' => 'required_if:status,extended|nullable|date|after:today',
        'completion_notes' => 'nullable|string',
    ]);
    
    if ($validated['status'] === 'completed') {
        $appraisal->update([
            'pip_completed_at' => now(),
            'pip_completion_notes' => $validated['completion_notes'] ?? null,
            'pip_status' => 'completed',
        ]);
        
        // Send notification
        $this->sendPipNotification($appraisal, 'completed');
        
        $message = 'PIP marked as completed.';
        
    } elseif ($validated['status'] === 'extended') {
        $appraisal->update([
            'pip_end_date' => $validated['new_end_date'],
            'pip_extension_reason' => $validated['completion_notes'] ?? null,
            'pip_extended_at' => now(),
            'pip_extended_by' => auth()->id(),
        ]);
        
        $this->sendPipNotification($appraisal, 'extended', $validated['new_end_date']);
        $message = 'PIP extended successfully.';
        
    } else {
        $message = 'PIP status updated.';
    }
    
    if ($request->expectsJson()) {
        return response()->json(['success' => true, 'message' => $message]);
    }
    
    return redirect()->back()->with('success', $message);
}

/**
 * Export PIPs to CSV/Excel
 */
public function exportPips(Request $request)
{
    $user = auth()->user();
    
    $query = Appraisal::where('pip_initiated', true)
        ->with(['user', 'pipInitiator']);
    
    if ($user->user_type === 'supervisor') {
        $employeeNumbers = $this->getTeamEmployeeNumbers($user);
        $query->whereIn('employee_number', $employeeNumbers);
    }
    
    $pips = $query->get();
    
    $filename = 'pip_report_' . date('Y-m-d') . '.csv';
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename={$filename}",
    ];
    
    $callback = function() use ($pips) {
        $file = fopen('php://output', 'w');
        
        // Add headers
        fputcsv($file, [
            'Employee Name',
            'Employee Number',
            'Department',
            'PIP Start Date',
            'PIP End Date',
            'Status',
            'Final Score',
            'Initiated By',
            'Initiated Date',
            'Plan Summary'
        ]);
        
        foreach ($pips as $pip) {
            $status = $pip->pip_end_date < now() ? 'Completed' : 'Active';
            
            fputcsv($file, [
                $pip->user->name ?? 'N/A',
                $pip->employee_number,
                $pip->user->department ?? 'N/A',
                $pip->pip_start_date?->format('Y-m-d'),
                $pip->pip_end_date?->format('Y-m-d'),
                $status,
                number_format($this->calculateTotalScore($pip), 1) . '%',
                $pip->pipInitiator?->name ?? 'System',
                $pip->pip_initiated_at?->format('Y-m-d'),
                Str::limit($pip->pip_plan ?? '', 100),
            ]);
        }
        
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
}

/**
 * Calculate total score for an appraisal
 */
private function calculateTotalScore($appraisal)
{
    $totalScore = 0;
    foreach ($appraisal->kpas as $kpa) {
        $kpi = $kpa->kpi ?: 4;
        $finalRating = $kpa->supervisor_rating ?? $kpa->self_rating;
        $totalScore += ($finalRating / $kpi) * $kpa->weight;
    }
    return $totalScore;
}

/**
 * Calculate PIP progress percentage
 */
private function calculatePipProgress($appraisal)
{
    if (!$appraisal->pip_start_date || !$appraisal->pip_end_date) {
        return 0;
    }
    
    $start = $appraisal->pip_start_date;
    $end = $appraisal->pip_end_date;
    $now = now();
    
    if ($now < $start) {
        return 0;
    }
    
    if ($now > $end) {
        return 100;
    }
    
    $totalDays = $start->diffInDays($end);
    $daysPassed = $start->diffInDays($now);
    
    return round(($daysPassed / $totalDays) * 100);
}

/**
 * Authorize PIP access
 */
private function authorizePipAccess($appraisal)
{
    $user = auth()->user();
    
    if ($user->user_type === 'admin' || $user->user_type === 'hr') {
        return true;
    }
    
    if ($user->user_type === 'supervisor') {
        $employeeNumbers = $this->getTeamEmployeeNumbers($user);
        if (in_array($appraisal->employee_number, $employeeNumbers)) {
            return true;
        }
    }
    
    abort(403, 'Unauthorized access to this PIP.');
}


    /**
     * Display individual appraisal forms (all forms)
     */
    public function plazaManager()
    {
        return view('appraisals.Plaza_Manager');
    }

    public function emTechnician()
    {
        return view('appraisals.E&M');
    }

    public function adminClerk()
    {
        return view('appraisals.admin-clerk');
    }

    public function shiftManager()
    {
        return view('appraisals.shift-manager');
    }

    public function seniorTollCollector()
    {
        return view('appraisals.senior-toll-collector-form');
    }

    public function tollCollector()
    {
        return view('appraisals.toll-collector-form');
    }

    public function tce()
    {
        return view('appraisals.TCE');
    }

    public function routePatrolDriver()
    {
        return view('appraisals.route-patrol-driver-form');
    }

    public function plazaAttendant()
    {
        return view('appraisals.plaza-attendant-form');
    }

    public function laneAttendant()
    {
        return view('appraisals.lane-attendant-form');
    }

    public function hrAssistant()
    {
        return view('appraisals.hr-assistant-form');
    }

    /**
     * Verification Clerk Form
     */
    public function verificationclerk()
    {
        $user = Auth::user();
        $currentQuarter = $this->getCurrentQuarter();
        
        return view('appraisals.verification-clerk-form', [
            'user' => $user,
            'quarter' => $currentQuarter['quarter'],
            'quarterName' => $currentQuarter['quarter_name'],
            'quarterMonths' => $currentQuarter['quarter_months'],
            'dueDate' => $currentQuarter['due_date'],
            'year' => $currentQuarter['year'],
            'jobTitle' => 'verification clerk',
            'formType' => 'verification_clerk',
        ]);
    }

    /**
     * HR Advisor Form
     */
    public function hrAdvisor()
    {
        $user = Auth::user();
        $currentQuarter = $this->getCurrentQuarter();
        
        return view('appraisals.hr-advisor-form', [
            'user' => $user,
            'quarter' => $currentQuarter['quarter'],
            'quarterName' => $currentQuarter['quarter_name'],
            'quarterMonths' => $currentQuarter['quarter_months'],
            'dueDate' => $currentQuarter['due_date'],
            'year' => $currentQuarter['year'],
            'jobTitle' => 'HR Advisor',
            'formType' => 'hr_advisor',
        ]);
    }
    
    /**
     * Admin Manager Form
     */
    public function adminManager()
    {
        $user = Auth::user();
        $currentQuarter = $this->getCurrentQuarter();
        
        return view('appraisals.admin-manager-form', [
            'user' => $user,
            'quarter' => $currentQuarter['quarter'],
            'quarterName' => $currentQuarter['quarter_name'],
            'quarterMonths' => $currentQuarter['quarter_months'],
            'dueDate' => $currentQuarter['due_date'],
            'year' => $currentQuarter['year'],
            'jobTitle' => 'Admin Manager',
            'formType' => 'admin_manager',
        ]);
    }

    /**
     * Trainer Form
     */
    public function trainer()
    {
        $user = Auth::user();
        $currentQuarter = $this->getCurrentQuarter();
        
        return view('appraisals.trainer-form', [
            'user' => $user,
            'quarter' => $currentQuarter['quarter'],
            'quarterName' => $currentQuarter['quarter_name'],
            'quarterMonths' => $currentQuarter['quarter_months'],
            'dueDate' => $currentQuarter['due_date'],
            'year' => $currentQuarter['year'],
            'jobTitle' => 'Trainer',
            'formType' => 'trainer',
        ]);
    }

    /**
     * Senior Trainer Form
     */
    public function seniorTrainer()
    {
        $user = Auth::user();
        $currentQuarter = $this->getCurrentQuarter();
        
        return view('appraisals.senior-trainer-form', [
            'user' => $user,
            'quarter' => $currentQuarter['quarter'],
            'quarterName' => $currentQuarter['quarter_name'],
            'quarterMonths' => $currentQuarter['quarter_months'],
            'dueDate' => $currentQuarter['due_date'],
            'year' => $currentQuarter['year'],
            'jobTitle' => 'Senior Trainer',
            'formType' => 'senior_trainer',
        ]);
    }

    /**
     * Senior TCE Form
     */
    public function seniorTce()
    {
        $user = Auth::user();
        $currentQuarter = $this->getCurrentQuarter();
        
        return view('appraisals.senior-tce-form', [
            'user' => $user,
            'quarter' => $currentQuarter['quarter'],
            'quarterName' => $currentQuarter['quarter_name'],
            'quarterMonths' => $currentQuarter['quarter_months'],
            'dueDate' => $currentQuarter['due_date'],
            'year' => $currentQuarter['year'],
            'jobTitle' => 'Senior TCE',
            'formType' => 'senior_tce',
        ]);
    }

    /**
     * Media and Customer Coordinator Form
     */
    public function mediaCoordinator()
    {
        $user = Auth::user();
        $currentQuarter = $this->getCurrentQuarter();
        
        return view('appraisals.media-coordinator-form', [
            'user' => $user,
            'quarter' => $currentQuarter['quarter'],
            'quarterName' => $currentQuarter['quarter_name'],
            'quarterMonths' => $currentQuarter['quarter_months'],
            'dueDate' => $currentQuarter['due_date'],
            'year' => $currentQuarter['year'],
            'jobTitle' => 'Media and Customer Coordinator',
            'formType' => 'media_coordinator',
        ]);
    }
    
    /**
 * Show list of appraisals with supervisor filtering
 */
public function index(Request $request)
{
    try {
        $user = Auth::user();
        
        // ADD THIS LINE - Determine if user is supervisor
        $isSupervisor = $user->user_type === 'supervisor';
        
        // Initialize variables
        $employee = $user;
        $teamMembers = collect();
        $employeeNumber = null;
        
        // For supervisors: get ALL team members and handle employee filter
        if ($isSupervisor) {
            // Get ALL team members (direct reports, indirect reports, and assigned employees)
            $allTeamMembers = $this->getAllSubordinates($user->employee_number);
            
            // Also get assigned employees from pivot table
            $assignedEmployees = collect();
            if (method_exists($user, 'rateableEmployees')) {
                $assignedEmployees = $user->rateableEmployees()
                    ->when(method_exists($user->rateableEmployees()->getModel(), 'scopeActiveCompany'), function ($query) {
                        $query->activeCompany();
                    })
                    ->get();
            }
            
            // Get direct reports via manager_id
            $directReports = User::where('manager_id', $user->employee_number)
                ->where('user_type', 'employee')
                ->when(method_exists(User::class, 'scopeActiveCompany'), function ($query) {
                    $query->activeCompany();
                })
                ->get();
            
            // Merge all and deduplicate
            $teamMembers = collect($allTeamMembers)
                ->merge($assignedEmployees)
                ->merge($directReports)
                ->unique('employee_number');
            
            // If employee_number parameter is provided, filter by that employee
            if ($request->has('employee_number')) {
                $employeeNumber = $request->query('employee_number');
                
                // Verify the employee is in the combined team
                $employee = $teamMembers->firstWhere('employee_number', $employeeNumber);
                
                if (!$employee) {
                    // If not in team, fall back to current user
                    $employee = $user;
                    $employeeNumber = $user->employee_number;
                }
            } else {
                // If no employee filter, show all team members' appraisals
                $employee = null;
            }
        } else if ($user->user_type === 'employee') {
            // Employees see only their own appraisals
            $employeeNumber = $user->employee_number;
        } else {
            // Admin or other user types
            $employee = $user;
            $employeeNumber = null;
        }
        
        // Build query
        $query = Appraisal::query();
        
        // Apply employee filter
        if ($employeeNumber) {
            $query->where('employee_number', $employeeNumber);
        } elseif ($isSupervisor && !$request->has('employee_number')) {
            // For supervisor viewing all team members
            $teamEmployeeNumbers = $teamMembers->pluck('employee_number')->toArray();
            if (!empty($teamEmployeeNumbers)) {
                $query->whereIn('employee_number', $teamEmployeeNumbers);
            } else {
                $query->where('employee_number', '0'); // No team members
            }
        }
        
        // ADDED: Year filter
        if ($request->has('year') && !empty($request->year) && $request->year !== 'all') {
            // Filter by year - match period patterns like "Q1 2024", "Q2 2024", etc.
            $query->where('period', 'like', '%' . $request->year);
        }
        
        // ADDED: Quarter filter - exact match on period field
        if ($request->has('quarter') && !empty($request->quarter) && $request->quarter !== 'all') {
            $query->where('period', $request->quarter);
        }
        
        // Load KPAs for score calculation
        $query->with('kpas');
        
        // Apply sorting
        $query->orderBy('created_at', 'desc');
        
        // Get paginated results
        $perPage = $request->get('per_page', 5);
        $appraisals = $query->paginate($perPage);
        
        // Calculate statistics
        $statsQuery = Appraisal::query();
        
        if ($employeeNumber) {
            $statsQuery->where('employee_number', $employeeNumber);
        } elseif ($isSupervisor && !$request->has('employee_number')) {
            $teamEmployeeNumbers = $teamMembers->pluck('employee_number')->toArray();
            if (!empty($teamEmployeeNumbers)) {
                $statsQuery->whereIn('employee_number', $teamEmployeeNumbers);
            }
        }
        
        // Apply year filter to stats
        if ($request->has('year') && !empty($request->year) && $request->year !== 'all') {
            $statsQuery->where('period', 'like', '%' . $request->year);
        }
        
        // Apply quarter filter to stats as well
        if ($request->has('quarter') && !empty($request->quarter) && $request->quarter !== 'all') {
            $statsQuery->where('period', $request->quarter);
        }
        
        $submittedCount = $statsQuery->where('status', 'submitted')->count();
        $draftCount = $statsQuery->where('status', 'draft')->count();
        
        // Calculate average score
        $averageScore = 0;
        $totalScore = 0;
        $count = 0;
        
        foreach ($appraisals as $appraisal) {
            // Calculate final score from KPAs if needed
            $finalScore = $this->calculateAppraisalScore($appraisal);
            if ($finalScore > 0) {
                $totalScore += $finalScore;
                $count++;
            }
        }
        
        if ($count > 0) {
            $averageScore = round($totalScore / $count, 1);
        }
        
        // GET AVAILABLE YEARS FROM DATABASE
        $availableYears = [];
        
        // Build base query for available years
        $yearsQuery = Appraisal::query();
        
        // Apply the same user permissions to years query
        if ($employeeNumber) {
            $yearsQuery->where('employee_number', $employeeNumber);
        } elseif ($isSupervisor && !$request->has('employee_number')) {
            $teamEmployeeNumbers = $teamMembers->pluck('employee_number')->toArray();
            if (!empty($teamEmployeeNumbers)) {
                $yearsQuery->whereIn('employee_number', $teamEmployeeNumbers);
            } else {
                $yearsQuery->where('employee_number', '0');
            }
        }
        
        // Extract years from period field (e.g., "Q1 2024" -> 2024)
        $periods = $yearsQuery
            ->select('period')
            ->distinct()
            ->pluck('period')
            ->toArray();
        
        $availableYears = [];
        foreach ($periods as $period) {
            if (preg_match('/(\d{4})$/', $period, $matches)) {
                $year = $matches[1];
                if (!in_array($year, $availableYears)) {
                    $availableYears[] = $year;
                }
            }
        }
        
        // Sort years in descending order (newest first)
        rsort($availableYears);
        
        // GET AVAILABLE QUARTERS FROM DATABASE - Only quarters that actually exist
        $availableQuarters = [];
        
        // Build base query for available quarters
        $quartersQuery = Appraisal::query();
        
        // Apply the same user permissions to quarters query
        if ($employeeNumber) {
            $quartersQuery->where('employee_number', $employeeNumber);
        } elseif ($isSupervisor && !$request->has('employee_number')) {
            $teamEmployeeNumbers = $teamMembers->pluck('employee_number')->toArray();
            if (!empty($teamEmployeeNumbers)) {
                $quartersQuery->whereIn('employee_number', $teamEmployeeNumbers);
            } else {
                $quartersQuery->where('employee_number', '0');
            }
        }
        
        // Apply year filter to quarters query if year is selected
        if ($request->has('year') && !empty($request->year) && $request->year !== 'all') {
            $quartersQuery->where('period', 'like', '%' . $request->year);
        }
        
        // Get distinct period values from appraisals table
        $availableQuarters = $quartersQuery
            ->select('period')
            ->distinct()
            ->orderBy('period', 'desc')
            ->pluck('period')
            ->toArray();
        
        // Create quarters array with month ranges for display
        $quarters = [];
        foreach ($availableQuarters as $quarterValue) {
            // Parse quarter to get month ranges
            if (preg_match('/Q([1-4])\s*(\d{4})/', $quarterValue, $matches)) {
                $quarterNum = $matches[1];
                $year = $matches[2];
                
                switch ($quarterNum) {
                    case 1:
                        $quarters[$quarterValue] = 'Jan - Mar';
                        break;
                    case 2:
                        $quarters[$quarterValue] = 'Apr - Jun';
                        break;
                    case 3:
                        $quarters[$quarterValue] = 'Jul - Sep';
                        break;
                    case 4:
                        $quarters[$quarterValue] = 'Oct - Dec';
                        break;
                    default:
                        $quarters[$quarterValue] = $quarterValue;
                }
            } else {
                $quarters[$quarterValue] = $quarterValue;
            }
        }
        
        // If no quarters exist in database, set to empty array
        if (empty($availableQuarters)) {
            $availableQuarters = [];
            $quarters = [];
        }
        
        // Return view with all variables including isSupervisor
        return view('appraisals.index', [
            'appraisals' => $appraisals,
            'employee' => $employee,
            'teamMembers' => $teamMembers,
            'submittedCount' => $submittedCount,
            'draftCount' => $draftCount,
            'averageScore' => $averageScore,
            'availableYears' => $availableYears,
            'availableQuarters' => $availableQuarters,
            'quarters' => $quarters,
            'isSupervisor' => $isSupervisor
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Appraisal index error: ' . $e->getMessage());
        
        // Get available years from database even in error case
        $periods = Appraisal::select('period')
            ->distinct()
            ->pluck('period')
            ->toArray();
        
        $availableYears = [];
        foreach ($periods as $period) {
            if (preg_match('/(\d{4})$/', $period, $matches)) {
                $year = $matches[1];
                if (!in_array($year, $availableYears)) {
                    $availableYears[] = $year;
                }
            }
        }
        rsort($availableYears);
        
        // Get available quarters from database even in error case
        $availableQuarters = Appraisal::select('period')
            ->distinct()
            ->orderBy('period', 'desc')
            ->pluck('period')
            ->toArray();
        
        $quarters = [];
        foreach ($availableQuarters as $quarterValue) {
            if (preg_match('/Q([1-4])\s*(\d{4})/', $quarterValue, $matches)) {
                $quarterNum = $matches[1];
                $year = $matches[2];
                
                switch ($quarterNum) {
                    case 1:
                        $quarters[$quarterValue] = 'Jan - Mar';
                        break;
                    case 2:
                        $quarters[$quarterValue] = 'Apr - Jun';
                        break;
                    case 3:
                        $quarters[$quarterValue] = 'Jul - Sep';
                        break;
                    case 4:
                        $quarters[$quarterValue] = 'Oct - Dec';
                        break;
                    default:
                        $quarters[$quarterValue] = $quarterValue;
                }
            } else {
                $quarters[$quarterValue] = $quarterValue;
            }
        }
        
        return view('appraisals.index', [
            'appraisals' => collect([]),
            'employee' => Auth::user(),
            'teamMembers' => collect([]),
            'submittedCount' => 0,
            'draftCount' => 0,
            'averageScore' => 0,
            'availableYears' => $availableYears,
            'availableQuarters' => $availableQuarters,
            'quarters' => $quarters,
            'isSupervisor' => Auth::user()->user_type === 'supervisor'
        ])->with('error', 'An error occurred while loading appraisals.');
    }
}

    /**
     * Show create form (generic - users should use specific form methods above)
     */
    public function create()
    {
        return view('appraisals.create');
    }

    /**
     * Store new appraisal
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'period' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:draft,submitted',
            'development_needs' => 'nullable|string',
            'employee_comments' => 'nullable|string',
            'kpas' => 'required|array',
            'kpas.*.category' => 'required|string',
            'kpas.*.kpa' => 'required|string',
            'kpas.*.result_indicators' => 'required|string',
            'kpas.*.kpi' => 'required|integer',
            'kpas.*.weight' => 'required|integer|min:0|max:100',
            'kpas.*.self_rating' => 'required|integer|between:1,4',
            'kpas.*.comments' => 'nullable|string',
        ]);

        // Check total weight = 100%
        $totalWeight = collect($validated['kpas'])->sum('weight');
        if ($totalWeight !== 100 && $request->status == 'submitted') {
            return back()->withErrors(['total_weight' => 'Total weight must equal 100% before submitting.'])->withInput();
        }

        // Calculate weighted score
        $weightedScore = 0;
        foreach ($validated['kpas'] as $kpa) {
            $weightedScore += ($kpa['self_rating'] * $kpa['weight']) / 100;
        }

        try {
            DB::beginTransaction();

            // Get current user's employee number
            $employeeNumber = Auth::user()->employee_number;
            $user = Auth::user();

            // Create appraisal
            $appraisal = Appraisal::create([
                'employee_number' => $employeeNumber,
                'employee_name' => $user->name,
                'department' => $user->department,
                'position' => $user->position,
                'period' => $validated['period'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'status' => $validated['status'],
                'development_needs' => $validated['development_needs'] ?? null,
                'employee_comments' => $validated['employee_comments'] ?? null,
                'total_weight' => $totalWeight,
                'self_score' => round($weightedScore, 2),
                'overall_score' => round($weightedScore, 2),
                'created_by' => $user->id,
            ]);

            // Create KPAs
            foreach ($validated['kpas'] as $index => $kpaData) {
                AppraisalKpa::create([
                    'appraisal_id' => $appraisal->id,
                    'category' => $kpaData['category'],
                    'kpa' => $kpaData['kpa'],
                    'result_indicators' => $kpaData['result_indicators'],
                    'kpi' => $kpaData['kpi'],
                    'weight' => $kpaData['weight'],
                    'self_rating' => $kpaData['self_rating'],
                    'comments' => $kpaData['comments'] ?? null,
                    'order' => $index,
                ]);
            }

            DB::commit();

            $message = $validated['status'] === 'draft' 
                ? 'Appraisal saved as draft successfully!'
                : 'Appraisal submitted successfully!';
                
            // Redirect to dashboard instead of show page
            if ($validated['status'] === 'submitted') {
                return redirect()->route('dashboard')->with('success', $message);
            }
            
            return redirect()->route('appraisals.show', $appraisal->id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to save appraisal: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show single appraisal
     */
    public function show(Appraisal $appraisal)
    {
        $user = Auth::user();
        
        // Employees can only view their own appraisals
        if ($user->user_type === 'employee' && $appraisal->employee_number !== $user->employee_number) {
            abort(403, 'Unauthorized access to this appraisal.');
        }
        
        // Supervisors can view appraisals of their team members (including indirect reports)
        if ($user->user_type === 'supervisor') {
            // Check if user is authorized to view this appraisal
            if (!$this->canViewAppraisal($user, $appraisal)) {
                abort(403, 'Unauthorized access to this appraisal.');
            }
        }
        
        $appraisal->load('kpas');
        
        // Load user data for the employee
        $employee = User::where('employee_number', $appraisal->employee_number)->first();
        
        // Check if multiple supervisors are assigned
        $hasMultipleSupervisors = false;
        $ratingSupervisors = collect();
        $currentUserSupervisorData = null;
        
        if ($employee && DB::table('employee_rating_supervisors')->exists()) {
            $ratingSupervisors = DB::table('employee_rating_supervisors')
                ->where('employee_number', $employee->employee_number)
                ->whereNull('kpa_id')
                ->get();
            
            $hasMultipleSupervisors = $ratingSupervisors->count() > 1;
            
            // Get current supervisor's assignment data
            $currentUserSupervisorData = $ratingSupervisors
                ->where('supervisor_id', $user->employee_number)
                ->first();
        }
        
        // Check if current supervisor can approve
        $canApprove = false;
        $isAssignedSupervisor = false;
        $isPrimarySupervisor = false;
        $isHigherLevelSupervisor = false;
        
        if ($user->user_type === 'supervisor') {
            // Check if supervisor is assigned to this employee
            $isAssignedSupervisor = false;
            $isPrimarySupervisor = false;
            
            // 1. Check direct report via manager_id
            if ($employee && $employee->manager_id === $user->employee_number) {
                $isAssignedSupervisor = true;
                $isPrimarySupervisor = true; // Direct manager is always primary
            }
            
            // 2. Check assigned via pivot table (if not already found)
            if (!$isAssignedSupervisor && DB::table('employee_rating_supervisors')->exists()) {
                $supervisorAssignment = DB::table('employee_rating_supervisors')
                    ->where('supervisor_id', $user->employee_number)
                    ->where('employee_number', $appraisal->employee_number)
                    ->whereNull('kpa_id')
                    ->first();
                
                if ($supervisorAssignment) {
                    $isAssignedSupervisor = true;
                    $isPrimarySupervisor = $supervisorAssignment->is_primary ?? false;
                }
            }
            
            // 3. Check if user is a higher-level supervisor (in the management chain)
            $isHigherLevelSupervisor = $this->isHigherLevelSupervisor($user, $appraisal->employee_number);
            
            // Check if can approve (either assigned or higher-level)
            if ($isAssignedSupervisor || $isHigherLevelSupervisor) {
                $canApprove = $this->canSupervisorApprove($user, $appraisal);
            }
        }
        
        return view('appraisals.show', compact(
            'appraisal', 
            'employee', 
            'hasMultipleSupervisors', 
            'ratingSupervisors',
            'currentUserSupervisorData',
            'canApprove',
            'isAssignedSupervisor',
            'isPrimarySupervisor',
            'isHigherLevelSupervisor'
        ));
    }

    /**
     * Check if user can view appraisal
     */
    private function canViewAppraisal($user, $appraisal)
    {
        // User can view their own appraisal
        if ($appraisal->employee_number === $user->employee_number) {
            return true;
        }
        
        // Check direct manager relationship
        $employee = User::where('employee_number', $appraisal->employee_number)->first();
        if ($employee && $employee->manager_id === $user->employee_number) {
            return true;
        }
        
        // Check pivot table assignment
        if (DB::table('employee_rating_supervisors')->exists()) {
            $isAssigned = DB::table('employee_rating_supervisors')
                ->where('supervisor_id', $user->employee_number)
                ->where('employee_number', $appraisal->employee_number)
                ->whereNull('kpa_id')
                ->exists();
            
            if ($isAssigned) {
                return true;
            }
        }
        
        // Check if user is in management chain (higher-level supervisor)
        return $this->isInManagementChain($user, $appraisal->employee_number);
    }

    /**
     * Check if appraisal can be edited
     */
    public function canEdit(Appraisal $appraisal)
    {
        $user = Auth::user();
        
        // Cannot edit approved appraisals
        if ($appraisal->status === 'approved') {
            return false;
        }
        
        // Employees can edit their own appraisals (draft OR submitted)
        if ($user->user_type === 'employee') {
            return $appraisal->employee_number === $user->employee_number;
        }
        
        // Supervisors can edit appraisals of their team members
        if ($user->user_type === 'supervisor') {
            return $this->canViewAppraisal($user, $appraisal);
        }
        
        return false;
    }

    /**
     * Edit appraisal - Allow editing for draft AND submitted
     */
    public function edit(Appraisal $appraisal)
    {
        $user = Auth::user();
        
        // Check if user can edit this appraisal
        if (!$this->canEdit($appraisal)) {
            if ($appraisal->status === 'approved') {
                abort(403, 'Approved appraisals cannot be edited.');
            }
            abort(403, 'Unauthorized access to edit this appraisal.');
        }

        $appraisal->load('kpas');
        
        // Get quarter info
        $quarterInfo = $this->getQuarterInfo();
        
        // Show warning if this is a resubmission
        $isResubmission = $appraisal->status === 'submitted';
        
        return view('appraisals.edit', compact('appraisal', 'quarterInfo', 'user', 'isResubmission'));
    }

    /**
     * Update appraisal
     */
    public function update(Request $request, Appraisal $appraisal)
    {
        $user = Auth::user();

        // Check if user can edit
        if (!$this->canEdit($appraisal)) {
            abort(403, 'Unauthorized access to update this appraisal.');
        }

        $validated = $request->validate([
            'status' => 'required|in:draft,submitted',
            'development_needs' => 'nullable|string',
            'employee_comments' => 'nullable|string',
            'kpas' => 'required|array',
            'kpas.*.weight' => 'required|integer|min:0|max:100',
            'kpas.*.self_rating' => 'required|integer|between:1,4',
            'kpas.*.comments' => 'nullable|string',
            'agreement_option' => 'required|in:agree,not_agree',
            'manager_reason' => 'nullable|string',
        ]);

        // Validate total weight
        $totalWeight = collect($validated['kpas'])->sum('weight');
        if ($validated['status'] === 'submitted' && $totalWeight !== 100) {
            return back()
                ->withErrors(['total_weight' => 'Total weight must equal 100% before submitting.'])
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Update KPAs
            foreach ($validated['kpas'] as $kpaId => $data) {
                AppraisalKpa::where('id', $kpaId)
                    ->where('appraisal_id', $appraisal->id)
                    ->update([
                        'weight' => $data['weight'],
                        'self_rating' => $data['self_rating'],
                        'comments' => $data['comments'] ?? null,
                    ]);
            }

            // Recalculate score
            $selfScore = $this->calculateAppraisalScore($appraisal->fresh('kpas'));

            // Track resubmission
            $wasSubmitted = $appraisal->status === 'submitted';
            $newStatus = $validated['status'];
            
            $updateData = [
                'status' => $newStatus,
                'development_needs' => $validated['development_needs'],
                'employee_comments' => $validated['employee_comments'],
                'total_weight' => $totalWeight,
                'self_score' => $selfScore,
                'overall_score' => $selfScore,
                'agreement_option' => $validated['agreement_option'],
                'manager_reason' => $validated['manager_reason'] ?? null,
            ];

            // Track resubmission if it was already submitted
            if ($wasSubmitted && $newStatus === 'submitted') {
                $updateData['resubmitted_at'] = now();
                $updateData['resubmitted_by'] = $user->employee_number;
                $updateData['resubmission_count'] = DB::raw('COALESCE(resubmission_count, 0) + 1');
            }

            $appraisal->update($updateData);

            DB::commit();

            $message = $newStatus === 'submitted' 
                ? ($wasSubmitted ? 'Appraisal resubmitted successfully!' : 'Appraisal submitted successfully!')
                : 'Appraisal updated successfully!';

            return redirect()->route('appraisals.show', $appraisal)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Delete appraisal - Permanently delete from database
     */
    public function destroy(Appraisal $appraisal)
    {
        $user = Auth::user();
        
        // Authorization check
        if ($user->user_type === 'employee') {
            // Employee can only delete their own appraisals
            if ($appraisal->employee_number !== $user->employee_number) {
                abort(403, 'Unauthorized access to delete this appraisal.');
            }
            
            // Cannot delete approved appraisals
            if ($appraisal->status === 'approved') {
                abort(403, 'Approved appraisals cannot be deleted.');
            }
            
        } else if ($user->user_type === 'supervisor') {
            // Supervisors can delete:
            // 1. Their OWN appraisals (when they're in employee mode)
            // 2. Draft appraisals of their team members
            
            $isOwnAppraisal = ($appraisal->employee_number === $user->employee_number);
            
            if ($isOwnAppraisal) {
                // Supervisor deleting their own appraisal
                if ($appraisal->status === 'approved') {
                    abort(403, 'Approved appraisals cannot be deleted.');
                }
            } else {
                // Supervisor deleting team member's appraisal
                if ($appraisal->status !== 'draft') {
                    abort(403, 'Supervisors can only delete draft appraisals of team members.');
                }
                
                if (!$this->canViewAppraisal($user, $appraisal)) {
                    abort(403, 'Unauthorized access to delete this appraisal.');
                }
            }
        } else if ($user->user_type === 'admin') {
            // Admin can delete any appraisal except approved ones
            if ($appraisal->status === 'approved') {
                abort(403, 'Approved appraisals cannot be deleted.');
            }
        }

        try {
            DB::beginTransaction();
            
            // Get appraisal details for logging
            $appraisalId = $appraisal->id;
            $employeeNumber = $appraisal->employee_number;
            $period = $appraisal->period;
            
            // Delete all associated KPAs first (foreign key constraint)
            $kpasDeleted = $appraisal->kpas()->delete();
            
            // Then delete the appraisal itself - USE delete() for permanent deletion
            $appraisalDeleted = $appraisal->delete();
            
            DB::commit();
            
            // Log the deletion
            \Log::info('Appraisal permanently deleted', [
                'appraisal_id' => $appraisalId,
                'employee_number' => $employeeNumber,
                'period' => $period,
                'deleted_by' => $user->employee_number,
                'deleted_by_role' => $user->user_type,
                'kpas_deleted' => $kpasDeleted,
                'deleted_at' => now()
            ]);
            
            return redirect()->route('dashboard')
                ->with('success', 'Appraisal #' . $appraisalId . ' has been permanently deleted from the database.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Failed to delete appraisal', [
                'appraisal_id' => $appraisal->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to delete appraisal: ' . $e->getMessage());
        }
    }

    /**
     * Submit appraisal (change status from draft to submitted)
     */
    public function submit(Appraisal $appraisal)
    {
        $user = Auth::user();
        
        if ($user->user_type === 'employee') {
            if ($appraisal->employee_number !== $user->employee_number || $appraisal->status !== 'draft') {
                abort(403, 'Unauthorized access to submit this appraisal.');
            }
        } else if ($user->user_type === 'supervisor') {
            abort(403, 'Supervisors cannot submit appraisals.');
        }

        $appraisal->update(['status' => 'submitted']);
        return redirect()->route('dashboard')
            ->with('success', 'Appraisal submitted successfully!');
    }

    /**
     * Approve appraisal
     */
    public function approve(Appraisal $appraisal)
    {
        $user = Auth::user();
        
        // Only supervisors can approve
        if ($user->user_type !== 'supervisor') {
            abort(403, 'Only supervisors can approve appraisals.');
        }
        
        // Check if appraisal is in review or submitted
        if (!in_array($appraisal->status, ['submitted', 'in_review'])) {
            return back()->with('error', 'Only submitted or in-review appraisals can be approved.');
        }
        
        // Check if supervisor can approve (including higher-level)
        if (!$this->canSupervisorApprove($user, $appraisal)) {
            abort(403, 'You are not authorized to approve this appraisal.');
        }
        
        // Calculate final score if not already calculated
        $finalScore = $this->calculateAppraisalScore($appraisal);
        $rating = $this->calculateRating($finalScore);
        
        $appraisal->update([
            'status' => 'approved',
            'approved_by' => $user->employee_number,
            'approved_at' => now(),
            'final_score' => $finalScore,
            'rating' => $rating,
        ]);
        
        return redirect()->route('dashboard')
            ->with('success', 'Appraisal approved successfully!');
    }

    /**
     * Add supervisor comment
     */
    public function addComment(Request $request, Appraisal $appraisal)
    {
        $user = Auth::user();
        
        // Only supervisors can add comments
        if ($user->user_type !== 'supervisor') {
            abort(403, 'Only supervisors can add comments.');
        }
        
        // Check if user is in management chain
        if (!$this->isInManagementChain($user, $appraisal->employee_number)) {
            abort(403, 'Unauthorized to comment on this appraisal.');
        }
        
        $validated = $request->validate([
            'supervisor_comments' => 'required|string|min:10',
        ]);
        
        // Append comment instead of overwriting
        $existingComments = $appraisal->supervisor_comments;
        $newComment = "[" . now()->format('Y-m-d H:i') . "] " . $user->name . ": " . $validated['supervisor_comments'];
        
        if ($existingComments) {
            $appraisal->supervisor_comments = $existingComments . "\n\n" . $newComment;
        } else {
            $appraisal->supervisor_comments = $newComment;
        }
        
        $appraisal->save();
        
        return back()->with('success', 'Comment added successfully!');
    }

    /**
     * Return appraisal for revision
     */
    public function returnForRevision(Request $request, Appraisal $appraisal)
    {
        $user = Auth::user();
        
        // Only supervisors can return appraisals
        if ($user->user_type !== 'supervisor') {
            abort(403, 'Only supervisors can return appraisals.');
        }
        
        // Check if user is in management chain
        if (!$this->isInManagementChain($user, $appraisal->employee_number)) {
            abort(403, 'Unauthorized to return this appraisal.');
        }
        
        // Check if appraisal is in review or submitted
        if (!in_array($appraisal->status, ['submitted', 'in_review'])) {
            return back()->with('error', 'Only submitted or in-review appraisals can be returned.');
        }
        
        $request->validate([
            'feedback' => 'required|string|min:10',
        ]);
        
        $appraisal->update([
            'status' => 'draft',
            'supervisor_comments' => $request->feedback,
            'returned_by' => $user->employee_number,
            'returned_at' => now(),
        ]);
        
        return back()->with('success', 'Appraisal returned for revision.');
    }

    /**
     * Reject appraisal
     */
    public function reject(Request $request, Appraisal $appraisal)
    {
        $user = Auth::user();
        
        // Only supervisors can reject
        if ($user->user_type !== 'supervisor') {
            abort(403, 'Only supervisors can reject appraisals.');
        }
        
        // Check if user is in management chain
        if (!$this->isInManagementChain($user, $appraisal->employee_number)) {
            abort(403, 'Unauthorized to reject this appraisal.');
        }
        
        // Validate that appraisal is in a rejectable state
        if (!in_array($appraisal->status, ['submitted', 'returned', 'in_review'])) {
            return redirect()->back()
                ->with('error', 'Appraisal cannot be rejected in its current status.');
        }
        
        $request->validate([
            'rejection_reason' => 'required|string|min:10',
            'allow_edit' => 'required|in:0,1',
        ]);
        
        // Update the appraisal
        $appraisal->update([
            'status' => $request->allow_edit == '1' ? 'rejected' : 'archived',
            'rejection_reason' => $request->rejection_reason,
            'rejected_at' => now(),
            'rejected_by' => $user->employee_number,
        ]);
        
        // If permanently rejected (archived), remove supervisor ratings
        if ($request->allow_edit == '0') {
            foreach ($appraisal->kpas as $kpa) {
                $kpa->update([
                    'supervisor_rating' => null,
                    'supervisor_comments' => null,
                ]);
            }
            
            // Also remove individual supervisor ratings from pivot table if it exists
            if (DB::table('employee_rating_supervisors')->exists()) {
                DB::table('employee_rating_supervisors')
                    ->where('employee_number', $appraisal->employee_number)
                    ->whereNotNull('kpa_id')
                    ->delete();
            }
        }
        
        return redirect()->back()
            ->with('success', 'Appraisal has been ' . ($request->allow_edit == '1' ? 'rejected. Employee can now edit it.' : 'permanently rejected and archived.'));
    }

    /**
     * Rate a specific KPA (for single supervisor system)
     */
    public function rateKpa(Request $request)
    {
        $user = Auth::user();
        
        // Only supervisors can rate KPAs
        if ($user->user_type !== 'supervisor') {
            return response()->json([
                'success' => false,
                'message' => 'Only supervisors can rate KPAs.'
            ], 403);
        }
        
        // First, validate the basic required fields
        $validated = $request->validate([
            'kpa_id' => 'required|exists:appraisal_kpas,id',
            'supervisor_comments' => 'required|string|min:5',
        ]);
        
        // Get the KPA
        $kpa = AppraisalKpa::findOrFail($validated['kpa_id']);
        
        // Get the appraisal
        $appraisal = $kpa->appraisal;
        
        // Check if user is in management chain
        if (!$this->isInManagementChain($user, $appraisal->employee_number)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to rate this KPA.'
            ], 403);
        }
        
        // Check if appraisal is submitted
        if (!in_array($appraisal->status, ['submitted', 'in_review'])) {
            return response()->json([
                'success' => false,
                'message' => 'Only submitted appraisals can be rated.'
            ], 400);
        }
        
        // Check if "agree with self-rating" is set and true
        $agreeWithSelfRating = $request->has('agree_with_self_rating') && 
                              ($request->agree_with_self_rating === '1' || 
                               $request->agree_with_self_rating === 'true' ||
                               $request->agree_with_self_rating === true);
        
        DB::beginTransaction();
        
        try {
            // Determine the final rating
            if ($agreeWithSelfRating) {
                // Use employee's self-rating
                $finalRating = $kpa->self_rating;
                $supervisorComments = $validated['supervisor_comments'];
                
                $kpa->update([
                    'supervisor_rating' => $finalRating,
                    'supervisor_comments' => $supervisorComments,
                ]);
            } else {
                // Use supervisor's rating - validate it's present and valid
                if (!$request->has('supervisor_rating') || !is_numeric($request->supervisor_rating)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Supervisor rating is required when not agreeing with self-rating.'
                    ], 400);
                }
                
                // Validate the rating value
                $rating = intval($request->supervisor_rating);
                if ($rating < 1 || $rating > 10) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Supervisor rating must be between 1 and 10.'
                    ], 400);
                }
                
                $finalRating = $rating;
                $supervisorComments = $validated['supervisor_comments'];
                
                $kpa->update([
                    'supervisor_rating' => $finalRating,
                    'supervisor_comments' => $supervisorComments,
                ]);
            }
            
            // Update overall appraisal scores
            $this->updateAppraisalScores($appraisal);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'KPA rated successfully!',
                'data' => [
                    'kpa_id' => $kpa->id,
                    'supervisor_rating' => $finalRating,
                    'supervisor_comments' => $supervisorComments,
                    'agree_with_self_rating' => $agreeWithSelfRating,
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error saving rating: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Rate KPA for multiple supervisors (new system)
     */
    public function rateKpaMultiple(Request $request)
    {
        $user = Auth::user();
        
        // Only supervisors can rate KPAs
        if ($user->user_type !== 'supervisor') {
            return response()->json([
                'success' => false,
                'message' => 'Only supervisors can rate KPAs.'
            ], 403);
        }
        
        // Validate the request
        $validated = $request->validate([
            'kpa_id' => 'required|exists:appraisal_kpas,id',
            'supervisor_rating' => 'required|integer|min:1|max:10',
            'supervisor_comments' => 'required|string|min:5',
        ]);
        
        // Get the KPA
        $kpa = AppraisalKpa::findOrFail($validated['kpa_id']);
        
        // Get the appraisal
        $appraisal = $kpa->appraisal;
        
        // Check if user is in management chain
        if (!$this->isInManagementChain($user, $appraisal->employee_number)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to rate this KPA.'
            ], 403);
        }
        
        // Check if appraisal is submitted
        if (!in_array($appraisal->status, ['submitted', 'in_review'])) {
            return response()->json([
                'success' => false,
                'message' => 'Only submitted appraisals can be rated.'
            ], 400);
        }
        
        // Get supervisor weight
        $supervisorWeight = 100;
        
        if (DB::table('employee_rating_supervisors')->exists()) {
            // Check pivot table for supervisor assignment
            $supervisorAssignment = DB::table('employee_rating_supervisors')
                ->where('supervisor_id', $user->employee_number)
                ->where('employee_number', $appraisal->employee_number)
                ->whereNull('kpa_id')
                ->first();
            
            if ($supervisorAssignment) {
                $supervisorWeight = $supervisorAssignment->rating_weight ?? 100;
            }
        }
        
        try {
            DB::beginTransaction();
            
            // Store individual supervisor rating in the pivot table
            DB::table('employee_rating_supervisors')->updateOrInsert(
                [
                    'supervisor_id' => $user->employee_number,
                    'employee_number' => $appraisal->employee_number,
                    'kpa_id' => $kpa->id,
                ],
                [
                    'rating' => $validated['supervisor_rating'],
                    'comments' => $validated['supervisor_comments'],
                    'rating_weight' => $supervisorWeight,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            
            // Recalculate the weighted average rating for this KPA
            $this->recalculateKpaWeightedRating($kpa);
            
            // Update overall appraisal scores
            $this->updateAppraisalScores($appraisal);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'KPA rated successfully!',
                'data' => [
                    'kpa_id' => $kpa->id,
                    'supervisor_rating' => $validated['supervisor_rating'],
                    'supervisor_comments' => $validated['supervisor_comments'],
                    'rating_weight' => $supervisorWeight,
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error saving rating: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get supervisor ratings for a KPA
     */
    public function getKpaRatings($kpaId)
    {
        $user = Auth::user();
        
        // Only supervisors or the employee can view ratings
        if ($user->user_type !== 'supervisor' && $user->user_type !== 'employee') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to view ratings.'
            ], 403);
        }
        
        $kpa = AppraisalKpa::findOrFail($kpaId);
        $appraisal = $kpa->appraisal;
        
        // Check authorization
        if ($user->user_type === 'employee' && $appraisal->employee_number !== $user->employee_number) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to view these ratings.'
            ], 403);
        }
        
        if ($user->user_type === 'supervisor') {
            // Check if user is in management chain
            if (!$this->isInManagementChain($user, $appraisal->employee_number)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view these ratings.'
                ], 403);
            }
        }
        
        $ratings = [];
        
        // Get all ratings for this KPA with supervisor details
        if (DB::table('employee_rating_supervisors')->exists()) {
            $ratings = DB::table('employee_rating_supervisors as ers')
                ->select('ers.*', 'u.name as supervisor_name', 'u.employee_number')
                ->leftJoin('users as u', 'ers.supervisor_id', '=', 'u.employee_number')
                ->where('ers.kpa_id', $kpaId)
                ->whereNotNull('ers.rating')
                ->orderBy('ers.created_at', 'desc')
                ->get();
        }
        
        return response()->json([
            'success' => true,
            'ratings' => $ratings,
            'kpa' => [
                'id' => $kpa->id,
                'kpa' => $kpa->kpa,
                'category' => $kpa->category,
                'self_rating' => $kpa->self_rating,
                'supervisor_rating' => $kpa->supervisor_rating,
                'supervisor_comments' => $kpa->supervisor_comments,
            ]
        ]);
    }
    
    /**
     * ==============================================
     * PRIVATE HELPER METHODS
     * ==============================================
     */
    
    /**
     * Check if user is in the management chain of an employee
     */
    private function isInManagementChain($user, $employeeNumber)
    {
        // Get the employee
        $employee = User::where('employee_number', $employeeNumber)->first();
        
        if (!$employee) {
            return false;
        }
        
        // Check if user is the direct manager
        if ($employee->manager_id === $user->employee_number) {
            return true;
        }
        
        // Check if user is assigned via pivot table
        if (DB::table('employee_rating_supervisors')->exists()) {
            $isAssigned = DB::table('employee_rating_supervisors')
                ->where('supervisor_id', $user->employee_number)
                ->where('employee_number', $employeeNumber)
                ->whereNull('kpa_id')
                ->exists();
            
            if ($isAssigned) {
                return true;
            }
        }
        
        // Get all subordinates recursively and check if employee is in that list
        $allSubordinates = $this->getAllSubordinates($user->employee_number);
        $subordinateNumbers = collect($allSubordinates)->pluck('employee_number')->toArray();
        
        return in_array($employeeNumber, $subordinateNumbers);
    }

    /**
     * Check if user is a higher-level supervisor (manager of the direct supervisor)
     */
    private function isHigherLevelSupervisor($user, $employeeNumber)
    {
        // Get the employee
        $employee = User::where('employee_number', $employeeNumber)->first();
        
        if (!$employee) {
            return false;
        }
        
        // Get the employee's direct supervisor
        $directSupervisorNumber = null;
        
        // First check manager_id
        if ($employee->manager_id) {
            $directSupervisorNumber = $employee->manager_id;
        }
        
        // If not found, check pivot table for primary supervisor
        if (!$directSupervisorNumber && DB::table('employee_rating_supervisors')->exists()) {
            $primarySupervisor = DB::table('employee_rating_supervisors')
                ->where('employee_number', $employeeNumber)
                ->where('is_primary', true)
                ->whereNull('kpa_id')
                ->first();
            
            if ($primarySupervisor) {
                $directSupervisorNumber = $primarySupervisor->supervisor_id;
            }
        }
        
        if (!$directSupervisorNumber) {
            return false;
        }
        
        // Check if the current user is the manager of the direct supervisor
        $directSupervisor = User::where('employee_number', $directSupervisorNumber)->first();
        
        if (!$directSupervisor) {
            return false;
        }
        
        // Check if user is the manager of the direct supervisor
        if ($directSupervisor->manager_id === $user->employee_number) {
            return true;
        }
        
        // Check recursively higher up the chain
        return $this->isInManagementChain($user, $directSupervisorNumber);
    }

    /**
     * Check if supervisor can approve the appraisal
     */
    private function canSupervisorApprove($supervisor, $appraisal)
    {
        // Only submitted or in_review appraisals can be approved
        if (!in_array($appraisal->status, ['submitted', 'in_review'])) {
            return false;
        }
        
        // Check if supervisor is in management chain (including higher levels)
        $isInManagementChain = $this->isInManagementChain($supervisor, $appraisal->employee_number);
        
        if (!$isInManagementChain) {
            return false;
        }
        
        // Check if supervisor is in team (either via manager_id OR pivot table)
        $isInTeam = false;
        $isPrimary = false;
        
        // 1. Check direct report via manager_id
        $employee = User::where('employee_number', $appraisal->employee_number)->first();
        if ($employee && $employee->manager_id === $supervisor->employee_number) {
            $isInTeam = true;
            $isPrimary = true; // Direct manager is always considered primary
        }
        
        // 2. Check assigned via pivot table (if not already found)
        if (!$isInTeam && DB::table('employee_rating_supervisors')->exists()) {
            $supervisorAssignment = DB::table('employee_rating_supervisors')
                ->where('supervisor_id', $supervisor->employee_number)
                ->where('employee_number', $appraisal->employee_number)
                ->whereNull('kpa_id')
                ->first();
            
            if ($supervisorAssignment) {
                $isInTeam = true;
                $isPrimary = $supervisorAssignment->is_primary ?? false;
            }
        }
        
        // Check if all KPAs have been rated by all supervisors
        $allKPAsRated = $this->checkAllKPAsRated($appraisal);
        
        // In multiple supervisor system, only primary supervisor can approve after all KPAs are rated
        if (DB::table('employee_rating_supervisors')->exists()) {
            $supervisorCount = DB::table('employee_rating_supervisors')
                ->where('employee_number', $appraisal->employee_number)
                ->whereNull('kpa_id')
                ->count();
            
            if ($supervisorCount > 1) {
                // For multiple supervisors, only primary can approve
                if ($isInTeam && !$isPrimary) {
                    return false;
                }
                // Higher-level supervisors can approve regardless of primary status
                if (!$isInTeam) {
                    // Higher-level supervisor - can approve if all KPAs are rated
                    return $allKPAsRated;
                }
                // Primary assigned supervisor - check if all KPAs are rated
                return $allKPAsRated;
            }
        }
        
        // Single supervisor system
        if ($isInTeam && $isPrimary) {
            // Primary/direct manager can approve even if not all KPAs are rated
            return true;
        }
        
        // Higher-level supervisor or non-primary - check if all KPAs are rated
        return $allKPAsRated;
    }

    /**
     * Check if all KPAs have been rated by all assigned supervisors
     */
    private function checkAllKPAsRated($appraisal)
    {
        if (!DB::table('employee_rating_supervisors')->exists()) {
            // Old system - check if all KPAs have supervisor_rating
            return $appraisal->kpas->every(function ($kpa) {
                return !is_null($kpa->supervisor_rating);
            });
        }
        
        // New system - check if all supervisors have rated all KPAs
        $assignedSupervisors = DB::table('employee_rating_supervisors')
            ->where('employee_number', $appraisal->employee_number)
            ->whereNull('kpa_id')
            ->get();
        
        $totalKPAs = $appraisal->kpas->count();
        
        // If no supervisors assigned, return false
        if ($assignedSupervisors->isEmpty()) {
            return false;
        }
        
        // Check each supervisor has rated all KPAs
        foreach ($assignedSupervisors as $supervisor) {
            $supervisorRatingsCount = DB::table('employee_rating_supervisors')
                ->where('employee_number', $appraisal->employee_number)
                ->where('supervisor_id', $supervisor->supervisor_id)
                ->whereIn('kpa_id', $appraisal->kpas->pluck('id')->toArray())
                ->whereNotNull('rating')
                ->count();
            
            if ($supervisorRatingsCount < $totalKPAs) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Recalculate weighted rating for a KPA from all supervisor ratings
     */
    private function recalculateKpaWeightedRating(AppraisalKpa $kpa)
    {
        // Get all supervisor ratings for this KPA
        $ratings = DB::table('employee_rating_supervisors')
            ->where('kpa_id', $kpa->id)
            ->whereNotNull('rating')
            ->get();
        
        if ($ratings->isEmpty()) {
            // No supervisor ratings, clear the supervisor_rating field
            $kpa->update(['supervisor_rating' => null]);
            return;
        }
        
        $totalWeightedRating = 0;
        $totalWeight = 0;
        
        foreach ($ratings as $rating) {
            // Use supervisor's rating weight from the relationship
            $supervisorWeight = $rating->rating_weight ?? 100;
            
            $totalWeightedRating += $rating->rating * $supervisorWeight;
            $totalWeight += $supervisorWeight;
        }
        
        if ($totalWeight > 0) {
            $weightedAverage = $totalWeightedRating / $totalWeight;
            $kpa->update(['supervisor_rating' => round($weightedAverage, 2)]);
        }
    }
    
    /**
     * Update overall appraisal scores after rating a KPA
     */
    private function updateAppraisalScores(Appraisal $appraisal)
    {
        // Reload KPAs with fresh data
        $appraisal->load('kpas');
        
        // Calculate supervisor score
        $supervisorScore = 0;
        $totalWeight = 0;
        
        foreach ($appraisal->kpas as $kpa) {
            // Use supervisor rating if available, otherwise use self-rating
            $rating = $kpa->supervisor_rating ?? $kpa->self_rating ?? 0;
            $kpi = $kpa->kpi ?? 4;
            if ($kpi == 0) $kpi = 4;
            
            $percentage = ($rating / $kpi) * 100;
            $supervisorScore += ($percentage * $kpa->weight) / 100;
            $totalWeight += $kpa->weight;
        }
        
        // Calculate final score
        $finalScore = $supervisorScore;
        
        // Check if all KPAs are rated
        $allKPAsRated = $this->checkAllKPAsRated($appraisal);
        
        // Update appraisal
        $updateData = [
            'final_score' => round($finalScore, 2),
            'overall_score' => round($finalScore, 2),
        ];
        
        // If all KPAs are rated, change status to in_review (ready for approval)
        if ($allKPAsRated && $appraisal->status === 'submitted') {
            $updateData['status'] = 'in_review';
        }
        
        $appraisal->update($updateData);
    }
    
    /**
     * Calculate appraisal score from KPAs
     */
    private function calculateAppraisalScore(Appraisal $appraisal)
    {
        if (!$appraisal->relationLoaded('kpas')) {
            $appraisal->load('kpas');
        }
        
        if ($appraisal->kpas->isEmpty()) {
            return $appraisal->final_score ?? $appraisal->self_score ?? 0;
        }
        
        $totalScore = 0;
        $totalWeight = 0;
        
        foreach ($appraisal->kpas as $kpa) {
            // Use supervisor rating if available, otherwise use self-rating
            $rating = $kpa->supervisor_rating ?? $kpa->self_rating ?? 0;
            $kpi = $kpa->kpi ?? 4;
            if ($kpi == 0) $kpi = 4;
            
            $percentage = ($rating / $kpi) * 100;
            $totalScore += ($percentage * $kpa->weight) / 100;
            $totalWeight += $kpa->weight;
        }
        
        if ($totalWeight == 0) {
            return 0;
        }
        
        return min(100, max(0, round($totalScore, 2)));
    }
    
    /**
     * Calculate rating based on score
     */
    private function calculateRating($score)
    {
        if ($score >= 90) return 'Outstanding';
        if ($score >= 80) return 'Excellent';
        if ($score >= 70) return 'Good';
        if ($score >= 60) return 'Average';
        return 'Poor';
    }

    /**
     * Get all subordinates recursively (including indirect reports)
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
     * Check if user is supervisor
     */
    private function isSupervisor($user)
    {
        if (isset($user->user_type) && $user->user_type === 'supervisor') {
            return true;
        }
        
        if (User::where('manager_id', $user->employee_number)->where('is_onboarded', true)->exists()) {
            return true;
        }
        
        return false;
    }

    /**
     * Helper method to get current quarter information
     */
    private function getCurrentQuarter()
    {
        $now = now();
        $month = $now->month;
        $year = $now->year;
        
        if ($month >= 1 && $month <= 3) {
            return [
                'quarter' => 'Q1',
                'quarter_name' => 'Quarter 1',
                'quarter_months' => 'January - March',
                'due_date' => date('M d', strtotime("April 20, $year")),
                'year' => $year,
            ];
        } elseif ($month >= 4 && $month <= 6) {
            return [
                'quarter' => 'Q2',
                'quarter_name' => 'Quarter 2',
                'quarter_months' => 'April - June',
                'due_date' => date('M d', strtotime("July 20, $year")),
                'year' => $year,
            ];
        } elseif ($month >= 7 && $month <= 9) {
            return [
                'quarter' => 'Q3',
                'quarter_name' => 'Quarter 3',
                'quarter_months' => 'July - September',
                'due_date' => date('M d', strtotime("October 20, $year")),
                'year' => $year,
            ];
        } else {
            return [
                'quarter' => 'Q4',
                'quarter_name' => 'Quarter 4',
                'quarter_months' => 'October - December',
                'due_date' => date('M d', strtotime("January 20, " . ($year + 1))),
                'year' => $year,
            ];
        }
    }

    /**
     * Get quarter information with detailed dates
     */
    private function getQuarterInfo()
    {
        $now = now();
        $month = $now->month;
        $year = $now->year;
        
        $quarterInfo = [
            'current_date' => $now->format('Y-m-d'),
            'year' => $year,
            'quarter' => '',
            'quarter_name' => '',
            'quarter_months' => '',
            'due_date' => '',
            'due_date_formatted' => '',
            'due_date_timestamp' => '',
            'is_past' => false,
            'is_current' => false,
            'is_future' => false
        ];
        
        // Determine current quarter based on month
        if ($month >= 1 && $month <= 3) {
            $quarterInfo['quarter'] = 'Q1';
            $quarterInfo['quarter_name'] = 'Quarter 1';
            $quarterInfo['quarter_months'] = 'January - March';
            $quarterInfo['due_date'] = date('M d', strtotime("April 20, $year"));
            $quarterInfo['due_date_formatted'] = "April 20";
            $quarterInfo['due_date_timestamp'] = strtotime("April 20, $year");
        } elseif ($month >= 4 && $month <= 6) {
            $quarterInfo['quarter'] = 'Q2';
            $quarterInfo['quarter_name'] = 'Quarter 2';
            $quarterInfo['quarter_months'] = 'April - June';
            $quarterInfo['due_date'] = date('M d', strtotime("July 20, $year"));
            $quarterInfo['due_date_formatted'] = "July 20";
            $quarterInfo['due_date_timestamp'] = strtotime("July 20, $year");
        } elseif ($month >= 7 && $month <= 9) {
            $quarterInfo['quarter'] = 'Q3';
            $quarterInfo['quarter_name'] = 'Quarter 3';
            $quarterInfo['quarter_months'] = 'July - September';
            $quarterInfo['due_date'] = date('M d', strtotime("October 20, $year"));
            $quarterInfo['due_date_formatted'] = "October 20";
            $quarterInfo['due_date_timestamp'] = strtotime("October 20, $year");
        } else {
            $quarterInfo['quarter'] = 'Q4';
            $quarterInfo['quarter_name'] = 'Quarter 4';
            $quarterInfo['quarter_months'] = 'October - December';
            $quarterInfo['due_date'] = date('M d', strtotime("January 20, " . ($year + 1)));
            $quarterInfo['due_date_formatted'] = "January 20";
            $quarterInfo['due_date_timestamp'] = strtotime("January 20, " . ($year + 1));
        }
        
        // Determine if quarter is past, current, or future
        $nowTimestamp = time();
        $dueDateTimestamp = $quarterInfo['due_date_timestamp'];
        
        if ($nowTimestamp > $dueDateTimestamp) {
            $quarterInfo['is_past'] = true;
        } elseif ($quarterInfo['quarter'] === 'Q1' && $month >= 1 && $month <= 4) {
            $quarterInfo['is_current'] = true;
        } elseif ($quarterInfo['quarter'] === 'Q2' && $month >= 4 && $month <= 7) {
            $quarterInfo['is_current'] = true;
        } elseif ($quarterInfo['quarter'] === 'Q3' && $month >= 7 && $month <= 10) {
            $quarterInfo['is_current'] = true;
        } elseif ($quarterInfo['quarter'] === 'Q4' && ($month >= 10 || $month <= 1)) {
            $quarterInfo['is_current'] = true;
        } else {
            $quarterInfo['is_future'] = true;
        }
        
        return (object) $quarterInfo;
    }

    /**
     * Return appraisal (legacy method for backward compatibility)
     */
    public function return(Request $request, Appraisal $appraisal)
    {
        return $this->returnForRevision($request, $appraisal);
    }

    /**
     * Display quarterly summary report
     */
    public function quarterlySummary(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $quarter = $request->input('quarter', 'all');
        $employeeType = $request->input('employee_type', 'all');
        $workstation = $request->input('workstation', null);
        $department = $request->input('department', 'all');
        $position = $request->input('position', 'all');

        // System statistics
        $systemStats = [
            'total_users' => User::count(),
            'total_appraisals' => Appraisal::count(),
            'users_with_ratings' => User::whereHas('appraisals', function($q) {
                $q->whereNotNull('final_score');
            })->count(),
            'users_without_ratings' => User::whereDoesntHave('appraisals', function($q) {
                $q->whereNotNull('final_score');
            })->count(),
        ];

        // Get summary data
        $summaryData = $this->getQuarterlySummaryData($year, $quarter, $employeeType, $workstation, $department, $position);

        // Get filter options
        $hqDepartments = ['HR' => 'Human Resources', 'Finance' => 'Finance', 'IT' => 'Information Technology', 'Operations' => 'Operations'];
        $departments = array_keys($hqDepartments);
        $positions = ['Manager', 'Supervisor', 'Staff', 'Officer', 'Assistant', 'Technician', 'Clerk', 'Toll Collector'];

        return view('appraisals.quarterly-summary', compact(
            'year', 'quarter', 'employeeType', 'workstation', 'department', 'position',
            'systemStats', 'summaryData', 'hqDepartments', 'departments', 'positions'
        ));
    }

    /**
     * Get quarterly summary data based on filters
     */
    private function getQuarterlySummaryData($year, $quarter, $employeeType, $workstation, $department, $position)
    {
        // This would normally query the database
        // For now, returning sample data structure
        return [
            'total_users' => 50,
            'workstation_stats' => [
                'hq' => ['count' => 20, 'avg_score' => 80],
                'toll_plaza' => ['count' => 30, 'avg_score' => 75],
            ],
            'toll_plaza_stats' => [
                ['name' => 'Kafulafuta', 'count' => 10, 'avg_score' => 70],
                ['name' => 'Katuba', 'count' => 10, 'avg_score' => 75],
                ['name' => 'Mokola', 'count' => 10, 'avg_score' => 80],
            ],
        ];
    }
}