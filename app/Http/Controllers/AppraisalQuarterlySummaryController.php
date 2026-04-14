<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appraisal;
use App\Models\KPA;
use App\Services\PerformanceDistributionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class AppraisalQuarterlySummaryController extends Controller
{
    protected $distributionService;

    public function __construct()
    {
        $this->distributionService = new PerformanceDistributionService();
    }
    
    /**
     * Display quarterly summary for ALL employees and supervisors
     * No team restrictions - shows everyone in the system
     */
  /**
 * Display quarterly summary for ALL employees and supervisors
 */
public function index(Request $request)
{
    $user = auth()->user();
    
    // Check if user has permission (admin or supervisor)
    if ($user->user_type !== 'supervisor' && !$user->is_admin) {
        abort(403, 'You do not have permission to access this page.');
    }

    $year = $request->get('year', date('Y'));
    $quarter = $request->get('quarter', 'all');
    $department = $request->get('department');
    $position = $request->get('position');
    $employeeType = $request->get('employee_type', 'all');
    $workstation = $request->get('workstation');
    $location = $request->get('location');
    $tollPlaza = $request->get('toll_plaza');
    $hqDepartment = $request->get('hq_department');

    // Get ALL users in the system for the selected year
    $employees = User::where('is_onboarded', true)
        ->with(['appraisals' => function($query) use ($year) {
            $query->whereYear('created_at', $year)
                ->orWhereYear('start_date', $year)
                ->orWhereYear('updated_at', $year)
                ->with('kpas');
        }]);
    
    // Filter by user type if specified
    if ($employeeType !== 'all') {
        $employees = $employees->where('user_type', $employeeType);
    }

    // Apply department filter
    if ($department && $department !== '') {
        $employees = $employees->where('department', $department);
    }
    
    // Apply position filter
    if ($position && $position !== '') {
        $employees = $employees->where('job_title', $position);
    }
    
    // Apply workstation filter (combined approach)
    if ($workstation && $workstation !== '') {
        if ($workstation === 'HQ') {
            $employees = $employees->where('workstation_type', 'hq');
        } else {
            $employees = $employees->where('workstation_type', 'toll_plaza')
                ->where('toll_plaza', $this->getTollPlazaCode($workstation));
        }
    }
    // Keep backward compatibility with old filters
    elseif ($location && $location !== '') {
        if ($location === 'hq') {
            $employees = $employees->where('workstation_type', 'hq');
            
            if ($hqDepartment && $hqDepartment !== '') {
                $employees = $employees->where('hq_department', $hqDepartment);
            }
        } elseif ($location === 'toll_plaza') {
            $employees = $employees->where('workstation_type', 'toll_plaza');
            
            if ($tollPlaza && $tollPlaza !== '') {
                $employees = $employees->where('toll_plaza', $tollPlaza);
            }
        }
    }

    $employees = $employees->get();
    
    // Determine which quarters to display
    $quarters = $quarter !== 'all' ? [$quarter] : ['Q1', 'Q2', 'Q3', 'Q4'];
    
    // Calculate summary statistics
    $summaryData = $this->calculateSummaryData($employees, $year, $quarters);
    
    // Calculate distribution by workstation
    $distributionByWorkstation = $this->getDistributionByWorkstation($employees, $year);
    
    // Get all team sizes
    $allTeamSizes = $this->getAllTeamSizes();
    
    // Get outstanding employees for HQ (for the rating adjustment modal)
    $outstandingEmployees = $this->getOutstandingEmployees($employees, $year, 'hq');

    // Get all departments and positions for filters
    $departments = User::whereNotNull('department')
        ->distinct('department')
        ->pluck('department')
        ->filter()
        ->values();
        
    $positions = User::whereNotNull('job_title')
        ->distinct('job_title')
        ->pluck('job_title')
        ->filter()
        ->values();
        
    // Combined workstations for filter dropdown
    $workstations = [
        'all' => 'All Workstations',
        'HQ' => 'Headquarters (HQ)'
    ];
    
    // Add toll plazas with their full names
    $tollPlazaNames = [
        'TP-001' => 'Kafulafuta Toll Plaza',
        'TP-002' => 'Abram Zayoni Mokola Toll Plaza',
        'TP-003' => 'Katuba Toll Plaza',
        'TP-004' => 'Manyumbi Toll Plaza',
        'TP-005' => 'Konkola Toll Plaza'
    ];
    
    foreach ($tollPlazaNames as $code => $name) {
        $workstations[$name] = $name;
    }
    
    // Get HQ departments for filter
    $hqDepartments = User::whereNotNull('hq_department')
        ->where('hq_department', '!=', '')
        ->distinct('hq_department')
        ->pluck('hq_department')
        ->filter()
        ->values()
        ->mapWithKeys(function($item) {
            return [$item => ucwords(str_replace('_', ' ', $item))];
        })
        ->toArray();
        
    // Get top performers from ALL users
    $topPerformers = $this->getTopPerformers($employees, $year);

    // Get system-wide statistics
    $systemStats = [
        'total_users' => $employees->count(),
        'total_employees' => $employees->where('user_type', 'employee')->count(),
        'total_supervisors' => $employees->where('user_type', 'supervisor')->count(),
        'total_appraisals' => Appraisal::whereYear('created_at', $year)->count(),
        'submitted_appraisals' => Appraisal::whereYear('created_at', $year)->where('status', 'submitted')->count(),
        'approved_appraisals' => Appraisal::whereYear('created_at', $year)->where('status', 'approved')->count(),
        'draft_appraisals' => Appraisal::whereYear('created_at', $year)->where('status', 'draft')->count(),
        'users_with_ratings' => $employees->filter(function($employee) use ($year) {
            return $employee->appraisals->isNotEmpty();
        })->count(),
        'users_without_ratings' => $employees->filter(function($employee) use ($year) {
            return $employee->appraisals->isEmpty();
        })->count(),
    ];

    // Return the view with ALL data
    return view('appraisals.quarterly-summary', compact(
        'employees', 
        'summaryData', 
        'departments', 
        'positions',
        'workstations',
        'hqDepartments',
        'topPerformers',
        'year',
        'quarter',
        'employeeType',
        'workstation',
        'systemStats',
        'distributionByWorkstation',
        'allTeamSizes',
        'outstandingEmployees'  // Make sure this is included
    ));
}
    
   /**
 * Get distribution statistics by workstation
 */
private function getDistributionByWorkstation($employees, $year)
{
    $distributionByWorkstation = [];
    
    // Group employees by workstation
    $workstationGroups = [];
    
    foreach ($employees as $employee) {
        $workstationKey = $this->getWorkstationDisplay($employee);
        
        if (!isset($workstationGroups[$workstationKey])) {
            $workstationGroups[$workstationKey] = [];
        }
        
        // Calculate average score for this employee
        $totalScore = 0;
        $quarterCount = 0;
        
        foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $quarter) {
            $managerScore = $this->getQuarterlyScore($employee, $quarter, $year, 'manager');
            if ($managerScore !== null) {
                $totalScore += $managerScore;
                $quarterCount++;
            }
        }
        
        if ($quarterCount > 0) {
            $avgScore = $totalScore / $quarterCount;
            $rating = $this->distributionService->getRatingFromScore($avgScore);
            
            $workstationGroups[$workstationKey][] = [
                'employee' => $employee,
                'score' => $avgScore,
                'rating' => $rating
            ];
        }
    }
    
    // Calculate distribution for each workstation
    foreach ($workstationGroups as $workstationName => $members) {
        $teamSize = count($members);
        $ratingsCount = [
            5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0
        ];
        
        foreach ($members as $member) {
            $ratingsCount[$member['rating']]++;
        }
        
        $distributionByWorkstation[$workstationName] = [
            'team_size' => $teamSize,
            'ratings_count' => $ratingsCount,
            'compliance' => $this->distributionService->checkDistributionCompliance($ratingsCount, $teamSize),
            'limits' => $this->distributionService->calculateRatingLimits($teamSize),
            'recommendations' => $this->distributionService->getRecommendedAdjustments($ratingsCount, $teamSize)
        ];
    }
    
    // Ensure all workstations are included even if they have no data
    $allWorkstations = [
        'HQ',
        'Kafulafuta Toll Plaza',
        'Katuba Toll Plaza',
        'Manyumbi Toll Plaza',
        'Konkola Toll Plaza',
        'Abram Zayoni Mokola Toll Plaza'
    ];
    
    foreach ($allWorkstations as $workstation) {
        if (!isset($distributionByWorkstation[$workstation])) {
            $distributionByWorkstation[$workstation] = [
                'team_size' => 0,
                'ratings_count' => [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0],
                'compliance' => $this->distributionService->checkDistributionCompliance([5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0], 0),
                'limits' => $this->distributionService->calculateRatingLimits(0),
                'recommendations' => $this->distributionService->getRecommendedAdjustments([5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0], 0)
            ];
        }
    }
    
    return $distributionByWorkstation;
}
   /**
 * Get team size grouped by workstation
 */
