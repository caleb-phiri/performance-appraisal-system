<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appraisal;
use App\Models\AppraisalKpa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        
        // For supervisors: show ALL team members' appraisals (both from pivot table AND direct reports)
        if ($user->user_type === 'supervisor') {
            // Get ALL team members (both from pivot table AND manager_id)
            $teamMembers = collect();
            
            // 1. Get employees from rating_supervisors pivot table (assigned employees)
            if (method_exists($user, 'rateableEmployees')) {
                $assignedEmployees = $user->rateableEmployees()
                    ->when(method_exists($user->rateableEmployees()->getModel(), 'scopeActiveCompany'), function ($query) {
                        $query->activeCompany();
                    })
                    ->get();
            } else {
                $assignedEmployees = collect();
            }
            
            // 2. Get employees who have this supervisor as their manager (direct reports)
            $directReports = User::where('manager_id', $user->employee_number)
                ->where('user_type', 'employee')
                ->when(method_exists(User::class, 'scopeActiveCompany'), function ($query) {
                    $query->activeCompany();
                })
                ->get();
            
            // 3. Merge both collections and remove duplicates
            $teamMembers = $assignedEmployees->merge($directReports)->unique('employee_number');
            
            $teamEmployeeNumbers = $teamMembers->pluck('employee_number')->toArray();
            
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
                    'team_members' => $teamMembers->count(),
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
            
            return view('dashboard', compact('appraisals', 'stats', 'teamMembers', 'user'));
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
     * Show list of appraisals with supervisor filtering
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Initialize variables
            $employee = $user;
            $teamMembers = collect();
            $employeeNumber = null;
            
            // For supervisors: get ALL team members and handle employee filter
            if ($user->user_type === 'supervisor') {
                // Get ALL team members (both assigned via pivot AND direct reports)
                $assignedEmployees = collect();
                $directReports = collect();
                
                // 1. Get assigned employees from pivot table
                if (method_exists($user, 'rateableEmployees')) {
                    $assignedEmployees = $user->rateableEmployees()
                        ->when(method_exists($user->rateableEmployees()->getModel(), 'scopeActiveCompany'), function ($query) {
                            $query->activeCompany();
                        })
                        ->get();
                }
                
                // 2. Get direct reports via manager_id
                $directReports = User::where('manager_id', $user->employee_number)
                    ->where('user_type', 'employee')
                    ->when(method_exists(User::class, 'scopeActiveCompany'), function ($query) {
                        $query->activeCompany();
                    })
                    ->get();
                
                // 3. Merge and deduplicate
                $teamMembers = $assignedEmployees->merge($directReports)->unique('employee_number');
                
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
            } elseif ($user->user_type === 'supervisor' && !$request->has('employee_number')) {
                // For supervisor viewing all team members
                $teamEmployeeNumbers = $teamMembers->pluck('employee_number')->toArray();
                if (!empty($teamEmployeeNumbers)) {
                    $query->whereIn('employee_number', $teamEmployeeNumbers);
                } else {
                    $query->where('employee_number', '0'); // No team members
                }
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
            } elseif ($user->user_type === 'supervisor' && !$request->has('employee_number')) {
                $teamEmployeeNumbers = $teamMembers->pluck('employee_number')->toArray();
                if (!empty($teamEmployeeNumbers)) {
                    $statsQuery->whereIn('employee_number', $teamEmployeeNumbers);
                }
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
            
            return view('appraisals.index', [
                'appraisals' => $appraisals,
                'employee' => $employee,
                'teamMembers' => $teamMembers,
                'submittedCount' => $submittedCount,
                'draftCount' => $draftCount,
                'averageScore' => $averageScore
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Appraisal index error: ' . $e->getMessage());
            
            return view('appraisals.index', [
                'appraisals' => collect([]),
                'employee' => Auth::user(),
                'teamMembers' => collect([]),
                'submittedCount' => 0,
                'draftCount' => 0,
                'averageScore' => 0
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
        
        // Supervisors can view appraisals of their team members (both direct reports AND assigned employees)
        if ($user->user_type === 'supervisor') {
            // Check if employee is in supervisor's team (either via manager_id OR pivot table)
            $isInTeam = false;
            
            // 1. Check direct report via manager_id
            $employee = User::where('employee_number', $appraisal->employee_number)->first();
            if ($employee && $employee->manager_id === $user->employee_number) {
                $isInTeam = true;
            }
            
            // 2. Check assigned via pivot table (if not already found)
            if (!$isInTeam && DB::table('employee_rating_supervisors')->exists()) {
                $isInTeam = DB::table('employee_rating_supervisors')
                    ->where('supervisor_id', $user->employee_number)
                    ->where('employee_number', $appraisal->employee_number)
                    ->whereNull('kpa_id')
                    ->exists();
            }
            
            if (!$isInTeam) {
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
        
        // Check if current supervisor can approve - FIXED LOGIC
        $canApprove = false;
        $isAssignedSupervisor = false;
        $isPrimarySupervisor = false;
        
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
            
            // Check if can approve
            if ($isAssignedSupervisor) {
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
            'isPrimarySupervisor'
        ));
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
        
        if (!$isInTeam) {
            return false;
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
                if (!$isPrimary) {
                    return false;
                }
                // Check if all KPAs are rated by all supervisors
                return $allKPAsRated;
            }
        }
        
        // Single supervisor system - can approve if all KPAs are rated
        // For single supervisor, don't require all KPAs to be rated if they're the primary/direct manager
        if ($isPrimary) {
            // Primary/direct manager can approve even if not all KPAs are rated
            return true;
        }
        
        // For non-primary supervisors in single supervisor system, check if all KPAs are rated
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
     * Edit appraisal
     */
    public function edit(Appraisal $appraisal)
    {
        $user = Auth::user();
        
        // Only allow editing if draft and owner
        if ($user->user_type === 'employee') {
            if ($appraisal->employee_number !== $user->employee_number || $appraisal->status !== 'draft') {
                abort(403, 'Unauthorized access to edit this appraisal.');
            }
        } else if ($user->user_type === 'supervisor') {
            abort(403, 'Supervisors cannot edit appraisals.');
        }

        $appraisal->load('kpas');
        return view('appraisals.edit', compact('appraisal'));
    }

    /**
     * Update appraisal
     */
    public function update(Request $request, Appraisal $appraisal)
    {
        $user = Auth::user();

        // Authorization
        if (
            $user->user_type !== 'employee' ||
            $appraisal->employee_number !== $user->employee_number ||
            $appraisal->status !== 'draft'
        ) {
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
        ]);

        // Validate total weight = 100 when submitting
        $totalWeight = collect($validated['kpas'])->sum('weight');
        if ($validated['status'] === 'submitted' && $totalWeight !== 100) {
            return back()
                ->withErrors(['total_weight' => 'Total weight must equal 100% before submitting.'])
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Update KPAs (DO NOT delete them)
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

            // Update appraisal
            $appraisal->update([
                'status' => $validated['status'],
                'development_needs' => $validated['development_needs'],
                'employee_comments' => $validated['employee_comments'],
                'total_weight' => $totalWeight,
                'self_score' => $selfScore,
                'overall_score' => $selfScore,
            ]);

            DB::commit();

            return $validated['status'] === 'submitted'
                ? redirect()->route('dashboard')->with('success', 'Appraisal submitted successfully!')
                : redirect()->route('appraisals.show', $appraisal)->with('success', 'Appraisal updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Delete appraisal
     */
    public function destroy(Appraisal $appraisal)
    {
        $user = Auth::user();
        
        // Only allow deletion if draft and owner
        if ($user->user_type === 'employee') {
            if ($appraisal->employee_number !== $user->employee_number || $appraisal->status !== 'draft') {
                abort(403, 'Unauthorized access to delete this appraisal.');
            }
        } else if ($user->user_type === 'supervisor') {
            abort(403, 'Supervisors cannot delete appraisals.');
        }

        $appraisal->delete();
        return redirect()->route('dashboard')
            ->with('success', 'Appraisal deleted successfully!');
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
     * Add supervisor comment and rating
     */
    public function addComment(Request $request, Appraisal $appraisal)
    {
        $user = Auth::user();
        
        // Only supervisors can add comments
        if ($user->user_type !== 'supervisor') {
            abort(403, 'Only supervisors can add comments.');
        }
        
        // Check if supervisor is in team (either via manager_id OR pivot table)
        $isInTeam = false;
        
        // 1. Check direct report via manager_id
        $employee = User::where('employee_number', $appraisal->employee_number)->first();
        if ($employee && $employee->manager_id === $user->employee_number) {
            $isInTeam = true;
        }
        
        // 2. Check assigned via pivot table (if not already found)
        if (!$isInTeam && DB::table('employee_rating_supervisors')->exists()) {
            $isInTeam = DB::table('employee_rating_supervisors')
                ->where('supervisor_id', $user->employee_number)
                ->where('employee_number', $appraisal->employee_number)
                ->whereNull('kpa_id')
                ->exists();
        }
        
        if (!$isInTeam) {
            abort(403, 'Unauthorized to comment on this appraisal.');
        }
        
        $validated = $request->validate([
            'supervisor_comments' => 'required|string|min:10',
        ]);
        
        $appraisal->update([
            'supervisor_comments' => $validated['supervisor_comments'],
            'approved_by' => $user->employee_number,
            'approved_at' => now(),
        ]);
        
        return back()->with('success', 'Comment added successfully!');
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
        
        // Check if supervisor can approve
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
     * Return appraisal for revision
     */
    public function returnForRevision(Request $request, Appraisal $appraisal)
    {
        $user = Auth::user();
        
        // Only supervisors can return appraisals
        if ($user->user_type !== 'supervisor') {
            abort(403, 'Only supervisors can return appraisals.');
        }
        
        // Check if supervisor is in team (either via manager_id OR pivot table)
        $isInTeam = false;
        $isPrimary = true; // Default to true for fallback system
        
        // 1. Check direct report via manager_id
        $employee = User::where('employee_number', $appraisal->employee_number)->first();
        if ($employee && $employee->manager_id === $user->employee_number) {
            $isInTeam = true;
        }
        
        // 2. Check assigned via pivot table (if not already found)
        if (!$isInTeam && DB::table('employee_rating_supervisors')->exists()) {
            $supervisorAssignment = DB::table('employee_rating_supervisors')
                ->where('supervisor_id', $user->employee_number)
                ->where('employee_number', $appraisal->employee_number)
                ->whereNull('kpa_id')
                ->first();
            
            if ($supervisorAssignment) {
                $isInTeam = true;
                $isPrimary = $supervisorAssignment->is_primary ?? false;
            }
        }
        
        if (!$isInTeam) {
            abort(403, 'Unauthorized to return this appraisal.');
        }
        
        // Check if supervisor is primary (only primary can return for revision in multiple supervisor system)
        if (DB::table('employee_rating_supervisors')->exists() && !$isPrimary && $employee && $employee->manager_id !== $user->employee_number) {
            abort(403, 'Only primary supervisors can return appraisals for revision.');
        }
        
        $validated = $request->validate([
            'feedback' => 'required|string|min:10',
        ]);
        
        $appraisal->update([
            'status' => 'draft',
            'supervisor_comments' => $validated['feedback'],
        ]);
        
        return back()->with('success', 'Appraisal returned for revision.');
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
        
        // Check if supervisor is in team (either via manager_id OR pivot table)
        $isInTeam = false;
        
        // 1. Check direct report via manager_id
        $employee = User::where('employee_number', $appraisal->employee_number)->first();
        if ($employee && $employee->manager_id === $user->employee_number) {
            $isInTeam = true;
        }
        
        // 2. Check assigned via pivot table (if not already found)
        if (!$isInTeam && DB::table('employee_rating_supervisors')->exists()) {
            $isInTeam = DB::table('employee_rating_supervisors')
                ->where('supervisor_id', $user->employee_number)
                ->where('employee_number', $appraisal->employee_number)
                ->whereNull('kpa_id')
                ->exists();
        }
        
        if (!$isInTeam) {
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
        
        // Determine the final rating
        if ($agreeWithSelfRating) {
            // Use employee's self-rating
            $finalRating = $kpa->self_rating;
            $supervisorComments = $validated['supervisor_comments'];
            
            // Direct update to KPA
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
            
            // Direct update to KPA
            $kpa->update([
                'supervisor_rating' => $finalRating,
                'supervisor_comments' => $supervisorComments,
            ]);
        }
        
        // Update overall appraisal scores
        $this->updateAppraisalScores($appraisal);
        
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
        
        // Check if supervisor is in team (either via manager_id OR pivot table)
        $isInTeam = false;
        $supervisorWeight = 100;
        
        if (DB::table('employee_rating_supervisors')->exists()) {
            // Check pivot table first
            $supervisorAssignment = DB::table('employee_rating_supervisors')
                ->where('supervisor_id', $user->employee_number)
                ->where('employee_number', $appraisal->employee_number)
                ->whereNull('kpa_id')
                ->first();
            
            if ($supervisorAssignment) {
                $isInTeam = true;
                $supervisorWeight = $supervisorAssignment->rating_weight ?? 100;
            }
        }
        
        // If not found in pivot table, check if direct manager
        if (!$isInTeam) {
            $employee = User::where('employee_number', $appraisal->employee_number)->first();
            if ($employee && $employee->manager_id === $user->employee_number) {
                $isInTeam = true;
            }
        }
        
        if (!$isInTeam) {
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
     * Reject appraisal
     */
    public function reject(Request $request, Appraisal $appraisal)
    {
        // Validate that only supervisors can reject
        if (auth()->user()->user_type !== 'supervisor') {
            abort(403, 'Unauthorized action.');
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
            'rejected_by' => auth()->user()->name,
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
     * Return appraisal
     */
    public function return(Request $request, Appraisal $appraisal)
    {
        if (auth()->user()->user_type !== 'supervisor') {
            abort(403, 'Unauthorized action.');
        }
        
        if (!in_array($appraisal->status, ['submitted', 'in_review'])) {
            return redirect()->back()
                ->with('error', 'Appraisal cannot be returned in its current status.');
        }
        
        $request->validate([
            'return_reason' => 'required|string|min:10',
        ]);
        
        $appraisal->update([
            'status' => 'returned',
            'return_reason' => $request->return_reason,
            'returned_at' => now(),
            'returned_by' => auth()->user()->name,
        ]);
        
        return redirect()->back()
            ->with('success', 'Appraisal has been returned for revision.');
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
            $isInTeam = false;
            
            // 1. Check direct report via manager_id
            $employee = User::where('employee_number', $appraisal->employee_number)->first();
            if ($employee && $employee->manager_id === $user->employee_number) {
                $isInTeam = true;
            }
            
            // 2. Check assigned via pivot table (if not already found)
            if (!$isInTeam && DB::table('employee_rating_supervisors')->exists()) {
                $isInTeam = DB::table('employee_rating_supervisors')
                    ->where('supervisor_id', $user->employee_number)
                    ->where('employee_number', $appraisal->employee_number)
                    ->whereNull('kpa_id')
                    ->exists();
            }
            
            if (!$isInTeam) {
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
}