<?php
// app/Http\Controllers\DashboardController.php

namespace App\Http\Controllers;

use App\Models\Appraisal;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
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
        
        // Rest of your dashboard logic...
        $requiresPasswordSetup = $user->requiresPasswordSetup();
        
        // Fetch appraisals based on user type
        if ($user->user_type === 'supervisor') {
            $appraisals = Appraisal::whereHas('user', function ($query) use ($user) {
                $query->where('supervisor_id', $user->id)
                      ->orWhere('department', $user->department)
                      ->orWhere('id', $user->id);
            })->latest()->get();
        } else {
            $appraisals = Appraisal::where('employee_number', $user->employee_number)->latest()->get();
        }
        
        // Calculate counts from the $appraisals collection
        $totalAppraisals = $appraisals->count() ?? 0;
        $draftAppraisals = $appraisals->where('status', 'draft')->count() ?? 0;
        $submittedAppraisals = $appraisals->where('status', 'submitted')->count() ?? 0;
        $approvedAppraisals = $appraisals->where('status', 'approved')->count() ?? 0;
        $rejectedAppraisals = $appraisals->where('status', 'rejected')->count() ?? 0;
        
        // Get recent appraisals (first 5)
        $recentAppraisals = $appraisals->take(5);
        
        return view('dashboard', compact(
            'user',
            'appraisals',
            'requiresPasswordSetup',
            'totalAppraisals',
            'draftAppraisals',
            'submittedAppraisals',
            'approvedAppraisals',
            'rejectedAppraisals',
            'recentAppraisals'
        ));
    }
}