public function getAllTeamSizes()
{
    $teamSizes = [];
    
    // Get HQ team
    $hqCount = User::where('is_onboarded', true)
        ->where('workstation_type', 'hq')
        ->count();
    $teamSizes['HQ'] = $hqCount;
    
    // Get Toll Plazas - INCLUDING MANYUMBI
    $plazas = [
        'TP-001' => 'Kafulafuta Toll Plaza',
        'TP-002' => 'Abram Zayoni Mokola Toll Plaza',
        'TP-003' => 'Katuba Toll Plaza',
        'TP-004' => 'Manyumbi Toll Plaza',
        'TP-005' => 'Konkola Toll Plaza'
    ];
    
    foreach ($plazas as $code => $name) {
        $plazaCount = User::where('is_onboarded', true)
            ->where('workstation_type', 'toll_plaza')
            ->where('toll_plaza', $code)
            ->count();
        $teamSizes[$name] = $plazaCount;
    }
    
    return $teamSizes;
}
    
    /**
     * Download quarterly summary for ALL employees and supervisors
     * INCLUDES users with or without ratings
     */
    public function download(Request $request)
    {
        $user = auth()->user();
        
        // Check if user has permission (admin or supervisor)
        if ($user->user_type !== 'supervisor' && !$user->is_admin) {
            abort(403, 'You do not have permission to download this report.');
        }
        
        $year = $request->get('year', date('Y'));
        $quarter = $request->get('quarter', 'all');
        $department = $request->get('department');
        $position = $request->get('position');
        $employeeType = $request->get('employee_type', 'all'); // 'all', 'employee', 'supervisor'
        $workstation = $request->get('workstation'); // Combined workstation filter
        $location = $request->get('location'); // Keep for backward compatibility
        $tollPlaza = $request->get('toll_plaza');
        $hqDepartment = $request->get('hq_department');
        
        // Get ALL users in the system for the selected year - INCLUDING those with no appraisals
        $employees = User::where('is_onboarded', true)
            ->with(['appraisals' => function($query) use ($year) {
                $query->whereYear('created_at', $year)
                    ->orWhereYear('start_date', $year)
                    ->orWhereYear('updated_at', $year)
                    ->with('kpas');
            }]);
        
        // Filter by user type if specified
        if ($employeeType !== 'all') {
            $employees = $employees->where('user_type', $employeeType);
        }
        
        // Apply department filter
        if ($department && $department !== '') {
            $employees = $employees->where('department', $department);
        }
        
        // Apply position filter
        if ($position && $position !== '') {
            $employees = $employees->where('job_title', $position);
        }
        
        // Apply workstation filter (combined approach)
        if ($workstation && $workstation !== '') {
            if ($workstation === 'HQ') {
                $employees = $employees->where('workstation_type', 'hq');
                
                if ($hqDepartment && $hqDepartment !== '') {
                    $employees = $employees->where('hq_department', $hqDepartment);
                }
            } else {
                // Filter by specific toll plaza name
                $plazaCode = $this->getTollPlazaCode($workstation);
                $employees = $employees->where('workstation_type', 'toll_plaza')
                    ->where('toll_plaza', $plazaCode);
            }
        }
        // Keep backward compatibility
        elseif ($location && $location !== '') {
            if ($location === 'hq') {
                $employees = $employees->where('workstation_type', 'hq');
                
                if ($hqDepartment && $hqDepartment !== '') {
                    $employees = $employees->where('hq_department', $hqDepartment);
                }
            } elseif ($location === 'toll_plaza') {
                $employees = $employees->where('workstation_type', 'toll_plaza');
                
                if ($tollPlaza && $tollPlaza !== '') {
                    $employees = $employees->where('toll_plaza', $tollPlaza);
                }
            }
        }
        
        $employees = $employees->get();
        
        // Generate Excel file with ALL data and professional styling
        return $this->generateExcel($employees, $year, $quarter, $user, $employeeType, $workstation, $hqDepartment);
    }
    
    /**
     * Download complete system-wide report with ALL appraisals (for admins only)
     * INCLUDES users with or without ratings
     */
    public function downloadAllAppraisals(Request $request)
    {
        $user = auth()->user();
        
        // Check if user is admin
        if (!$user->is_admin) {
            abort(403, 'Only administrators can download the complete system report.');
        }
        
        $year = $request->get('year', date('Y'));
        $quarter = $request->get('quarter', 'all');
        $department = $request->get('department');
        $position = $request->get('position');
        $employeeType = $request->get('employee_type', 'all'); // 'all', 'employee', 'supervisor'
        $workstation = $request->get('workstation'); // Combined workstation filter
        $location = $request->get('location'); // Keep for backward compatibility
        $tollPlaza = $request->get('toll_plaza');
        $hqDepartment = $request->get('hq_department');
        $includeWithoutRatings = $request->get('include_without_ratings', true); // Default to true
        
        // Get ALL users in the system for the selected year - INCLUDING those with no appraisals
        $employees = User::where('is_onboarded', true)
            ->with(['appraisals' => function($query) use ($year) {
                $query->whereYear('created_at', $year)
                    ->orWhereYear('start_date', $year)
                    ->orWhereYear('updated_at', $year)
                    ->with('kpas');
            }]);
        
        // Filter by user type if specified
        if ($employeeType !== 'all') {
            $employees = $employees->where('user_type', $employeeType);
        }
        
        // Apply filters
        if ($department && $department !== '') {
            $employees = $employees->where('department', $department);
        }
        
        if ($position && $position !== '') {
            $employees = $employees->where('job_title', $position);
        }
        
        // Apply workstation filter (combined approach)
        if ($workstation && $workstation !== '') {
            if ($workstation === 'HQ') {
                $employees = $employees->where('workstation_type', 'hq');
                
                if ($hqDepartment && $hqDepartment !== '') {
                    $employees = $employees->where('hq_department', $hqDepartment);
                }
            } else {
                // Filter by specific toll plaza name
                $plazaCode = $this->getTollPlazaCode($workstation);
                $employees = $employees->where('workstation_type', 'toll_plaza')
                    ->where('toll_plaza', $plazaCode);
            }
        }
        // Keep backward compatibility
        elseif ($location && $location !== '') {
            if ($location === 'hq') {
                $employees = $employees->where('workstation_type', 'hq');
                
                if ($hqDepartment && $hqDepartment !== '') {
                    $employees = $employees->where('hq_department', $hqDepartment);
                }
            } elseif ($location === 'toll_plaza') {
                $employees = $employees->where('workstation_type', 'toll_plaza');
                
                if ($tollPlaza && $tollPlaza !== '') {
                    $employees = $employees->where('toll_plaza', $tollPlaza);
                }
            }
        }
        
        $employees = $employees->get();
        
        // Get all appraisals count for summary for the selected year
        $totalAppraisals = Appraisal::whereYear('created_at', $year)->count();
        $submittedAppraisals = Appraisal::whereYear('created_at', $year)->where('status', 'submitted')->count();
        $approvedAppraisals = Appraisal::whereYear('created_at', $year)->where('status', 'approved')->count();
        $draftAppraisals = Appraisal::whereYear('created_at', $year)->where('status', 'draft')->count();
        
        // Count users with and without ratings
        $usersWithRatings = $employees->filter(function($employee) use ($year) {
            return $employee->appraisals->isNotEmpty();
        })->count();
        
        $usersWithoutRatings = $employees->filter(function($employee) use ($year) {
            return $employee->appraisals->isEmpty();
        })->count();
        
        // Generate Excel file with all data
        return $this->generateCompleteSystemReport($employees, $year, $quarter, $user, $employeeType, [
            'total_appraisals' => $totalAppraisals,
            'submitted' => $submittedAppraisals,
            'approved' => $approvedAppraisals,
            'draft' => $draftAppraisals,
            'users_with_ratings' => $usersWithRatings,
            'users_without_ratings' => $usersWithoutRatings
        ], $workstation, $hqDepartment);
    }
    
    /**
     * Download all appraisals raw data (for admins only) - filtered by year
     * INCLUDES users with or without ratings
     */
    public function downloadAllAppraisalsRaw(Request $request)
    {
        $user = auth()->user();
        
        // Check if user is admin
        if (!$user->is_admin) {
            abort(403, 'Only administrators can download raw appraisal data.');
        }
        
        $year = $request->get('year', date('Y'));
        $quarter = $request->get('quarter', 'all');
        $status = $request->get('status', 'all');
        $workstation = $request->get('workstation'); // Combined workstation filter
        $location = $request->get('location'); // Keep for backward compatibility
        $tollPlaza = $request->get('toll_plaza');
        $hqDepartment = $request->get('hq_department');
        $includeWithoutRatings = $request->get('include_without_ratings', true);
        
        // Get all appraisals with related data for the selected year
        $query = Appraisal::with(['user', 'kpas', 'approver'])
            ->whereYear('created_at', $year);
        
        if ($quarter !== 'all') {
            $dates = $this->getQuarterDates($quarter, $year);
            $query->whereBetween('created_at', [$dates['start'], $dates['end']]);
        }
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Apply workstation filter through user relationship
        if ($workstation && $workstation !== '') {
            if ($workstation === 'HQ') {
                $query->whereHas('user', function($q) use ($hqDepartment) {
                    $q->where('workstation_type', 'hq');
                    
                    if ($hqDepartment && $hqDepartment !== '') {
                        $q->where('hq_department', $hqDepartment);
                    }
                });
            } else {
                // Filter by specific toll plaza name
                $plazaCode = $this->getTollPlazaCode($workstation);
                $query->whereHas('user', function($q) use ($plazaCode) {
                    $q->where('workstation_type', 'toll_plaza')
                      ->where('toll_plaza', $plazaCode);
                });
            }
        }
        // Keep backward compatibility
        elseif ($location && $location !== '') {
            if ($location === 'hq') {
                $query->whereHas('user', function($q) use ($hqDepartment) {
                    $q->where('workstation_type', 'hq');
                    
                    if ($hqDepartment && $hqDepartment !== '') {
                        $q->where('hq_department', $hqDepartment);
                    }
                });
            } elseif ($location === 'toll_plaza') {
                $query->whereHas('user', function($q) use ($tollPlaza) {
                    $q->where('workstation_type', 'toll_plaza');
                    
                    if ($tollPlaza && $tollPlaza !== '') {
                        $q->where('toll_plaza', $tollPlaza);
                    }
                });
            }
        }
        
        $appraisals = $query->orderBy('created_at', 'desc')->get();
        
        // Generate Excel file with raw appraisal data
        return $this->generateRawAppraisalsExcel($appraisals, $year, $quarter, $user);
    }
    
    /**
     * Download complete user list with appraisal status (with or without ratings)
     * Shows all users and whether they have ratings
     */
    public function downloadUserListWithStatus(Request $request)
    {
        $user = auth()->user();
        
        // Check if user is admin
        if (!$user->is_admin) {
            abort(403, 'Only administrators can download this report.');
        }
        
        $year = $request->get('year', date('Y'));
        $department = $request->get('department');
        $position = $request->get('position');
        $employeeType = $request->get('employee_type', 'all');
        $workstation = $request->get('workstation'); // Combined workstation filter
        $location = $request->get('location'); // Keep for backward compatibility
        $tollPlaza = $request->get('toll_plaza');
        $hqDepartment = $request->get('hq_department');
        
        // Get ALL users
        $users = User::where('is_onboarded', true);
        
        if ($employeeType !== 'all') {
            $users = $users->where('user_type', $employeeType);
        }
        
        if ($department && $department !== '') {
            $users = $users->where('department', $department);
        }
        
        if ($position && $position !== '') {
            $users = $users->where('job_title', $position);
        }
        
        // Apply workstation filter (combined approach)
        if ($workstation && $workstation !== '') {
            if ($workstation === 'HQ') {
                $users = $users->where('workstation_type', 'hq');
                
                if ($hqDepartment && $hqDepartment !== '') {
                    $users = $users->where('hq_department', $hqDepartment);
                }
            } else {
                // Filter by specific toll plaza name
                $plazaCode = $this->getTollPlazaCode($workstation);
                $users = $users->where('workstation_type', 'toll_plaza')
                    ->where('toll_plaza', $plazaCode);
            }
        }
        // Keep backward compatibility
        elseif ($location && $location !== '') {
            if ($location === 'hq') {
                $users = $users->where('workstation_type', 'hq');
                
                if ($hqDepartment && $hqDepartment !== '') {
                    $users = $users->where('hq_department', $hqDepartment);
                }
            } elseif ($location === 'toll_plaza') {
                $users = $users->where('workstation_type', 'toll_plaza');
                
                if ($tollPlaza && $tollPlaza !== '') {
                    $users = $users->where('toll_plaza', $tollPlaza);
                }
            }
        }
        
        $users = $users->orderBy('user_type')->orderBy('name')->get();
        
        // Get appraisal counts for each user for the selected year
        foreach ($users as $userItem) {
            $appraisals = Appraisal::where('employee_number', $userItem->employee_number)
                ->whereYear('created_at', $year)
                ->get();
            
            $userItem->appraisal_count = $appraisals->count();
            $userItem->has_ratings = $appraisals->count() > 0;
            $userItem->latest_appraisal = $appraisals->sortByDesc('created_at')->first();
        }
        
        return $this->generateUserListExcel($users, $year, $user, $workstation, $hqDepartment);
    }
    
    /**
     * Calculate summary statistics - includes location-specific stats
     */
    private function calculateSummaryData($employees, $year, $quarters)
    {
        $totalUsers = $employees->count();
        $totalScore = 0;
        $scoredUsers = 0;
        $totalBonusPool = 0;
        $totalSalary = 0;
        $usersWithoutRatings = 0;
        
        $quarterlyAverages = [
            'Q1' => ['self' => 0, 'manager' => 0, 'count' => 0],
            'Q2' => ['self' => 0, 'manager' => 0, 'count' => 0],
            'Q3' => ['self' => 0, 'manager' => 0, 'count' => 0],
            'Q4' => ['self' => 0, 'manager' => 0, 'count' => 0],
        ];
        
        $distribution = [
            'excellent' => 0,
            'good' => 0,
            'satisfactory' => 0,
            'needs_improvement' => 0,
            'unsatisfactory' => 0,
            'no_ratings' => 0
        ];
        
        $typeStats = [
            'employees' => 0,
            'supervisors' => 0,
            'employee_score' => 0,
            'supervisor_score' => 0
        ];
        
        $departmentStats = [];
        $workstationStats = [
            'hq' => ['count' => 0, 'with_ratings' => 0, 'total_score' => 0],
            'toll_plaza' => ['count' => 0, 'with_ratings' => 0, 'total_score' => 0]
        ];
        
        // Specific toll plaza statistics - using full names
        $tollPlazaStats = [
            'TP-001' => ['name' => 'Kafulafuta Toll Plaza', 'count' => 0, 'with_ratings' => 0, 'total_score' => 0],
            'TP-002' => ['name' => 'Abram Zayoni Mokola Toll Plaza', 'count' => 0, 'with_ratings' => 0, 'total_score' => 0],
            'TP-003' => ['name' => 'Katuba Toll Plaza', 'count' => 0, 'with_ratings' => 0, 'total_score' => 0],
            'TP-004' => ['name' => 'Manyumbi Toll Plaza', 'count' => 0, 'with_ratings' => 0, 'total_score' => 0],
            'TP-005' => ['name' => 'Konkola Toll Plaza', 'count' => 0, 'with_ratings' => 0, 'total_score' => 0]
        ];
        
        $hqDepartmentStats = [];
        
        foreach ($employees as $employee) {
            $monthlySalary = $employee->monthly_salary ?? 0;
            $totalSalary += $monthlySalary;
            
            // Count by user type
            if ($employee->user_type === 'employee') {
                $typeStats['employees']++;
            } elseif ($employee->user_type === 'supervisor') {
                $typeStats['supervisors']++;
            }
            
            // Track by workstation
            $workstationType = $employee->workstation_type ?? 'unknown';
            if ($workstationType === 'hq') {
                $workstationStats['hq']['count']++;
                
                // Track by HQ department
                $hqDept = $employee->hq_department ?? 'Unassigned';
                if (!isset($hqDepartmentStats[$hqDept])) {
                    $hqDepartmentStats[$hqDept] = [
                        'count' => 0,
                        'with_ratings' => 0,
                        'total_score' => 0
                    ];
                }
                $hqDepartmentStats[$hqDept]['count']++;
                
            } elseif ($workstationType === 'toll_plaza') {
                $workstationStats['toll_plaza']['count']++;
                
                // Track by specific toll plaza - using codes
                $plazaCode = $employee->toll_plaza ?? 'Unknown';
                if (isset($tollPlazaStats[$plazaCode])) {
                    $tollPlazaStats[$plazaCode]['count']++;
                } else {
                    // Handle any unknown plaza codes
                    if (!isset($tollPlazaStats[$plazaCode])) {
                        $tollPlazaStats[$plazaCode] = [
                            'name' => $plazaCode,
                            'count' => 0,
                            'with_ratings' => 0,
                            'total_score' => 0
                        ];
                    }
                    $tollPlazaStats[$plazaCode]['count']++;
                }
            }
            
            $department = $employee->department ?? 'Unassigned';
            if (!isset($departmentStats[$department])) {
                $departmentStats[$department] = [
                    'count' => 0,
                    'total_score' => 0,
                    'total_salary' => 0,
                    'with_ratings' => 0,
                    'without_ratings' => 0
                ];
            }
            $departmentStats[$department]['count']++;
            $departmentStats[$department]['total_salary'] += $monthlySalary;
            
            $employeeTotalScore = 0;
            $quarterCount = 0;
            $hasAnyRating = false;
            
            foreach ($quarters as $q) {
                $selfScore = $this->getQuarterlyScore($employee, $q, $year, 'self');
                $managerScore = $this->getQuarterlyScore($employee, $q, $year, 'manager');
                
                if ($selfScore !== null && $selfScore > 0) {
                    $quarterlyAverages[$q]['self'] += $selfScore;
                    $quarterlyAverages[$q]['count']++;
                    $hasAnyRating = true;
                }
                
                if ($managerScore !== null && $managerScore > 0) {
                    $quarterlyAverages[$q]['manager'] += $managerScore;
                    $employeeTotalScore += $managerScore;
                    $quarterCount++;
                    $hasAnyRating = true;
                }
            }
            
            // Track users without ratings
            if (!$hasAnyRating) {
                $usersWithoutRatings++;
                $distribution['no_ratings']++;
                $departmentStats[$department]['without_ratings']++;
            } else {
                $departmentStats[$department]['with_ratings']++;
                
                // Update workstation stats
                if ($workstationType === 'hq') {
                    $workstationStats['hq']['with_ratings']++;
                    if (isset($hqDepartmentStats[$hqDept])) {
                        $hqDepartmentStats[$hqDept]['with_ratings']++;
                    }
                } elseif ($workstationType === 'toll_plaza') {
                    $workstationStats['toll_plaza']['with_ratings']++;
                    
                    // Update specific toll plaza stats
                    $plazaCode = $employee->toll_plaza ?? 'Unknown';
                    if (isset($tollPlazaStats[$plazaCode])) {
                        $tollPlazaStats[$plazaCode]['with_ratings']++;
                    }
                }
            }
            
            // Calculate average for this employee
            if ($quarterCount > 0) {
                $avgScore = $employeeTotalScore / $quarterCount;
                $totalScore += $avgScore;
                $scoredUsers++;
                
                // Track scores by workstation
                if ($workstationType === 'hq') {
                    $workstationStats['hq']['total_score'] += $avgScore;
                    if (isset($hqDepartmentStats[$hqDept])) {
                        $hqDepartmentStats[$hqDept]['total_score'] += $avgScore;
                    }
                } elseif ($workstationType === 'toll_plaza') {
                    $workstationStats['toll_plaza']['total_score'] += $avgScore;
                    
                    // Update specific toll plaza stats
                    $plazaCode = $employee->toll_plaza ?? 'Unknown';
                    if (isset($tollPlazaStats[$plazaCode])) {
                        $tollPlazaStats[$plazaCode]['total_score'] += $avgScore;
                    }
                }
                
                // Track scores by user type
                if ($employee->user_type === 'employee') {
                    $typeStats['employee_score'] += $avgScore;
                } elseif ($employee->user_type === 'supervisor') {
                    $typeStats['supervisor_score'] += $avgScore;
                }
                
                $departmentStats[$department]['total_score'] += $avgScore;
                
                // Distribution counts
                if ($avgScore >= 90) {
                    $distribution['excellent']++;
                } elseif ($avgScore >= 80) {
                    $distribution['good']++;
                } elseif ($avgScore >= 70) {
                    $distribution['satisfactory']++;
                } elseif ($avgScore >= 60) {
                    $distribution['needs_improvement']++;
                } else {
                    $distribution['unsatisfactory']++;
                }
                
                // Calculate bonus
                $bonusMultiplier = $this->calculateBonusMultiplier($avgScore);
                $totalBonusPool += $monthlySalary * $bonusMultiplier;
            }
        }
        
        // Calculate department averages
        foreach ($departmentStats as $dept => &$stats) {
            if ($stats['count'] > 0) {
                $stats['avg_score'] = $stats['total_score'] > 0 
                    ? round($stats['total_score'] / $stats['with_ratings'], 1) 
                    : 0;
                $stats['avg_salary'] = round($stats['total_salary'] / $stats['count'], 2);
                $stats['ratings_percentage'] = $stats['count'] > 0 
                    ? round(($stats['with_ratings'] / $stats['count']) * 100, 1) 
                    : 0;
            }
        }
        
        // Calculate workstation averages
        foreach ($workstationStats as $ws => &$stats) {
            if ($stats['count'] > 0) {
                $stats['avg_score'] = $stats['total_score'] > 0 && $stats['with_ratings'] > 0
                    ? round($stats['total_score'] / $stats['with_ratings'], 1)
                    : 0;
                $stats['ratings_percentage'] = round(($stats['with_ratings'] / $stats['count']) * 100, 1);
            }
        }
        
        // Calculate toll plaza averages - using full names
        foreach ($tollPlazaStats as $plazaCode => &$stats) {
            if ($stats['count'] > 0) {
                $stats['avg_score'] = $stats['total_score'] > 0 && $stats['with_ratings'] > 0
                    ? round($stats['total_score'] / $stats['with_ratings'], 1)
                    : 0;
                $stats['ratings_percentage'] = round(($stats['with_ratings'] / $stats['count']) * 100, 1);
            }
        }
        
        // Calculate HQ department averages
        foreach ($hqDepartmentStats as $dept => &$stats) {
            if ($stats['count'] > 0) {
                $stats['avg_score'] = $stats['total_score'] > 0 && $stats['with_ratings'] > 0
                    ? round($stats['total_score'] / $stats['with_ratings'], 1)
                    : 0;
                $stats['ratings_percentage'] = round(($stats['with_ratings'] / $stats['count']) * 100, 1);
            }
        }
        
        // Calculate averages by user type
        if ($typeStats['employees'] > 0) {
            $typeStats['avg_employee_score'] = round($typeStats['employee_score'] / $typeStats['employees'], 1);
        }
        if ($typeStats['supervisors'] > 0) {
            $typeStats['avg_supervisor_score'] = round($typeStats['supervisor_score'] / $typeStats['supervisors'], 1);
        }
        
        // Calculate percentages for distribution
        $distributionPercentages = [];
        foreach ($distribution as $key => $count) {
            $distributionPercentages[$key . '_pct'] = $totalUsers > 0 
                ? round(($count / $totalUsers) * 100, 1) 
                : 0;
        }
        
        // Calculate quarterly averages
        foreach ($quarterlyAverages as $q => $data) {
            if ($data['count'] > 0) {
                $quarterlyAverages[$q]['self'] = round($data['self'] / $data['count'], 1);
                $quarterlyAverages[$q]['manager'] = round($data['manager'] / $data['count'], 1);
            }
        }
        
        return [
            'total_users' => $totalUsers,
            'total_employees' => $typeStats['employees'],
            'total_supervisors' => $typeStats['supervisors'],
            'average_score' => $scoredUsers > 0 ? round($totalScore / $scoredUsers, 1) : 0,
            'avg_employee_score' => $typeStats['avg_employee_score'] ?? 0,
            'avg_supervisor_score' => $typeStats['avg_supervisor_score'] ?? 0,
            'top_performers' => $distribution['excellent'] + $distribution['good'],
            'total_bonus_pool' => round($totalBonusPool, 2),
            'avg_salary' => $totalUsers > 0 ? round($totalSalary / $totalUsers, 2) : 0,
            'quarterly_averages' => $quarterlyAverages,
            'distribution' => array_merge($distribution, $distributionPercentages),
            'department_stats' => $departmentStats,
            'workstation_stats' => $workstationStats,
            'toll_plaza_stats' => $tollPlazaStats,
            'hq_department_stats' => $hqDepartmentStats,
            'scored_users' => $scoredUsers,
            'users_without_ratings' => $usersWithoutRatings,
            'ratings_coverage' => $totalUsers > 0 ? round(($scoredUsers / $totalUsers) * 100, 1) : 0
        ];
    }
    
    /**
     * Get quarterly score for an employee - FIXED to return null for no data
     */
    private function getQuarterlyScore($employee, $quarter, $year, $type = 'manager')
    {
        // Determine date range for quarter
        $dates = $this->getQuarterDates($quarter, $year);
        
        // Get appraisals in this quarter for the selected year
        $appraisals = $employee->appraisals()
            ->where(function($query) use ($dates) {
                $query->whereBetween('created_at', [$dates['start'], $dates['end']])
                    ->orWhereBetween('start_date', [$dates['start'], $dates['end']])
                    ->orWhereBetween('updated_at', [$dates['start'], $dates['end']]);
            })
            ->with('kpas')
            ->get();
        
        if ($appraisals->isEmpty()) {
            return null; // Return null for no data
        }
        
        $totalScore = 0;
        $appraisalCount = 0;
        
        foreach ($appraisals as $appraisal) {
            if ($type === 'self') {
                $score = $this->calculateSelfScore($appraisal);
            } else {
                $score = $this->calculateManagerScore($appraisal);
            }
            
            if ($score > 0) {
                $totalScore += $score;
                $appraisalCount++;
            }
        }
        
        // Return rounded to nearest integer, or null if no scores
        return $appraisalCount > 0 ? round($totalScore / $appraisalCount, 0) : null;
    }
    
    /**
     * Calculate self score from KPAs
     */
    private function calculateSelfScore($appraisal)
    {
        $totalWeight = 0;
        $weightedSum = 0;
        
        foreach ($appraisal->kpas as $kpa) {
            $weight = $kpa->weight ?? 0;
            $totalWeight += $weight;
            
            $selfRating = $kpa->self_rating ?? 0;
            $kpi = $kpa->kpi ?? 4;
            
            if ($selfRating > 0 && $kpi > 0) {
                $percentage = ($selfRating / $kpi) * 100;
                $weightedSum += ($percentage * $weight) / 100;
            }
        }
        
        return $totalWeight > 0 ? $weightedSum : 0;
    }
    
    /**
     * Calculate manager score from KPAs
     */
    private function calculateManagerScore($appraisal)
    {
        $totalWeight = 0;
        $weightedSum = 0;
        
        foreach ($appraisal->kpas as $kpa) {
            $weight = $kpa->weight ?? 0;
            $totalWeight += $weight;
            
            $managerRating = $kpa->supervisor_rating ?? 0;
            $kpi = $kpa->kpi ?? 4;
            
            if ($managerRating > 0 && $kpi > 0) {
                $percentage = ($managerRating / $kpi) * 100;
                $weightedSum += ($percentage * $weight) / 100;
            }
        }
        
        return $totalWeight > 0 ? $weightedSum : 0;
    }
    
    /**
     * Get quarter date ranges
     */
    private function getQuarterDates($quarter, $year)
    {
        $quarters = [
            'Q1' => ['start' => "$year-01-01", 'end' => "$year-03-31"],
            'Q2' => ['start' => "$year-04-01", 'end' => "$year-06-30"],
            'Q3' => ['start' => "$year-07-01", 'end' => "$year-09-30"],
            'Q4' => ['start' => "$year-10-01", 'end' => "$year-12-31"],
        ];
        
        return $quarters[$quarter] ?? $quarters['Q1'];
    }
    
    /**
     * Calculate bonus multiplier based on score - EXACTLY as in your example
     */
    private function calculateBonusMultiplier($score)
    {
        if ($score >= 90) return 2;
        if ($score >= 80) return 1.5;
        if ($score >= 70) return 1;
        if ($score >= 60) return 0.5;
        return 0;
    }
    
    /**
     * Get top performers from ALL users
     */
    private function getTopPerformers($employees, $year)
    {
        $performers = [];
        
        foreach ($employees as $employee) {
            $totalScore = 0;
            $quarterCount = 0;
            
            foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $quarter) {
                $score = $this->getQuarterlyScore($employee, $quarter, $year, 'manager');
                if ($score !== null && $score > 0) {
                    $totalScore += $score;
                    $quarterCount++;
                }
            }
            
            if ($quarterCount > 0) {
                $avgScore = $totalScore / $quarterCount;
                if ($avgScore >= 80) {
                    $performers[] = [
                        'name' => $employee->name,
                        'position' => $employee->job_title,
                        'department' => $employee->department,
                        'employee_number' => $employee->employee_number,
                        'user_type' => $employee->user_type,
                        'workstation' => $this->getWorkstationDisplay($employee),
                        'average' => round($avgScore, 1),
                        'bonus_multiplier' => $this->calculateBonusMultiplier($avgScore)
                    ];
                }
            }
        }
        
        // Sort by average score descending and take top 10
        usort($performers, function($a, $b) {
            return $b['average'] <=> $a['average'];
        });
        
        return array_slice($performers, 0, 10);
    }
    
    /**
 * Get workstation display value (single column)
 */
