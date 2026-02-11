<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OnboardingController extends Controller
{
    // Show the onboarding form
    public function showSurvey()
    {
        // If user is already logged in and onboarded, redirect based on user type
        if (Auth::check() && Auth::user()->is_onboarded) {
            return Auth::user()->user_type === 'supervisor'
                ? redirect()->route('supervisor.dashboard')
                : redirect()->route('dashboard');
        }
        
        // If user is logged in but not onboarded, show the form
        if (Auth::check()) {
            return view('onboarding.survey');
        }
        
        return view('onboarding.survey');
    }

    // Handle form submission
    public function submitSurvey(Request $request)
    {
        Log::info('Submit survey called', ['employee_number' => $request->employee_number]);
        
        // Normalize employee number
        $employeeNumber = trim($request->employee_number);
        
        // Check if user exists
        $existingUser = User::where('employee_number', $employeeNumber)->first();
        
        // Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'employee_number' => 'required|string|max:50',
            'job_title' => 'required|string|max:255',
            'user_type' => 'required|string|in:employee,supervisor',
            'workstation_type' => 'required|string|in:hq,toll_plaza',
            'hq_department' => 'nullable|string|required_if:workstation_type,hq',
            'toll_plaza' => 'nullable|string|required_if:workstation_type,toll_plaza',
            'manager_id' => 'nullable|string',
            'department' => 'required|string',
            'email' => 'nullable|email|unique:users,email,' . ($existingUser ? $existingUser->id : 'NULL'), // Added email field
        ];

        // Add unique rule only for new users
        if (!$existingUser) {
            $rules['employee_number'] .= '|unique:users,employee_number';
        }

        // Validate
        try {
            $data = $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }

        // Create or update user
        if ($existingUser) {
            $existingUser->update([
                'name' => $data['name'],
                'email' => $data['email'] ?? null, // Added email
                'job_title' => $data['job_title'],
                'user_type' => $data['user_type'],
                'workstation_type' => $data['workstation_type'],
                'hq_department' => $data['hq_department'] ?? null,
                'toll_plaza' => $data['toll_plaza'] ?? null,
                'manager_id' => $data['manager_id'] ?? null,
                'department' => $data['department'],
                'is_onboarded' => true,
                'onboarded_at' => now(),
                'password_setup_skipped' => true, // Added this line
            ]);
            $user = $existingUser;
        } else {
            $user = User::create([
                'employee_number' => $data['employee_number'],
                'name' => $data['name'],
                'email' => $data['email'] ?? null, // Added email
                'job_title' => $data['job_title'],
                'user_type' => $data['user_type'],
                'workstation_type' => $data['workstation_type'],
                'hq_department' => $data['hq_department'] ?? null,
                'toll_plaza' => $data['toll_plaza'] ?? null,
                'manager_id' => $data['manager_id'] ?? null,
                'department' => $data['department'],
                'is_onboarded' => true,
                'onboarded_at' => now(),
                'password' => null, // No password initially
                'password_setup_skipped' => true, // Allow login without password initially
            ]);
        }

        // Log in the user
        Auth::login($user);

        // Determine redirect based on user type
        $redirect = $user->user_type === 'supervisor' 
            ? route('supervisor.dashboard') 
            : route('dashboard');
        
        $message = $user->user_type === 'supervisor'
            ? 'Supervisor profile completed successfully!'
            : 'Profile completed successfully!';

        // AJAX response
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => $redirect,
                'message' => $message
            ]);
        }

        // Normal response
        return redirect($redirect)->with('success', $message);
    }

    // ALTERNATIVE: New method for handling form submission with password redirect
    public function submit(Request $request)
    {
        Log::info('Submit called', ['employee_number' => $request->employee_number]);
        
        // Normalize employee number
        $employeeNumber = trim($request->employee_number);
        
        // Check if user exists
        $existingUser = User::where('employee_number', $employeeNumber)->first();
        
        // Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'employee_number' => 'required|string|max:50',
            'job_title' => 'required|string|max:255',
            'user_type' => 'required|string|in:employee,supervisor',
            'workstation_type' => 'required|string|in:hq,toll_plaza',
            'hq_department' => 'nullable|string|required_if:workstation_type,hq',
            'toll_plaza' => 'nullable|string|required_if:workstation_type,toll_plaza',
            'manager_id' => 'nullable|string',
            'department' => 'required|string',
            'email' => 'nullable|email|unique:users,email,' . ($existingUser ? $existingUser->id : 'NULL'),
        ];

        // Add unique rule only for new users
        if (!$existingUser) {
            $rules['employee_number'] .= '|unique:users,employee_number';
        }

        // Validate
        try {
            $data = $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        // Create or update user
        if ($existingUser) {
            $existingUser->update([
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'job_title' => $data['job_title'],
                'user_type' => $data['user_type'],
                'workstation_type' => $data['workstation_type'],
                'hq_department' => $data['hq_department'] ?? null,
                'toll_plaza' => $data['toll_plaza'] ?? null,
                'manager_id' => $data['manager_id'] ?? null,
                'department' => $data['department'],
                'is_onboarded' => true,
                'onboarded_at' => now(),
                'password_setup_skipped' => true, // Mark as skipped initially
            ]);
            $user = $existingUser;
        } else {
            $user = User::create([
                'employee_number' => $data['employee_number'],
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'password' => null, // No password initially
                'password_setup_skipped' => true, // Allow login without password initially
                'department' => $data['department'],
                'job_title' => $data['job_title'],
                'workstation_type' => $data['workstation_type'],
                'toll_plaza' => $data['toll_plaza'] ?? null,
                'hq_department' => $data['hq_department'] ?? null,
                'user_type' => $data['user_type'],
                'manager_id' => $data['manager_id'] ?? null,
                'is_onboarded' => true,
                'onboarded_at' => now(),
            ]);
        }
        
        // Login the user
        Auth::login($user);
        
        // Redirect to password setup
        return redirect()->route('profile.password')->with('success', 'Profile created successfully! Please set up your password.');
    }

    // AJAX: check employee number - FIXED VERSION (REMOVED AUTO-LOGIN)
    public function checkEmployeeNumber(Request $request)
    {
        Log::info('AJAX check called', ['employee_number' => $request->employee_number]);
        
        // Force JSON response
        $request->headers->set('Accept', 'application/json');
        
        try {
            $request->validate([
                'employee_number' => 'required|string|min:3',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
                'exists' => false,
                'registered' => false,
                'onboarded' => false
            ], 422);
        }

        $employeeNumber = trim($request->employee_number);
        $user = User::where('employee_number', $employeeNumber)->first();
        
        if ($user) {
            if ($user->is_onboarded) {
                // User exists and is onboarded - DON'T auto-login
                // Instead, tell the frontend to show login form
                
                return response()->json([
                    'exists' => true,
                    'registered' => true,
                    'onboarded' => true,
                    'has_password' => $user->hasPassword(), // Check if password exists
                    'requires_password_setup' => $user->requiresPasswordSetup(), // Check if needs password setup
                    'redirect_to_login' => true, // Add this flag
                    'login_url' => route('login'), // Provide login URL
                    'message' => 'Employee found! Please login to continue.',
                    'user' => [
                        'name' => $user->name,
                        'employee_number' => $user->employee_number,
                        'user_type' => $user->user_type,
                        'job_title' => $user->job_title,
                        'department' => $user->department,
                        'workstation_type' => $user->workstation_type,
                        'hq_department' => $user->hq_department,
                        'toll_plaza' => $user->toll_plaza,
                        'manager_id' => $user->manager_id,
                    ]
                ]);
            } else {
                // User exists but not onboarded - prefill form
                return response()->json([
                    'exists' => true,
                    'registered' => true,
                    'onboarded' => false,
                    'has_password' => $user->hasPassword(),
                    'requires_password_setup' => $user->requiresPasswordSetup(),
                    'message' => 'Please complete your profile setup below.',
                    'user' => [
                        'name' => $user->name,
                        'employee_number' => $user->employee_number,
                        'user_type' => $user->user_type,
                        'job_title' => $user->job_title,
                        'department' => $user->department,
                        'workstation_type' => $user->workstation_type,
                        'hq_department' => $user->hq_department,
                        'toll_plaza' => $user->toll_plaza,
                        'manager_id' => $user->manager_id,
                    ]
                ]);
            }
        }

        return response()->json([
            'exists' => false,
            'registered' => false,
            'onboarded' => false,
            'has_password' => false,
            'requires_password_setup' => false,
            'message' => 'Please complete your profile information below.'
        ]);
    }

    /**
     * AJAX: Get all registered supervisors
     */
    public function getSupervisors(Request $request)
    {
        try {
            // Get all users who are supervisors and are onboarded
            $supervisors = User::where('user_type', 'supervisor')
                ->where('is_onboarded', true)
                ->orderBy('name')
                ->get(['employee_number', 'name', 'department', 'job_title']);
            
            return response()->json([
                'success' => true,
                'supervisors' => $supervisors,
                'count' => $supervisors->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading supervisors',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}