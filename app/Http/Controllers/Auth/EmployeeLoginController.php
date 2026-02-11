<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class EmployeeLoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle employee login
     */
    public function login(Request $request)
    {
        $request->validate([
            'employee_number' => 'required|string|min:3|max:20',
            'password' => 'nullable|string|min:6',
        ]);

        $employeeNumber = $request->employee_number;
        $password = $request->password;
        
        // Find user by employee number
        $user = User::where('employee_number', $employeeNumber)->first();
        
        // Check if user exists
        if (!$user) {
            return back()->with('show_onboarding_link', true)
                        ->withInput($request->only('employee_number'))
                        ->withErrors([
                            'employee_number' => 'Employee number not found.'
                        ]);
        }
        
        // ✅ CHECK IF USER IS INACTIVE/LEFT COMPANY
        if ($this->isUserInactive($user)) {
            return back()->with('error', 
                'Your account has been deactivated. Please contact the administrator.')
                ->withInput($request->only('employee_number'));
        }
        
        // Check if user has completed onboarding
        if (!$user->is_onboarded) {
            return redirect()->route('onboarding.survey')
                ->with('warning', 'Please complete your profile setup before logging in.')
                ->withInput($request->only('employee_number'));
        }
        
        // Check if password is set
        if ($user->hasPassword()) {
            // User has password - validate it
            if (empty($password)) {
                return back()->with('error', 'Password is required for this account.')
                    ->withInput($request->only('employee_number'));
            }
            
            // Use the verifyPassword method from User model
            if (!$user->verifyPassword($password)) {
                return back()->with('error', 'Invalid password.')
                    ->withInput($request->only('employee_number'));
            }
            
            // Password is correct - log in
            Auth::login($user, $request->filled('remember'));
            $request->session()->regenerate();
            
            return redirect()->intended(route('dashboard'));
            
        } else {
            // User doesn't have password (first time login)
            if (!empty($password)) {
                // They tried to enter a password but don't have one set
                return back()->with('error', 'No password set for this account. Please leave password blank for first login.')
                    ->withInput($request->only('employee_number'));
            }
            
            // First time login - log them in
            Auth::login($user);
            $request->session()->regenerate();
            
            // Check if they should set up password
            if ($user->requiresPasswordSetup()) {
                return redirect()->route('profile.password')
                    ->with('info', 'Welcome! Please set up a password for your account for added security.');
            }
            
            return redirect()->route('dashboard');
        }
    }

    /**
     * Check if user is inactive
     */
    private function isUserInactive(User $user): bool
    {
        // Check left_company field
        if ($user->left_company === true) {
            return true;
        }
        
        // Check is_active field if exists
        if (isset($user->is_active) && $user->is_active === false) {
            return true;
        }
        
        return false;
    }

    /**
     * Check employee number via AJAX (for login page)
     */
    public function checkEmployee(Request $request)
    {
        $request->validate([
            'employee_number' => 'required|string|min:3'
        ]);
        
        $employeeNumber = $request->employee_number;
        
        $user = User::where('employee_number', $employeeNumber)->first();
        
        if (!$user) {
            return response()->json([
                'exists' => false,
                'message' => 'Employee number not found'
            ]);
        }
        
        // ✅ CHECK IF USER IS INACTIVE/LEFT COMPANY
        if ($this->isUserInactive($user)) {
            return response()->json([
                'exists' => true,
                'registered' => true,
                'onboarded' => false,
                'inactive' => true,
                'message' => 'Your account has been deactivated. Please contact the administrator.',
                'redirect_to_login' => false,
                'user' => [
                    'name' => $user->name,
                    'employee_number' => $user->employee_number
                ]
            ]);
        }
        
        $hasPassword = $user->hasPassword();
        $onboarded = $user->is_onboarded ?? false;
        
        return response()->json([
            'exists' => true,
            'registered' => true,
            'onboarded' => $onboarded,
            'inactive' => false,
            'has_password' => $hasPassword,
            'user_type' => $hasPassword ? 'returning' : 'first_time',
            'redirect_to_login' => $onboarded,
            'login_url' => route('login'),
            'user' => [
                'name' => $user->name,
                'employee_number' => $user->employee_number
            ]
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('status', 'You have been logged out.');
    }
}