private function getWorkstationDisplay($employee)
{
    if ($employee->workstation_type === 'hq') {
        return 'HQ';
    } elseif ($employee->workstation_type === 'toll_plaza') {
        return $this->getTollPlazaName($employee->toll_plaza);
    }
    return 'Unknown';
}

/**
 * Get toll plaza display name from code
 */
private function getTollPlazaName($code)
{
    $plazas = [
        'TP-001' => 'Kafulafuta Toll Plaza',
        'TP-002' => 'Abram Zayoni Mokola Toll Plaza',
        'TP-003' => 'Katuba Toll Plaza',
        'TP-004' => 'Manyumbi Toll Plaza',
        'TP-005' => 'Konkola Toll Plaza',
        // Also handle direct names
        'kafulafuta' => 'Kafulafuta Toll Plaza',
        'abram_zayoni_mokola' => 'Abram Zayoni Mokola Toll Plaza',
        'katuba' => 'Katuba Toll Plaza',
        'manyumbi' => 'Manyumbi Toll Plaza',
        'konkola' => 'Konkola Toll Plaza'
    ];
    
    return $plazas[$code] ?? $code;
}
    
    
    
    /**
     * Get toll plaza code from name
     */
    private function getTollPlazaCode($name)
    {
        $plazas = [
            'Kafulafuta Toll Plaza' => 'TP-001',
            'Abram Zayoni Mokola Toll Plaza' => 'TP-002',
            'Katuba Toll Plaza' => 'TP-003',
            'Manyumbi Toll Plaza' => 'TP-004',
            'Konkola Toll Plaza' => 'TP-005'
        ];
        
        return $plazas[$name] ?? $name;
    }
    
    /**
     * Get performance color based on score
     */
    private function getPerformanceColor($score)
    {
        if ($score >= 90) return 'FF00B050'; // Bright Green
        if ($score >= 80) return 'FF0070C0'; // Bright Blue
        if ($score >= 70) return 'FFFFC000'; // Gold/Orange
        if ($score >= 60) return 'FFFF6600'; // Orange-Red
        return 'FFFF0000'; // Red
    }
    
    /**
     * Get performance background color (light version)
     */
    private function getPerformanceBackgroundColor($score)
    {
        if ($score >= 90) return 'FFE6F0E6'; // Light Green
        if ($score >= 80) return 'FFE6F0FF'; // Light Blue
        if ($score >= 70) return 'FFFFF0E6'; // Light Orange
        if ($score >= 60) return 'FFFFE6E6'; // Light Red-Orange
        return 'FFFFE6E6'; // Light Red
    }
    
   /**
 * Generate Excel file for download with professional styling and Distribution Compliance
 */
