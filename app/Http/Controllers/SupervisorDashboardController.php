<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appraisal;
use App\Models\Appeal;
use App\Models\Rating;
use App\Models\Leave;  // Add this import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // Add this import
use Illuminate\Support\Facades\Log;  // Add this import
use Illuminate\Pagination\LengthAwarePaginator;  // Add this import
use Carbon\Carbon;  // Add this import

class SupervisorDashboardController extends Controller
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
   public function index()
{
    $user = auth()->user();
    
    // Check if user is supervisor
    if ($user->user_type !== 'supervisor') {
        return redirect()->route('dashboard')
            ->with('error', 'You do not have access to the supervisor dashboard.');
    }
    
    // Check if supervisor is onboarded
    if (!$user->is_onboarded) {
        return redirect()->route('onboarding.survey')
            ->with('warning', 'Please complete your profile setup first to access supervisor features.');
    }
    
    // Get supervisor's team
    $team = User::where('manager_id', $user->employee_number)
        ->where('user_type', 'employee')
        ->where('is_onboarded', true)
        ->get();
    
    // Get team employee numbers for queries
    $teamEmployeeNumbers = $team->pluck('employee_number')->toArray();
    
    // Initialize collections
    $pendingAppraisals = collect();
    $approvedAppraisals = collect();
    $pendingAppeals = collect();
    $approvedAppeals = collect();
    $pendingLeaves = collect();  // Add this
    $upcomingLeaves = collect(); // Add this
    
    // Only run queries if there are team members
    if (!empty($teamEmployeeNumbers)) {
        // Get pending appraisals for the team
        $pendingAppraisals = Appraisal::whereIn('employee_number', $teamEmployeeNumbers)
            ->where('status', 'submitted')
            ->orderBy('updated_at', 'desc')
            ->get();
        
        // Get approved appraisals for the team WITH final score calculation
        $approvedAppraisals = Appraisal::with('kpas')
            ->whereIn('employee_number', $teamEmployeeNumbers)
            ->where('status', 'approved')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($appraisal) {
                // Calculate final score from KPAs
                $totalSupervisorScore = 0;
                $totalWeight = 0;
                
                foreach ($appraisal->kpas as $kpa) {
                    $rating = $kpa->supervisor_rating ?? $kpa->self_rating;
                    $totalSupervisorScore += ($rating * $kpa->weight) / 100;
                    $totalWeight += $kpa->weight;
                }
                
                // Add calculated final_score as an attribute
                $appraisal->final_score_calculated = $totalWeight > 0 ? $totalSupervisorScore : 0;
                return $appraisal;
            });
        
        // Get pending appeals for the team
        $pendingAppeals = Appeal::whereIn('employee_number', $teamEmployeeNumbers)
            ->whereIn('status', ['pending', 'under_review'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get approved appeals for the team
        $approvedAppeals = Appeal::whereIn('employee_number', $teamEmployeeNumbers)
            ->where('status', 'approved')
            ->orderBy('updated_at', 'desc')
            ->get();
        
        // Get pending leaves for the team - ADD THIS SECTION
        $pendingLeaves = Leave::whereIn('employee_number', $teamEmployeeNumbers)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(10)  // Limit for dashboard display
            ->get();
        
        // Get upcoming approved leaves for the team - ADD THIS SECTION
        $upcomingLeaves = Leave::whereIn('employee_number', $teamEmployeeNumbers)
            ->where('status', 'approved')
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->limit(10)  // Limit for dashboard display
            ->get();
    }
    
    // Calculate average final score
    $avgScore = 0;
    if ($approvedAppraisals->count() > 0) {
        $totalScore = $approvedAppraisals->sum('final_score_calculated');
        $avgScore = $totalScore / $approvedAppraisals->count();
    }
    
    // Calculate leave statistics - ADD THIS SECTION
    $leaveStats = [
        'pending' => 0,
        'approved' => 0,
        'rejected' => 0,
        'avg_days' => 0,
    ];
    
    if (!empty($teamEmployeeNumbers)) {
        $leaveStats = $this->getLeaveStats($team);
    }
    
    // Stats
    $stats = [
        'team_size' => $team->count(),
        'pending_appraisals' => $pendingAppraisals->count(),
        'approved_appraisals' => $approvedAppraisals->count(),
        'pending_appeals' => $pendingAppeals->count(),
        'approved_appeals' => $approvedAppeals->count(),
        'avg_final_score' => $avgScore,
    ];

    return view('supervisor.dashboard', [
        'team' => $team,
        'stats' => $stats,
        'supervisor' => $user,
        'pendingAppraisals' => $pendingAppraisals,
        'approvedAppraisals' => $approvedAppraisals,
        'pendingAppeals' => $pendingAppeals,
        'approvedAppeals' => $approvedAppeals,
        'leaveStats' => $leaveStats,        // Add this
        'pendingLeaves' => $pendingLeaves,  // Add this
        'upcomingLeaves' => $upcomingLeaves // Add this
    ]);
}

// Add this method to calculate leave statistics
private function getLeaveStats($team)
{
    $employeeNumbers = $team->pluck('employee_number');
    
    // Get all leaves for the team
    $leaves = Leave::whereIn('employee_number', $employeeNumbers)->get();
    
    // Count by status
    $pending = $leaves->where('status', 'pending')->count();
    $approved = $leaves->where('status', 'approved')->count();
    $rejected = $leaves->where('status', 'rejected')->count();
    
    // Calculate average days for approved leaves
    $approvedLeaves = $leaves->where('status', 'approved');
    $avgDays = $approvedLeaves->count() > 0 
        ? round($approvedLeaves->avg('total_days'), 1) 
        : 0;
    
    return [
        'pending' => $pending,
        'approved' => $approved,
        'rejected' => $rejected,
        'avg_days' => $avgDays,
        'total' => $leaves->count(),
    ];
}
/**
 * Show individual leave details.
 */
public function showLeave($id)
{
    $supervisor = Auth::user();
    
    // Check if user is supervisor
    if ($supervisor->user_type !== 'supervisor') {
        return redirect()->route('dashboard')
            ->with('error', 'You do not have access to supervisor features.');
    }
    
    $leave = Leave::with('user')->findOrFail($id);
    
    // Check if supervisor manages this employee
    $employee = User::where('employee_number', $leave->employee_number)
        ->where('manager_id', $supervisor->employee_number)
        ->first();
        
    if (!$employee) {
        return redirect()->route('supervisor.dashboard')
            ->with('error', 'You can only view leaves for your team members.');
    }
    
    return view('supervisor.leave-details', compact('leave', 'supervisor'));
}
    /**
     * Rate an employee
     */
    public function rateEmployee(Request $request)
    {
        $request->validate([
            'employee_number' => 'required|exists:users,employee_number',
            'rating' => 'required|integer|min:1|max:5',
            'category' => 'required|string',
            'comments' => 'required|string|min:10',
        ]);
        
        // Check if the current user is the supervisor of this employee
        $employee = User::where('employee_number', $request->employee_number)->first();
        
        if ($employee->manager_id !== auth()->user()->employee_number) {
            return back()->with('error', 'You are not authorized to rate this employee.');
        }
        
        // Create the rating
        Rating::create([
            'employee_number' => $request->employee_number,
            'supervisor_number' => auth()->user()->employee_number,
            'rating' => $request->rating,
            'category' => $request->category,
            'comments' => $request->comments,
            'rating_date' => now(),
        ]);
        
        return back()->with('success', 'Rating submitted successfully!');
    }

    /**
     * Get appeal details
     */
    public function getAppeal($id)
    {
        $appeal = Appeal::with(['employee', 'comments.user'])->findOrFail($id);
        
        // Check if supervisor is authorized
        if ($appeal->employee->manager_id !== auth()->user()->employee_number) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $html = view('supervisor.partials.appeal-details', compact('appeal'))->render();
        
        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    /**
     * Start appeal review
     */
    public function startAppealReview(Request $request, $id)
    {
        $appeal = Appeal::findOrFail($id);
        
        // Check if supervisor is authorized
        $employee = User::where('employee_number', $appeal->employee_number)->first();
        if ($employee->manager_id !== auth()->user()->employee_number) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $appeal->update([
            'status' => 'under_review',
            'reviewed_by' => auth()->user()->employee_number,
            'review_started_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Appeal review started'
        ]);
    }

    /**
     * Add comment to appeal
     */
    public function addAppealComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|min:5',
        ]);
        
        $appeal = Appeal::findOrFail($id);
        
        // Check if supervisor is authorized
        $employee = User::where('employee_number', $appeal->employee_number)->first();
        if ($employee->manager_id !== auth()->user()->employee_number) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $appeal->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $request->comment,
            'is_supervisor' => true,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Comment added'
        ]);
    }

    /**
     * Get rating comments
     */
    public function getRatingComments(Request $request)
    {
        $request->validate([
            'employee' => 'required|exists:users,employee_number',
            'category' => 'nullable|string',
        ]);
        
        // Check if supervisor is authorized
        $employee = User::where('employee_number', $request->employee)->first();
        if ($employee->manager_id !== auth()->user()->employee_number) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $query = Rating::where('employee_number', $request->employee)
            ->where('supervisor_number', auth()->user()->employee_number);
        
        if ($request->category) {
            $query->where('category', $request->category);
        }
        
        $ratings = $query->orderBy('rating_date', 'desc')
            ->take(10)
            ->get();
        
        $comments = $ratings->map(function($rating) {
            return [
                'author' => auth()->user()->name,
                'comment' => $rating->comments,
                'date' => $rating->rating_date->format('M d, Y'),
                'is_supervisor' => true,
            ];
        });
        
        return response()->json([
            'success' => true,
            'comments' => $comments
        ]);
    }

    /**
     * Resolve appeal
     */
    public function resolveAppeal(Request $request, $id)
    {
        $request->validate([
            'decision' => 'required|in:approved,rejected',
            'resolution_notes' => 'required|string|min:10',
        ]);
        
        $appeal = Appeal::findOrFail($id);
        
        // Check if supervisor is authorized
        $employee = User::where('employee_number', $appeal->employee_number)->first();
        if ($employee->manager_id !== auth()->user()->employee_number) {
            return back()->with('error', 'You are not authorized to resolve this appeal.');
        }
        
        $appeal->update([
            'status' => $request->decision,
            'resolution_notes' => $request->resolution_notes,
            'resolved_by' => auth()->user()->employee_number,
            'resolved_at' => now(),
        ]);
        
        return back()->with('success', 'Appeal resolved successfully!');
    }
     public function quickRate(Request $request)
    {
        try {
            $request->validate([
                'employee_number' => 'required|exists:users,employee_number',
                'rating' => 'required|integer|min:1|max:5',
                'comments' => 'nullable|string|max:1000',
            ]);
            
            $supervisor = Auth::user();
            $employee = User::where('employee_number', $request->employee_number)->first();
            
            // Verify the employee is in the supervisor's team
            if (!$employee || $employee->manager_id !== $supervisor->employee_number) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to rate this employee.'
                ], 403);
            }
            
            // Check if there's an active appraisal for the current period
            $currentPeriod = date('Y') . ' Q' . ceil(date('n') / 3); // e.g., "2024 Q1"
            
            $appraisal = Appraisal::firstOrCreate(
                [
                    'employee_number' => $employee->employee_number,
                    'period' => $currentPeriod,
                ],
                [
                    'employee_name' => $employee->name,
                    'employee_id' => $employee->id,
                    'supervisor_id' => $supervisor->id,
                    'supervisor_name' => $supervisor->name,
                    'status' => 'draft',
                    'self_score' => 0,
                    'supervisor_score' => 0,
                    'final_score' => 0,
                ]
            );
            
            // Create or update a KPA for the quick rating
            $kpa = AppraisalKpa::updateOrCreate(
                [
                    'appraisal_id' => $appraisal->id,
                    'type' => 'quick_rating',
                ],
                [
                    'description' => 'Quick Performance Rating',
                    'self_rating' => 0,
                    'supervisor_rating' => $request->rating * 20, // Convert 1-5 to 0-100 scale
                    'agreed_rating' => $request->rating * 20,
                    'weight' => 100, // Full weight for quick rating
                    'self_comment' => '',
                    'supervisor_comment' => $request->comments,
                    'order' => 1,
                ]
            );
            
            // Update appraisal scores
            $appraisal->supervisor_score = $request->rating * 20;
            $appraisal->final_score = $request->rating * 20;
            
            // If this is the first rating, mark as submitted
            if ($appraisal->status === 'draft') {
                $appraisal->status = 'submitted';
            }
            
            $appraisal->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Rating submitted successfully!',
                'appraisal_id' => $appraisal->id,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function leaves(Request $request)
    {
        $supervisor = Auth::user();
        
        // Check if user is onboarded
        if (!$supervisor->is_onboarded) {
            return redirect()->route('onboarding.survey')
                ->with('info', 'Please complete your profile setup first.');
        }
        
        // Check if user is supervisor
        if ($supervisor->user_type !== 'supervisor') {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have access to the supervisor dashboard.');
        }
        
        // Get team members
        $team = User::where('manager_id', $supervisor->employee_number)
            ->where('user_type', 'employee')
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
        if ($supervisor->user_type !== 'supervisor') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }
        
        $leave = Leave::findOrFail($id);
        
        // Check if supervisor manages this employee
        $employee = User::where('employee_number', $leave->employee_number)
            ->where('manager_id', $supervisor->employee_number)
            ->first();
            
        if (!$employee) {
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
        
        // Validate remarks if provided
        $request->validate([
            'remarks' => 'nullable|string|max:500'
        ]);
        
        // Approve the leave
        $leave->update([
            'status' => 'approved',
            'approved_by' => $supervisor->employee_number,
            'approved_at' => now(),
            'remarks' => $request->remarks
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
        if ($supervisor->user_type !== 'supervisor') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }
        
        $leave = Leave::findOrFail($id);
        
        // Check if supervisor manages this employee
        $employee = User::where('employee_number', $leave->employee_number)
            ->where('manager_id', $supervisor->employee_number)
            ->first();
            
        if (!$employee) {
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
            'approved_by' => $supervisor->employee_number,
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
        if ($supervisor->user_type !== 'supervisor') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }
        
        $leave = Leave::findOrFail($id);
        
        // Check if supervisor manages this employee
        $employee = User::where('employee_number', $leave->employee_number)
            ->where('manager_id', $supervisor->employee_number)
            ->first();
            
        if (!$employee) {
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
     * Show team members list.
     */
    public function team()
    {
        $supervisor = Auth::user();
        
        // Check if user is supervisor
        if ($supervisor->user_type !== 'supervisor') {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have access to the supervisor dashboard.');
        }
        
        $team = User::where('manager_id', $supervisor->employee_number)
            ->where('user_type', 'employee')
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
        if ($supervisor->user_type !== 'supervisor') {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have access to the supervisor dashboard.');
        }
        
        return view('supervisor.reports', compact('supervisor'));
    }
    
    // ... other methods ...
}