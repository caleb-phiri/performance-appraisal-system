<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appraisal;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('onboarding.survey')
                ->with('error', 'Please complete your profile setup first.');
        }

        $user = Auth::user();
        
        // Check if user needs to set up password
        if (!$user->password && !$user->password_setup_skipped) {
            return redirect()->route('profile.password')->with('info', 'Welcome! Please set up your password for added security.');
        }
        
        // Get all supervisors for the dropdown (excluding current user)
        $allSupervisors = User::where('user_type', 'supervisor')
            ->where('id', '!=', $user->id)
            ->orderBy('name')
            ->get();
        
        // Get current supervisor selection
        $selectedSupervisorId = $user->supervisor_id;
        
        // Calculate counts based on user type
        if ($user->user_type === 'supervisor') {
            // Get subordinate employee numbers
            $subordinateNumbers = $user->subordinates->pluck('employee_number')->toArray();
            // Include supervisor's own appraisals too
            $subordinateNumbers[] = $user->employee_number;
            
            // Get appraisals for supervisor and their subordinates
            $userAppraisals = Appraisal::whereIn('employee_number', $subordinateNumbers)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Regular employees only see their own appraisals
            $userAppraisals = Appraisal::where('employee_number', $user->employee_number)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        // Calculate counts from the $userAppraisals collection
        $totalAppraisals = $userAppraisals->count() ?? 0;
        $draftCount = $userAppraisals->where('status', 'draft')->count() ?? 0;
        $submittedCount = $userAppraisals->where('status', 'submitted')->count() ?? 0;
        $approvedCount = $userAppraisals->where('status', 'approved')->count() ?? 0;
        $rejectedCount = $userAppraisals->where('status', 'rejected')->count() ?? 0;
        
        // Get only first 5 appraisals for dashboard
        $recentAppraisals = $userAppraisals->take(5);
        
        // Get user leaves
        $userLeaves = Leave::where('employee_number', $user->employee_number)->get();
        $pendingLeaves = $userLeaves->where('status', 'pending')->count();
        $upcomingApprovedLeaves = $userLeaves->where('status', 'approved')
                                              ->where('start_date', '>=', now())
                                              ->count();
        
        // Pass all variables to the view
        return view('dashboard', compact(
            'user',
            'allSupervisors',
            'selectedSupervisorId',
            'totalAppraisals',
            'draftCount',
            'submittedCount',
            'approvedCount',
            'rejectedCount',
            'recentAppraisals',
            'pendingLeaves',
            'upcomingApprovedLeaves',
            'userAppraisals'
        ));
    }
}