private function generateExcel($employees, $year, $quarter, $user = null, $employeeType = 'all', $workstation = null, $hqDepartment = null)
{
    if (!$user) {
        $user = auth()->user();
    }
    
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Set document properties
    $spreadsheet->getProperties()
        ->setCreator($user->name ?? 'System')
        ->setLastModifiedBy($user->name ?? 'System')
        ->setTitle("Performance Summary {$year}")
        ->setSubject("Employee Performance Ratings for {$year}")
        ->setDescription("Quarterly performance summary report for year {$year}");
    
    // Define color constants
    $primaryColor = 'FF110484'; // MOIC Navy
    $secondaryColor = 'FFE7581C'; // MOIC Orange/Accent
    $headerBgColor = 'FF110484'; // Dark blue for headers
    $headerTextColor = 'FFFFFFFF'; // White
    $evenRowColor = 'FFF5F5F5'; // Light gray for even rows
    $oddRowColor = 'FFFFFFFF'; // White for odd rows
    $borderColor = 'FFCCCCCC'; // Light gray for borders
    $employeeTypeColor = 'FFE3F2FD'; // Very light blue for employee rows
    $noRatingsColor = 'FFFFF0E0'; // Light peach for users without ratings
    
    // HQ and Toll Plaza colors
    $hqColor = 'FFE6F0FF'; // Light blue for HQ
    $tollPlazaColor = 'FFF0E6FF'; // Light purple for Toll Plaza
    
    // Calculate total columns for merges
    $totalColumns = $this->getColumnCount($quarter);
    $lastColumnLetter = $this->getExcelColumn($totalColumns);
    
    // Add title with styling
    $title = "PERFORMANCE SUMMARY REPORT - {$year}";
    $sheet->setCellValue('A1', $title);
    $sheet->mergeCells('A1:' . $lastColumnLetter . '1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(18);
    $sheet->getStyle('A1')->getFont()->getColor()->setARGB($primaryColor);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getRowDimension(1)->setRowHeight(30);
    
    // Period information
    $periodText = 'YEAR: ' . $year . ($quarter !== 'all' ? ' - ' . $quarter : ' - FULL YEAR');
    $typeText = $employeeType !== 'all' ? ' - ' . strtoupper($employeeType) . 'S ONLY' : ' - ALL EMPLOYEES & SUPERVISORS';
    
    // Add workstation filter info
    $workstationText = '';
    if ($workstation && $workstation !== '') {
        $workstationText = ' - WORKSTATION: ' . $workstation;
    }
    
    $usersWithRatings = $employees->filter(function($emp) use ($year) {
        return $emp->appraisals->isNotEmpty();
    })->count();
    $usersWithoutRatings = $employees->count() - $usersWithRatings;
    
    $periodText .= $typeText . $workstationText . ' (Total: ' . $employees->count() . ' users - ' . $usersWithRatings . ' with ratings, ' . $usersWithoutRatings . ' without ratings)';
    
    $sheet->setCellValue('A2', $periodText);
    $sheet->mergeCells('A2:' . $lastColumnLetter . '2');
    $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
    $sheet->getStyle('A2')->getFont()->getColor()->setARGB($secondaryColor);
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
    // Generation info
    $sheet->setCellValue('A3', 'Report Generated: ' . now()->format('F j, Y \a\t g:i A'));
    $sheet->mergeCells('A3:' . $lastColumnLetter . '3');
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A3')->getFont()->setItalic(true);
    
    $sheet->setCellValue('A4', 'Generated By: ' . $user->name . ' (' . $user->employee_number . ') - ' . ucfirst($user->user_type));
    $sheet->mergeCells('A4:' . $lastColumnLetter . '4');
    $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A4')->getFont()->setItalic(true);
    
    // Empty row for spacing
    $sheet->setCellValue('A5', '');
    
    // Set headers starting from row 6 - SINGLE WORKSTATION COLUMN
    $headerRow = 6;
    $headers = ['User Type', 'Workstation', 'Employee Name', 'Employee No.', 'Position', 'Department', 'Has Ratings?'];
    
    $quarters = $quarter !== 'all' ? [$quarter] : ['Q1', 'Q2', 'Q3', 'Q4'];
    
    foreach ($quarters as $q) {
        $headers[] = $q . ' Self %';
        $headers[] = $q . ' Manager %';
    }
    
    $headers = array_merge($headers, [
        'Average Rating',
        'Corresponding Bonus Multiplier',
        'Monthly Salary',
        'Annual Bonus',
        'Status'
    ]);
    
    // Write headers with styling
    $colIndex = 1;
    foreach ($headers as $header) {
        $cell = $this->getExcelColumn($colIndex) . $headerRow;
        $sheet->setCellValue($cell, $header);
        
        // Style headers
        $sheet->getStyle($cell)->getFont()->setBold(true);
        $sheet->getStyle($cell)->getFont()->getColor()->setARGB($headerTextColor);
        $sheet->getStyle($cell)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB($headerBgColor);
        $sheet->getStyle($cell)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle($cell)->getBorders()->getOutline()
            ->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new Color($borderColor));
        
        $colIndex++;
    }
    
    $sheet->getRowDimension($headerRow)->setRowHeight(25);
    
    // Add data with alternating row colors and styling
    $dataRow = $headerRow + 1;
    $rowNumber = 0;
    
    foreach ($employees as $employee) {
        $colIndex = 1;
        $isEven = $rowNumber % 2 == 0;
        $rowColor = $isEven ? $evenRowColor : $oddRowColor;
        
        // Check if user has any appraisals
        $hasRatings = $employee->appraisals->isNotEmpty();
        
        // Determine workstation display
        $workstationDisplay = $this->getWorkstationDisplay($employee);
        
        // Color by workstation
        $typeColor = $employeeTypeColor;
        if ($employee->workstation_type === 'hq') {
            $typeColor = $hqColor;
        } elseif ($employee->workstation_type === 'toll_plaza') {
            $typeColor = $tollPlazaColor;
        }
        
        // If no ratings, use a different background
        if (!$hasRatings) {
            $typeColor = $noRatingsColor;
        }
        
        // User Type with special background
        $cell = $this->getExcelColumn($colIndex) . $dataRow;
        $userType = ucfirst($employee->user_type ?? 'Employee');
        $sheet->setCellValue($cell, $userType);
        $sheet->getStyle($cell)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB($typeColor);
        $sheet->getStyle($cell)->getFont()->setBold(true);
        $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $colIndex++;
        
        // Workstation - SINGLE COLUMN
        $cell = $this->getExcelColumn($colIndex) . $dataRow;
        $sheet->setCellValue($cell, $workstationDisplay);
        $sheet->getStyle($cell)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB($typeColor);
        $colIndex++;
        
        // Employee Name
        $cell = $this->getExcelColumn($colIndex) . $dataRow;
        $sheet->setCellValue($cell, $employee->name);
        $sheet->getStyle($cell)->getFont()->setBold(true);
        $colIndex++;
        
        // Employee Number
        $cell = $this->getExcelColumn($colIndex) . $dataRow;
        $sheet->setCellValue($cell, $employee->employee_number);
        $sheet->getStyle($cell)->getNumberFormat()->setFormatCode('@'); // Text format
        $colIndex++;
        
        // Position
        $cell = $this->getExcelColumn($colIndex) . $dataRow;
        $sheet->setCellValue($cell, $employee->job_title ?? 'N/A');
        $colIndex++;
        
        // Department
        $cell = $this->getExcelColumn($colIndex) . $dataRow;
        $sheet->setCellValue($cell, $employee->department ?? 'N/A');
        $colIndex++;
        
        // Has Ratings?
        $cell = $this->getExcelColumn($colIndex) . $dataRow;
        $sheet->setCellValue($cell, $hasRatings ? 'Yes' : 'No');
        if ($hasRatings) {
            $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FF00B050'); // Green
        } else {
            $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FFFF0000'); // Red
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }
        $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $colIndex++;
        
        $totalScore = 0;
        $quarterCount = 0;
        $hasAnyScore = false;
        
        foreach ($quarters as $q) {
            $selfScore = $this->getQuarterlyScore($employee, $q, $year, 'self');
            $managerScore = $this->getQuarterlyScore($employee, $q, $year, 'manager');
            
            // Self score - show empty if null
            $cell = $this->getExcelColumn($colIndex) . $dataRow;
            if ($selfScore !== null) {
                $sheet->setCellValue($cell, $selfScore);
                $sheet->getStyle($cell)->getNumberFormat()->setFormatCode('0"%"');
                $sheet->getStyle($cell)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB($this->getPerformanceBackgroundColor($selfScore));
                $sheet->getStyle($cell)->getFont()->getColor()->setARGB($this->getPerformanceColor($selfScore));
                $hasAnyScore = true;
            } else {
                $sheet->setCellValue($cell, 'N/A');
            }
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $colIndex++;
            
            // Manager score - show empty if null
            $cell = $this->getExcelColumn($colIndex) . $dataRow;
            if ($managerScore !== null) {
                $sheet->setCellValue($cell, $managerScore);
                $sheet->getStyle($cell)->getNumberFormat()->setFormatCode('0"%"');
                $sheet->getStyle($cell)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB($this->getPerformanceBackgroundColor($managerScore));
                $sheet->getStyle($cell)->getFont()->getColor()->setARGB($this->getPerformanceColor($managerScore));
                $totalScore += $managerScore;
                $quarterCount++;
                $hasAnyScore = true;
            } else {
                $sheet->setCellValue($cell, 'N/A');
            }
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $colIndex++;
        }
        
        // Calculate Average Rating (based ONLY on Manager % scores that exist)
        $avgScore = $quarterCount > 0 ? round($totalScore / $quarterCount, 0) : null;
        $bonusMultiplier = $avgScore !== null ? $this->calculateBonusMultiplier($avgScore) : null;
        $monthlySalary = $employee->monthly_salary ?? 0;
        $annualBonus = ($bonusMultiplier !== null && $monthlySalary > 0) ? $monthlySalary * $bonusMultiplier : 0;
        
        // Average Rating with color indicator
        $cell = $this->getExcelColumn($colIndex) . $dataRow;
        if ($avgScore !== null) {
            $sheet->setCellValue($cell, $avgScore);
            $sheet->getStyle($cell)->getNumberFormat()->setFormatCode('0"%"');
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getFont()->getColor()->setARGB($this->getPerformanceColor($avgScore));
            $sheet->getStyle($cell)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB($this->getPerformanceBackgroundColor($avgScore));
        } else {
            $sheet->setCellValue($cell, 'N/A');
        }
        $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $colIndex++;
        
        // Corresponding Bonus Multiplier with color indicator
        $cell = $this->getExcelColumn($colIndex) . $dataRow;
        if ($bonusMultiplier !== null) {
            $sheet->setCellValue($cell, $bonusMultiplier);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            if ($bonusMultiplier >= 2) {
                $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FF00B050'); // Green
            } elseif ($bonusMultiplier >= 1.5) {
                $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FF0070C0'); // Blue
            } elseif ($bonusMultiplier >= 1) {
                $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FFFFC000'); // Gold
            } elseif ($bonusMultiplier >= 0.5) {
                $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FFFF6600'); // Orange
            } else {
                $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FFFF0000'); // Red
            }
        } else {
            $sheet->setCellValue($cell, 'N/A');
        }
        $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $colIndex++;
        
        // Monthly Salary
        $cell = $this->getExcelColumn($colIndex) . $dataRow;
        if ($monthlySalary > 0) {
            $sheet->setCellValue($cell, $monthlySalary);
            $sheet->getStyle($cell)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        } else {
            $sheet->setCellValue($cell, '-');
        }
        $colIndex++;
        
        // Annual Bonus
        $cell = $this->getExcelColumn($colIndex) . $dataRow;
        if ($annualBonus > 0) {
            $sheet->setCellValue($cell, $annualBonus);
            $sheet->getStyle($cell)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FF00B050'); // Green
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        } else {
            $sheet->setCellValue($cell, '-');
        }
        $colIndex++;
        
        // Status
        $cell = $this->getExcelColumn($colIndex) . $dataRow;
        $status = 'No Ratings';
        if ($hasAnyScore) {
            $status = 'Has Ratings';
        } elseif ($hasRatings) {
            $status = 'Has Appraisals but No Scores';
        }
        $sheet->setCellValue($cell, $status);
        if ($status == 'Has Ratings') {
            $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FF00B050'); // Green
        } elseif ($status == 'Has Appraisals but No Scores') {
            $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FFFFC000'); // Gold
        } else {
            $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FFFF0000'); // Red
        }
        
        // Apply row background color
        $rowRange = 'A' . $dataRow . ':' . $lastColumnLetter . $dataRow;
        $sheet->getStyle($rowRange)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB($rowColor);
        
        // Apply borders to all cells in this row
        for ($c = 1; $c <= $totalColumns; $c++) {
            $cell = $this->getExcelColumn($c) . $dataRow;
            $sheet->getStyle($cell)->getBorders()->getOutline()
                ->setBorderStyle(Border::BORDER_THIN)
                ->setColor(new Color($borderColor));
        }
        
        $dataRow++;
        $rowNumber++;
    }
    
    // Add summary statistics section with styling
    $summaryRow = $dataRow + 2;
    $summaryData = $this->calculateSummaryData($employees, $year, $quarters);
    
    // Summary title
    $sheet->setCellValue('A' . $summaryRow, "YEAR {$year} - SYSTEM-WIDE SUMMARY STATISTICS" . $workstationText);
    $sheet->mergeCells('A' . $summaryRow . ':D' . $summaryRow);
    $sheet->getStyle('A' . $summaryRow)->getFont()->setBold(true)->setSize(12);
    $sheet->getStyle('A' . $summaryRow)->getFont()->getColor()->setARGB($primaryColor);
    
    // Summary data with styling
    $summaryStartRow = $summaryRow + 1;
    $summaryItems = [
        ['Total Users:', $summaryData['total_users']],
        ['Employees:', $summaryData['total_employees'] ?? 0],
        ['Supervisors:', $summaryData['total_supervisors'] ?? 0],
        ['Users with Ratings:', $summaryData['scored_users'] ?? 0],
        ['Users without Ratings:', $summaryData['users_without_ratings'] ?? 0],
        ['Ratings Coverage:', ($summaryData['ratings_coverage'] ?? 0) . '%'],
        ['Average Performance Score:', $summaryData['average_score'] > 0 ? $summaryData['average_score'] . '%' : '0%'],
        ['Avg Employee Score:', ($summaryData['avg_employee_score'] ?? 0) > 0 ? ($summaryData['avg_employee_score'] ?? 0) . '%' : '0%'],
        ['Avg Supervisor Score:', ($summaryData['avg_supervisor_score'] ?? 0) > 0 ? ($summaryData['avg_supervisor_score'] ?? 0) . '%' : '0%'],
        ['Top Performers (≥80%):', $summaryData['top_performers']],
        ['Total Bonus Pool:', number_format($summaryData['total_bonus_pool'], 2)],
        ['Average Monthly Salary:', number_format($summaryData['avg_salary'], 2)],
    ];
    
    // Add workstation-specific summary items
    if ($workstation === 'HQ') {
        $summaryItems[] = ['HQ Users:', $summaryData['workstation_stats']['hq']['count'] ?? 0];
        $summaryItems[] = ['HQ Ratings Coverage:', ($summaryData['workstation_stats']['hq']['ratings_percentage'] ?? 0) . '%'];
        $summaryItems[] = ['HQ Avg Score:', ($summaryData['workstation_stats']['hq']['avg_score'] ?? 0) . '%'];
        
        // Add HQ department breakdown
        if (!empty($summaryData['hq_department_stats'])) {
            $summaryRow2 = $summaryStartRow + count($summaryItems) + 2;
            $sheet->setCellValue('A' . $summaryRow2, "HQ DEPARTMENT BREAKDOWN");
            $sheet->mergeCells('A' . $summaryRow2 . ':C' . $summaryRow2);
            $sheet->getStyle('A' . $summaryRow2)->getFont()->setBold(true);
            
            $deptRow = $summaryRow2 + 1;
            foreach ($summaryData['hq_department_stats'] as $dept => $stats) {
                $sheet->setCellValue('A' . $deptRow, ucwords(str_replace('_', ' ', $dept)));
                $sheet->setCellValue('B' . $deptRow, $stats['count'] . ' users');
                $sheet->setCellValue('C' . $deptRow, $stats['ratings_percentage'] . '% rated');
                if ($stats['avg_score'] > 0) {
                    $sheet->setCellValue('D' . $deptRow, 'Avg: ' . $stats['avg_score'] . '%');
                }
                $deptRow++;
            }
        }
        
    } elseif ($workstation && $workstation !== 'HQ' && $workstation !== 'all' && $workstation !== '') {
        // Specific toll plaza selected
        $summaryItems[] = ['Plaza Users:', $summaryData['workstation_stats']['toll_plaza']['count'] ?? 0];
        $summaryItems[] = ['Plaza Ratings Coverage:', ($summaryData['workstation_stats']['toll_plaza']['ratings_percentage'] ?? 0) . '%'];
        $summaryItems[] = ['Plaza Avg Score:', ($summaryData['workstation_stats']['toll_plaza']['avg_score'] ?? 0) . '%'];
    } elseif (!$workstation || $workstation === 'all') {
        // Show both HQ and Toll Plaza stats when no filter is applied
        $summaryItems[] = ['HQ Users:', $summaryData['workstation_stats']['hq']['count'] ?? 0];
        $summaryItems[] = ['HQ Ratings Coverage:', ($summaryData['workstation_stats']['hq']['ratings_percentage'] ?? 0) . '%'];
        $summaryItems[] = ['HQ Avg Score:', ($summaryData['workstation_stats']['hq']['avg_score'] ?? 0) . '%'];
        $summaryItems[] = ['Toll Plaza Users:', $summaryData['workstation_stats']['toll_plaza']['count'] ?? 0];
        $summaryItems[] = ['Toll Plaza Ratings Coverage:', ($summaryData['workstation_stats']['toll_plaza']['ratings_percentage'] ?? 0) . '%'];
        $summaryItems[] = ['Toll Plaza Avg Score:', ($summaryData['workstation_stats']['toll_plaza']['avg_score'] ?? 0) . '%'];
    }
    
    $currentRow = $summaryStartRow;
    foreach ($summaryItems as $index => $item) {
        $sheet->setCellValue('A' . $currentRow, $item[0]);
        $sheet->setCellValue('B' . $currentRow, $item[1]);
        
        // Style labels
        $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
        
        // Alternate row background for summary
        if ($index % 2 == 0) {
            $sheet->getStyle('A' . $currentRow . ':B' . $currentRow)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB($evenRowColor);
        }
        
        $currentRow++;
    }
    
    // ========== NEW: DISTRIBUTION COMPLIANCE SECTION ==========
    $distComplianceRow = $currentRow + 3;
    
    // Get distribution by workstation for compliance analysis
    $distributionByWorkstation = $this->getDistributionByWorkstation($employees, $year);
    
    if (!empty($distributionByWorkstation)) {
        $sheet->setCellValue('A' . $distComplianceRow, "DISTRIBUTION COMPLIANCE BY WORKSTATION");
        $sheet->mergeCells('A' . $distComplianceRow . ':F' . $distComplianceRow);
        $sheet->getStyle('A' . $distComplianceRow)->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A' . $distComplianceRow)->getFont()->getColor()->setARGB($secondaryColor);
        $sheet->getStyle('A' . $distComplianceRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $distComplianceRow++;
        
        $sheet->setCellValue('A' . $distComplianceRow, "Policy Note: Distribution caps are calculated per Workstation/Team based on the following percentages:");
        $sheet->mergeCells('A' . $distComplianceRow . ':F' . $distComplianceRow);
        $sheet->getStyle('A' . $distComplianceRow)->getFont()->setItalic(true)->setSize(10);
        $distComplianceRow++;
        
        $sheet->setCellValue('A' . $distComplianceRow, "Rating 5 (Outstanding): ≤ 10% | Rating 4 (Excellent): ≤ 20% | Rating 3 (Competent): ≤ 40% | Rating 2 (Below Average): ≤ 20% | Rating 1 (Unsatisfactory): ≤ 10%");
        $sheet->mergeCells('A' . $distComplianceRow . ':F' . $distComplianceRow);
        $sheet->getStyle('A' . $distComplianceRow)->getFont()->setItalic(true)->setSize(9);
        $distComplianceRow += 2;
        
        foreach ($distributionByWorkstation as $workstationName => $distData) {
            // Workstation header
            $sheet->setCellValue('A' . $distComplianceRow, $workstationName);
            $sheet->mergeCells('A' . $distComplianceRow . ':F' . $distComplianceRow);
            $sheet->getStyle('A' . $distComplianceRow)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $distComplianceRow)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFE6F0FF');
            $distComplianceRow++;
            
            // Team size info
            $sheet->setCellValue('A' . $distComplianceRow, "Team Size: " . $distData['team_size'] . " employees");
            $sheet->mergeCells('A' . $distComplianceRow . ':F' . $distComplianceRow);
            $sheet->getStyle('A' . $distComplianceRow)->getFont()->setBold(true);
            $distComplianceRow++;
            
            // Headers for distribution table
            $headers = ['Rating', 'Description', 'Policy Cap', 'Max Allowed', 'Current Count', 'Status'];
            $col = 'A';
            foreach ($headers as $header) {
                $cell = $col . $distComplianceRow;
                $sheet->setCellValue($cell, $header);
                $sheet->getStyle($cell)->getFont()->setBold(true);
                $sheet->getStyle($cell)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB($headerBgColor);
                $sheet->getStyle($cell)->getFont()->getColor()->setARGB($headerTextColor);
                $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $col++;
            }
            $distComplianceRow++;
            
            // Distribution data
            $ratings = [
                5 => 'Outstanding',
                4 => 'Excellent',
                3 => 'Competent',
                2 => 'Below Average',
                1 => 'Unsatisfactory'
            ];
            
            foreach ($ratings as $rating => $name) {
                $limit = $distData['limits'][$rating];
                $current = $distData['ratings_count'][$rating] ?? 0;
                $compliance = $distData['compliance']['compliance'][$rating] ?? null;
                $isCompliant = $compliance ? $compliance['compliant'] : true;
                $excess = $compliance ? $compliance['excess'] : 0;
                
                $col = 'A';
                // Rating with badge style
                $cell = $col . $distComplianceRow;
                $ratingText = $rating . ' - ' . $name;
                $sheet->setCellValue($cell, $ratingText);
                if ($rating == 5) {
                    $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FF00B050');
                } elseif ($rating == 4) {
                    $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FF0070C0');
                } elseif ($rating == 3) {
                    $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FFFFC000');
                } elseif ($rating == 2) {
                    $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FFFF6600');
                } else {
                    $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FF6B7280');
                }
                $sheet->getStyle($cell)->getFont()->setBold(true);
                $col++;
                
                // Description
                $cell = $col . $distComplianceRow;
                $sheet->setCellValue($cell, $name);
                $col++;
                
                // Policy Cap
                $cell = $col . $distComplianceRow;
                $sheet->setCellValue($cell, "≤ " . $limit['cap_percentage'] . "%");
                $col++;
                
                // Max Allowed
                $cell = $col . $distComplianceRow;
                $sheet->setCellValue($cell, $limit['max']);
                $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $col++;
                
                // Current Count
                $cell = $col . $distComplianceRow;
                $sheet->setCellValue($cell, $current);
                $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                if (!$isCompliant && $current > 0) {
                    $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FFFF0000');
                    $sheet->getStyle($cell)->getFont()->setBold(true);
                } elseif ($current > 0) {
                    $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FF00B050');
                }
                $col++;
                
                // Status
                $cell = $col . $distComplianceRow;
                if ($isCompliant) {
                    if ($current > 0) {
                        $sheet->setCellValue($cell, "Compliant");
                        $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FF00B050');
                    } else {
                        $sheet->setCellValue($cell, "No ratings");
                        $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FF808080');
                    }
                } else {
                    $sheet->setCellValue($cell, "Exceeds by " . $excess);
                    $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FFFF0000');
                    $sheet->getStyle($cell)->getFont()->setBold(true);
                }
                $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                $distComplianceRow++;
            }
            
            // Compliance summary
            $distComplianceRow++;
            if ($distData['compliance']['is_compliant']) {
                $sheet->setCellValue('A' . $distComplianceRow, "✓ Distribution is compliant - All ratings are within the policy caps.");
                $sheet->mergeCells('A' . $distComplianceRow . ':F' . $distComplianceRow);
                $sheet->getStyle('A' . $distComplianceRow)->getFont()->getColor()->setARGB('FF00B050');
                $sheet->getStyle('A' . $distComplianceRow)->getFont()->setBold(true);
            } else {
                $sheet->setCellValue('A' . $distComplianceRow, "⚠ Distribution Exceeds Caps");
                $sheet->mergeCells('A' . $distComplianceRow . ':F' . $distComplianceRow);
                $sheet->getStyle('A' . $distComplianceRow)->getFont()->getColor()->setARGB('FFFF0000');
                $sheet->getStyle('A' . $distComplianceRow)->getFont()->setBold(true);
                $distComplianceRow++;
                
                $sheet->setCellValue('A' . $distComplianceRow, "Recommendations:");
                $sheet->mergeCells('A' . $distComplianceRow . ':F' . $distComplianceRow);
                $sheet->getStyle('A' . $distComplianceRow)->getFont()->setBold(true);
                $distComplianceRow++;
                
                foreach ($distData['recommendations']['recommendations'] as $recommendation) {
                    $sheet->setCellValue('A' . $distComplianceRow, "• " . $recommendation);
                    $sheet->mergeCells('A' . $distComplianceRow . ':F' . $distComplianceRow);
                    $sheet->getStyle('A' . $distComplianceRow)->getFont()->setItalic(true);
                    $distComplianceRow++;
                }
            }
            
            $distComplianceRow += 2; // Add spacing between workstations
        }
    } else {
        $sheet->setCellValue('A' . $distComplianceRow, "DISTRIBUTION COMPLIANCE");
        $sheet->mergeCells('A' . $distComplianceRow . ':F' . $distComplianceRow);
        $sheet->getStyle('A' . $distComplianceRow)->getFont()->setBold(true)->setSize(12);
        $distComplianceRow++;
        
        $sheet->setCellValue('A' . $distComplianceRow, "No rating data available for distribution analysis. Complete appraisals to see distribution statistics.");
        $sheet->mergeCells('A' . $distComplianceRow . ':F' . $distComplianceRow);
        $sheet->getStyle('A' . $distComplianceRow)->getFont()->setItalic(true);
        $sheet->getStyle('A' . $distComplianceRow)->getFont()->getColor()->setARGB('FF808080');
        $distComplianceRow += 2;
    }
    
    // Performance Distribution section
    $distRow = $distComplianceRow + 1;
    $sheet->setCellValue('A' . $distRow, "PERFORMANCE DISTRIBUTION - YEAR {$year}");
    $sheet->mergeCells('A' . $distRow . ':C' . $distRow);
    $sheet->getStyle('A' . $distRow)->getFont()->setBold(true)->setSize(12);
    $sheet->getStyle('A' . $distRow)->getFont()->getColor()->setARGB($secondaryColor);
    
    $distRow += 1;
    $distHeaders = ['Rating Category', 'Count', 'Percentage'];
    $col = 'A';
    foreach ($distHeaders as $header) {
        $cell = $col . $distRow;
        $sheet->setCellValue($cell, $header);
        $sheet->getStyle($cell)->getFont()->setBold(true);
        $sheet->getStyle($cell)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB($headerBgColor);
        $sheet->getStyle($cell)->getFont()->getColor()->setARGB($headerTextColor);
        $col++;
    }
    
    $distRow++;
    $distData = [
        ['Excellent (90-100%)', $summaryData['distribution']['excellent'] ?? 0, ($summaryData['distribution']['excellent_pct'] ?? 0) . '%'],
        ['Good (80-89%)', $summaryData['distribution']['good'] ?? 0, ($summaryData['distribution']['good_pct'] ?? 0) . '%'],
        ['Satisfactory (70-79%)', $summaryData['distribution']['satisfactory'] ?? 0, ($summaryData['distribution']['satisfactory_pct'] ?? 0) . '%'],
        ['Needs Improvement (60-69%)', $summaryData['distribution']['needs_improvement'] ?? 0, ($summaryData['distribution']['needs_improvement_pct'] ?? 0) . '%'],
        ['Unsatisfactory (<60%)', $summaryData['distribution']['unsatisfactory'] ?? 0, ($summaryData['distribution']['unsatisfactory_pct'] ?? 0) . '%'],
        ['No Ratings', $summaryData['distribution']['no_ratings'] ?? 0, ($summaryData['distribution']['no_ratings_pct'] ?? 0) . '%'],
    ];
    
    foreach ($distData as $index => $dist) {
        $sheet->setCellValue('A' . $distRow, $dist[0]);
        $sheet->setCellValue('B' . $distRow, $dist[1]);
        $sheet->setCellValue('C' . $distRow, $dist[2]);
        
        // Color code the rows based on category
        $bgColor = $evenRowColor;
        if ($index == 0) $bgColor = 'FFE6F0E6'; // Light green for Excellent
        else if ($index == 1) $bgColor = 'FFE6F0FF'; // Light blue for Good
        else if ($index == 2) $bgColor = 'FFFFF0E6'; // Light orange for Satisfactory
        else if ($index == 3) $bgColor = 'FFFFE6E6'; // Light red-orange for Needs Improvement
        else if ($index == 4) $bgColor = 'FFFFE6E6'; // Light red for Unsatisfactory
        else if ($index == 5) $bgColor = 'FFFFF0E0'; // Light peach for No Ratings
        
        $sheet->getStyle('A' . $distRow . ':C' . $distRow)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB($bgColor);
        
        $distRow++;
    }
    
    // Add year footer
    $footerRow = $distRow + 2;
    $sheet->setCellValue('A' . $footerRow, "© " . date('Y') . " MOIC Performance Appraisal System - Report for Year {$year}");
    $sheet->mergeCells('A' . $footerRow . ':C' . $footerRow);
    $sheet->getStyle('A' . $footerRow)->getFont()->setItalic(true)->setSize(10);
    $sheet->getStyle('A' . $footerRow)->getFont()->getColor()->setARGB('FF808080');
    
    // Auto-size columns for better readability
    foreach (range('A', $lastColumnLetter) as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
    
    // Freeze the header row
    $sheet->freezePane('A7');
    
    // Set print area to include all data
    $sheet->getPageSetup()->setPrintArea('A1:' . $lastColumnLetter . ($footerRow));
    
    // Set paper size and orientation
    $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
    $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
    
    // Set print scale to fit all columns on one page
    $sheet->getPageSetup()->setFitToWidth(1);
    $sheet->getPageSetup()->setFitToHeight(0);
    
    // Create response with filename including year and filters
    $typeText = ($employeeType !== 'all') ? '-' . $employeeType : '';
    $quarterText = ($quarter !== 'all') ? $quarter : 'full-year';
    $workstationText = '';
    if ($workstation && $workstation !== '') {
        $workstationText = '-' . str_replace(' ', '-', strtolower($workstation));
    }
    
    $filename = "performance-summary-{$year}{$typeText}{$workstationText}-{$quarterText}.xlsx";
    
    $writer = new Xlsx($spreadsheet);
    
    $response = response()->streamDownload(function() use ($writer) {
        $writer->save('php://output');
    }, $filename);
    
    $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $response->headers->set('Cache-Control', 'max-age=0');
    
    return $response;
}
    
    /**
     * Generate complete system report with all appraisals
     * Now uses a single Workstation column
     */
    private function generateCompleteSystemReport($employees, $year, $quarter, $user, $employeeType, $appraisalStats, $workstation = null, $hqDepartment = null)
    {
        $spreadsheet = new Spreadsheet();
        
        // Sheet 1: Performance Summary
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Performance Summary');
        
        // Define colors
        $primaryColor = 'FF110484';
        $secondaryColor = 'FFE7581C';
        $headerBgColor = 'FF110484';
        $headerTextColor = 'FFFFFFFF';
        $evenRowColor = 'FFF5F5F5';
        $noRatingsColor = 'FFFFF0E0';
        
        // Build workstation text for title
        $workstationText = '';
        if ($workstation && $workstation !== '') {
            $workstationText = ' - WORKSTATION: ' . $workstation;
        }
        
        // Add title
        $sheet->setCellValue('A1', "COMPLETE SYSTEM PERFORMANCE REPORT - {$year}{$workstationText}");
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getFont()->getColor()->setARGB($primaryColor);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A2', 'Period: ' . $year . ($quarter !== 'all' ? ' - ' . $quarter : ' - Full Year'));
        $sheet->mergeCells('A2:H2');
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('A2')->getFont()->getColor()->setARGB($secondaryColor);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A3', 'Generated: ' . now()->format('F j, Y H:i:s'));
        $sheet->mergeCells('A3:H3');
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // System Statistics
        $sheet->setCellValue('A5', "SYSTEM STATISTICS - {$year}");
        $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(12);
        
        $stats = [
            ['Total Users:', $employees->count()],
            ['Users with Ratings:', $appraisalStats['users_with_ratings'] ?? 0],
            ['Users without Ratings:', $appraisalStats['users_without_ratings'] ?? 0],
            ['Total Appraisals:', $appraisalStats['total_appraisals']],
            ['Submitted Appraisals:', $appraisalStats['submitted']],
            ['Approved Appraisals:', $appraisalStats['approved']],
            ['Draft Appraisals:', $appraisalStats['draft']],
        ];
        
        $row = 6;
        foreach ($stats as $index => $stat) {
            $sheet->setCellValue('A' . $row, $stat[0]);
            $sheet->setCellValue('B' . $row, $stat[1]);
            
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            
            if ($index % 2 == 0) {
                $sheet->getStyle('A' . $row . ':B' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB($evenRowColor);
            }
            
            $row++;
        }
        
        // User list
        $row += 2;
        $sheet->setCellValue('A' . $row, "COMPLETE USER LIST - {$year}{$workstationText}");
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
        $row += 2;
        
        // Headers with SINGLE WORKSTATION column
        $headers = ['User Type', 'Workstation', 'Name', 'Employee No.', 'Department', 'Position', 'Has Ratings?', 'Status', 'Appraisal Count'];
        
        $col = 'A';
        foreach ($headers as $header) {
            $cell = $col . $row;
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB($headerBgColor);
            $sheet->getStyle($cell)->getFont()->getColor()->setARGB($headerTextColor);
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $col++;
        }
        
        $row++;
        $rowNumber = 0;
        foreach ($employees as $employee) {
            $col = 'A';
            $isEven = $rowNumber % 2 == 0;
            $rowColor = $isEven ? $evenRowColor : 'FFFFFFFF';
            
            $hasRatings = $employee->appraisals->isNotEmpty();
            $appraisalCount = $employee->appraisals->count();
            
            // Special background for users without ratings
            if (!$hasRatings) {
                $rowColor = $noRatingsColor;
            }
            
            // Workstation display
            $workstationDisplay = $this->getWorkstationDisplay($employee);
            
            $sheet->setCellValue($col++ . $row, ucfirst($employee->user_type ?? 'Employee'));
            $sheet->setCellValue($col++ . $row, $workstationDisplay);
            $sheet->setCellValue($col++ . $row, $employee->name);
            $sheet->setCellValue($col++ . $row, $employee->employee_number);
            $sheet->setCellValue($col++ . $row, $employee->department ?? 'N/A');
            $sheet->setCellValue($col++ . $row, $employee->job_title ?? 'N/A');
            
            $cell = $col++ . $row;
            $sheet->setCellValue($cell, $hasRatings ? 'Yes' : 'No');
            if ($hasRatings) {
                $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FF00B050'); // Green
            } else {
                $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FFFF0000'); // Red
                $sheet->getStyle($cell)->getFont()->setBold(true);
            }
            
            $sheet->setCellValue($col++ . $row, $employee->is_onboarded ? 'Active' : 'Inactive');
            $sheet->setCellValue($col++ . $row, $appraisalCount);
            
            $sheet->getStyle('A' . $row . ':' . $col . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB($rowColor);
            
            $row++;
            $rowNumber++;
        }
        
        // Auto-size columns
        $lastCol = 'J'; // Based on header count (10 columns)
        foreach (range('A', $lastCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Freeze header
        $sheet->freezePane('A9');
        
        // Create response with filename including filters
        $workstationText = '';
        if ($workstation && $workstation !== '') {
            $workstationText = '-' . str_replace(' ', '-', strtolower($workstation));
        }
        
        $filename = "complete-system-report-{$year}{$workstationText}-" . ($quarter !== 'all' ? $quarter : 'full-year') . ".xlsx";
        
        $writer = new Xlsx($spreadsheet);
        
        $response = response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename);
        
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Cache-Control', 'max-age=0');
        
        return $response;
    }
    
    /**
     * Generate user list with appraisal status Excel
     * Shows all users and whether they have ratings - SINGLE WORKSTATION COLUMN
     */
    private function generateUserListExcel($users, $year, $currentUser, $workstation = null, $hqDepartment = null)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('User List with Ratings');
        
        // Define colors
        $primaryColor = 'FF110484';
        $secondaryColor = 'FFE7581C';
        $headerBgColor = 'FF110484';
        $headerTextColor = 'FFFFFFFF';
        $evenRowColor = 'FFF5F5F5';
        $withRatingsColor = 'FFE6F0E6'; // Light green
        $withoutRatingsColor = 'FFFFF0E0'; // Light peach
        
        // Build workstation text for title
        $workstationText = '';
        if ($workstation && $workstation !== '') {
            $workstationText = ' - WORKSTATION: ' . $workstation;
        }
        
        // Title
        $sheet->setCellValue('A1', "USER LIST WITH APPRAISAL STATUS - {$year}{$workstationText}");
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getFont()->getColor()->setARGB($primaryColor);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A2', 'Generated: ' . now()->format('F j, Y H:i:s'));
        $sheet->mergeCells('A2:I2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A3', 'Total Users: ' . $users->count());
        $sheet->mergeCells('A3:I3');
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Summary statistics
        $row = 5;
        $withRatings = $users->where('has_ratings', true)->count();
        $withoutRatings = $users->where('has_ratings', false)->count();
        
        $sheet->setCellValue('A' . $row, 'Summary:');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        $sheet->setCellValue('A' . $row, 'Users with Ratings:');
        $sheet->setCellValue('B' . $row, $withRatings);
        $sheet->getStyle('B' . $row)->getFont()->getColor()->setARGB('FF00B050');
        $row++;
        $sheet->setCellValue('A' . $row, 'Users without Ratings:');
        $sheet->setCellValue('B' . $row, $withoutRatings);
        $sheet->getStyle('B' . $row)->getFont()->getColor()->setARGB('FFFF0000');
        $row += 2;
        
        // Headers with SINGLE WORKSTATION column
        $headers = [
            'User Type', 'Workstation', 'Name', 'Employee No.', 'Department', 'Position',
            'Has Ratings?', 'Appraisal Count', 'Latest Appraisal', 'Status'
        ];
        
        $col = 'A';
        foreach ($headers as $header) {
            $cell = $col . $row;
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB($headerBgColor);
            $sheet->getStyle($cell)->getFont()->getColor()->setARGB($headerTextColor);
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $col++;
        }
        
        // Data
        $row++;
        $rowNumber = 0;
        foreach ($users as $user) {
            $col = 'A';
            $isEven = $rowNumber % 2 == 0;
            $rowColor = $isEven ? $evenRowColor : 'FFFFFFFF';
            
            // Color based on ratings status
            if ($user->has_ratings) {
                $rowColor = $withRatingsColor;
            } else {
                $rowColor = $withoutRatingsColor;
            }
            
            // Workstation display
            $workstationDisplay = $this->getWorkstationDisplay($user);
            
            $sheet->setCellValue($col++ . $row, ucfirst($user->user_type ?? 'Employee'));
            $sheet->setCellValue($col++ . $row, $workstationDisplay);
            $sheet->setCellValue($col++ . $row, $user->name);
            $sheet->setCellValue($col++ . $row, $user->employee_number);
            $sheet->setCellValue($col++ . $row, $user->department ?? 'N/A');
            $sheet->setCellValue($col++ . $row, $user->job_title ?? 'N/A');
            
            $cell = $col++ . $row;
            $sheet->setCellValue($cell, $user->has_ratings ? 'Yes' : 'No');
            if ($user->has_ratings) {
                $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FF00B050');
            } else {
                $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FFFF0000');
                $sheet->getStyle($cell)->getFont()->setBold(true);
            }
            
            $sheet->setCellValue($col++ . $row, $user->appraisal_count);
            
            $latestDate = $user->latest_appraisal ? $user->latest_appraisal->created_at->format('Y-m-d') : 'N/A';
            $sheet->setCellValue($col++ . $row, $latestDate);
            
            $status = $user->is_onboarded ? 'Active' : 'Inactive';
            $sheet->setCellValue($col++ . $row, $status);
            
            $sheet->getStyle('A' . $row . ':' . $col . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB($rowColor);
            
            $row++;
            $rowNumber++;
        }
        
        // Auto-size columns
        $lastCol = 'J'; // Based on header count (10 columns)
        foreach (range('A', $lastCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Freeze header
        $sheet->freezePane('A8');
        
        // Create filename with filters
        $workstationText = '';
        if ($workstation && $workstation !== '') {
            $workstationText = '-' . str_replace(' ', '-', strtolower($workstation));
        }
        
        $filename = "user-list-with-ratings-{$year}{$workstationText}.xlsx";
        
        $writer = new Xlsx($spreadsheet);
        
        $response = response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename);
        
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Cache-Control', 'max-age=0');
        
        return $response;
    }
    
    /**
     * Generate raw appraisals Excel
     */
    private function generateRawAppraisalsExcel($appraisals, $year, $quarter, $user)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('All Appraisals');
        
        // Define colors
        $primaryColor = 'FF110484';
        $secondaryColor = 'FFE7581C';
        $headerBgColor = 'FF110484';
        $headerTextColor = 'FFFFFFFF';
        $evenRowColor = 'FFF5F5F5';
        
        // Title
        $sheet->setCellValue('A1', "ALL APPRAISALS RAW DATA - {$year}");
        $sheet->mergeCells('A1:N1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getFont()->getColor()->setARGB($primaryColor);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A2', 'Period: ' . $year . ($quarter !== 'all' ? ' - ' . $quarter : ' - Full Year'));
        $sheet->mergeCells('A2:N2');
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('A2')->getFont()->getColor()->setARGB($secondaryColor);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A3', 'Total Appraisals: ' . $appraisals->count());
        $sheet->mergeCells('A3:N3');
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Headers with SINGLE WORKSTATION column
        $row = 5;
        $headers = [
            'ID', 'Employee Name', 'Employee No.', 'User Type', 'Workstation', 'Department', 'Position',
            'Period', 'Status', 'Self Score', 'Supervisor Score', 'Final Score',
            'Submitted At', 'Approved At', 'Created At'
        ];
        
        $col = 'A';
        foreach ($headers as $header) {
            $cell = $col . $row;
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB($headerBgColor);
            $sheet->getStyle($cell)->getFont()->getColor()->setARGB($headerTextColor);
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $col++;
        }
        
        // Data
        $row++;
        $rowNumber = 0;
        foreach ($appraisals as $appraisal) {
            $col = 'A';
            $isEven = $rowNumber % 2 == 0;
            $rowColor = $isEven ? $evenRowColor : 'FFFFFFFF';
            
            $employee = $appraisal->user;
            
            // Workstation display
            $workstationDisplay = $this->getWorkstationDisplay($employee);
            
            $sheet->setCellValue($col++ . $row, $appraisal->id);
            $sheet->setCellValue($col++ . $row, $employee->name ?? 'N/A');
            $sheet->setCellValue($col++ . $row, $appraisal->employee_number);
            $sheet->setCellValue($col++ . $row, ucfirst($employee->user_type ?? 'Employee'));
            $sheet->setCellValue($col++ . $row, $workstationDisplay);
            $sheet->setCellValue($col++ . $row, $employee->department ?? 'N/A');
            $sheet->setCellValue($col++ . $row, $employee->job_title ?? 'N/A');
            $sheet->setCellValue($col++ . $row, $appraisal->period);
            $sheet->setCellValue($col++ . $row, ucfirst($appraisal->status));
            
            // Color code scores
            $selfScore = $appraisal->self_score ?? 0;
            $cell = $col++ . $row;
            $sheet->setCellValue($cell, $selfScore);
            if ($selfScore > 0) {
                $sheet->getStyle($cell)->getFont()->getColor()->setARGB($this->getPerformanceColor($selfScore));
            }
            
            $supervisorScore = $appraisal->supervisor_score ?? 0;
            $cell = $col++ . $row;
            $sheet->setCellValue($cell, $supervisorScore);
            if ($supervisorScore > 0) {
                $sheet->getStyle($cell)->getFont()->getColor()->setARGB($this->getPerformanceColor($supervisorScore));
            }
            
            $finalScore = $appraisal->final_score ?? 0;
            $cell = $col++ . $row;
            $sheet->setCellValue($cell, $finalScore);
            if ($finalScore > 0) {
                $sheet->getStyle($cell)->getFont()->setBold(true);
                $sheet->getStyle($cell)->getFont()->getColor()->setARGB($this->getPerformanceColor($finalScore));
            }
            
            $sheet->setCellValue($col++ . $row, $appraisal->submitted_at ? $appraisal->submitted_at->format('Y-m-d H:i') : 'N/A');
            $sheet->setCellValue($col++ . $row, $appraisal->approved_at ? $appraisal->approved_at->format('Y-m-d H:i') : 'N/A');
            $sheet->setCellValue($col++ . $row, $appraisal->created_at->format('Y-m-d H:i'));
            
            $sheet->getStyle('A' . $row . ':' . $col . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB($rowColor);
            
            $row++;
            $rowNumber++;
        }
        
        // Auto-size columns
        $lastCol = 'O'; // Based on header count (15 columns)
        foreach (range('A', $lastCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Freeze header
        $sheet->freezePane('A6');
        
        $filename = "all-appraisals-raw-{$year}-" . ($quarter !== 'all' ? $quarter : 'full-year') . ".xlsx";
        
        $writer = new Xlsx($spreadsheet);
        
        $response = response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename);
        
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Cache-Control', 'max-age=0');
        
        return $response;
    }
    
    /**
     * Get Excel column letter from index
     */
    private function getExcelColumn($index)
    {
        $letters = range('A', 'Z');
        if ($index <= 26) {
            return $letters[$index - 1];
        }
        $first = floor(($index - 1) / 26);
        $second = ($index - 1) % 26;
        return $letters[$first - 1] . $letters[$second];
    }
    
    /**
     * Get total column count
     */
    private function getColumnCount($quarter)
    {
        $quarterCount = $quarter !== 'all' ? 1 : 4;
        return 7 + ($quarterCount * 2) + 4; // User type + Workstation + Employee fields + Has Ratings + quarter columns + summary fields
    }
   


/**
 * Adjust an employee's rating
 */
public function adjustRating(Request $request)
{
    $user = auth()->user();
    
    // Check permissions
    if ($user->user_type !== 'supervisor' && !$user->is_admin) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    
    $request->validate([
        'employee_number' => 'required|string',
        'quarter' => 'required|string',
        'year' => 'required|integer',
        'new_rating' => 'required|integer|in:1,2,3,4,5',
        'reason' => 'required|string|min:10'
    ]);
    
    try {
        DB::beginTransaction();
        
        // Find the employee
        $employee = User::where('employee_number', $request->employee_number)
            ->where('is_onboarded', true)
            ->firstOrFail();
        
        // Get the appraisal for the specified quarter
        $dates = $this->getQuarterDates($request->quarter, $request->year);
        
        $appraisal = Appraisal::where('employee_number', $request->employee_number)
            ->where(function($query) use ($dates) {
                $query->whereBetween('created_at', [$dates['start'], $dates['end']])
                    ->orWhereBetween('start_date', [$dates['start'], $dates['end']])
                    ->orWhereBetween('updated_at', [$dates['start'], $dates['end']]);
            })
            ->first();
        
        if (!$appraisal) {
            return response()->json(['error' => 'No appraisal found for this period'], 404);
        }
        
        // Calculate the required score for the new rating
        $targetScore = $this->getScoreForRating($request->new_rating);
        
        // Get current score
        $currentScore = $this->calculateManagerScore($appraisal);
        
        // Determine which KPAs need adjustment
        $kpas = $appraisal->kpas;
        $adjustmentsNeeded = [];
        
        // Calculate the difference needed
        $scoreDifference = $targetScore - $currentScore;
        
        // Get all KPAs that can be adjusted
        $adjustableKpas = [];
        foreach ($kpas as $kpa) {
            $currentRating = $kpa->supervisor_rating ?? 0;
            $maxRating = $kpa->kpi ?? 4;
            $weight = $kpa->weight ?? 0;
            
            if ($currentRating < $maxRating) {
                $potentialIncrease = (($maxRating - $currentRating) / $maxRating) * 100;
                $weightedImpact = ($potentialIncrease * $weight) / 100;
                $adjustableKpas[] = [
                    'kpa' => $kpa,
                    'current' => $currentRating,
                    'max' => $maxRating,
                    'weight' => $weight,
                    'potential_increase' => $potentialIncrease,
                    'weighted_impact' => $weightedImpact
                ];
            }
        }
        
        // Sort by weighted impact (smallest adjustments first for precision)
        usort($adjustableKpas, function($a, $b) {
            return $a['weighted_impact'] <=> $b['weighted_impact'];
        });
        
        // Apply adjustments to reach target score
        $remainingDifference = $scoreDifference;
        $adjustmentsMade = [];
        
        foreach ($adjustableKpas as $item) {
            if ($remainingDifference <= 0) break;
            
            $maxIncrease = $item['potential_increase'];
            $neededIncrease = min($maxIncrease, $remainingDifference);
            $newRatingValue = $item['current'] + ceil(($neededIncrease / 100) * $item['max']);
            $newRatingValue = min($newRatingValue, $item['max']);
            
            // Calculate actual percentage increase
            $actualIncrease = (($newRatingValue - $item['current']) / $item['max']) * 100;
            $weightedActualIncrease = ($actualIncrease * $item['weight']) / 100;
            
            $adjustmentsMade[] = [
                'kpa_id' => $item['kpa']->id,
                'kpa_name' => $item['kpa']->kpa_name,
                'old_rating' => $item['current'],
                'new_rating' => $newRatingValue,
                'impact' => $weightedActualIncrease
            ];
            
            $remainingDifference -= $weightedActualIncrease;
            
            // Update the KPA
            $item['kpa']->supervisor_rating = $newRatingValue;
            $item['kpa']->save();
        }
        
        // Recalculate the new score
        $newScore = $this->calculateManagerScore($appraisal);
        
        // Update the appraisal score
        $appraisal->supervisor_score = $newScore;
        $appraisal->final_score = $newScore;
        
        // Add adjustment note
        $note = "Rating adjusted from " . $this->getRatingFromScore($currentScore) . " to " . 
                $this->getRatingFromScore($newScore) . " (Target: {$request->new_rating}) on " . 
                date('Y-m-d H:i:s') . " by " . $user->name . ". Reason: " . $request->reason;
        
        // Save note (you'll need a notes field or use comments)
        // $appraisal->notes = ($appraisal->notes ? $appraisal->notes . "\n" : "") . $note;
        $appraisal->save();
        
        DB::commit();
        
        // Log the adjustment
        \Log::info('Rating adjustment', [
            'employee' => $employee->employee_number,
            'old_score' => $currentScore,
            'new_score' => $newScore,
            'old_rating' => $this->getRatingFromScore($currentScore),
            'new_rating' => $this->getRatingFromScore($newScore),
            'by' => $user->id,
            'reason' => $request->reason,
            'adjustments' => $adjustmentsMade
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Rating adjusted successfully',
            'old_score' => round($currentScore, 1),
            'new_score' => round($newScore, 1),
            'old_rating' => $this->getRatingFromScore($currentScore),
            'new_rating' => $this->getRatingFromScore($newScore),
            'adjustments' => $adjustmentsMade
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Rating adjustment failed', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Failed to adjust rating: ' . $e->getMessage()], 500);
    }
}

/**
 * Get the score range for a rating
 */
private function getScoreForRating($rating)
{
    switch($rating) {
        case 5: return 95; // Outstanding (90-100)
        case 4: return 85; // Excellent (80-89)
        case 3: return 75; // Competent (70-79)
        case 2: return 65; // Below Average (60-69)
        case 1: return 55; // Unsatisfactory (<60)
        default: return 75;
    }
}

/**
 * Get rating from score
 */
private function getRatingFromScore($score)
{
    if ($score >= 90) return 5;
    if ($score >= 80) return 4;
    if ($score >= 70) return 3;
    if ($score >= 60) return 2;
    return 1;
}

/**
 * Get outstanding employees for a specific workstation
 */
private function getOutstandingEmployees($employees, $year, $workstation = 'hq')
{
    $outstandingEmployees = [];
    
    foreach($employees as $emp) {
        if($emp->workstation_type === $workstation) {
            $totalScore = 0;
            $quarterCount = 0;
            $quarterScores = [];
            
            foreach(['Q1','Q2','Q3','Q4'] as $q) {
                $score = $this->getQuarterlyScore($emp, $q, $year, 'manager');
                if($score !== null) {
                    $totalScore += $score;
                    $quarterCount++;
                    $quarterScores[$q] = $score;
                }
            }
            
            if($quarterCount > 0) {
                $avgScore = $totalScore / $quarterCount;
                if($avgScore >= 90) { // Outstanding threshold
                    $outstandingEmployees[] = [
                        'employee_number' => $emp->employee_number,
                        'name' => $emp->name,
                        'position' => $emp->job_title,
                        'avg_score' => round($avgScore, 1),
                        'quarter_count' => $quarterCount,
                        'quarter_scores' => $quarterScores,
                        'workstation' => $this->getWorkstationDisplay($emp)
                    ];
                }
            }
        }
    }
    
    // Sort by average score descending
    usort($outstandingEmployees, function($a, $b) {
        return $b['avg_score'] <=> $a['avg_score'];
    });
    
    return $outstandingEmployees;
}



    
   


}