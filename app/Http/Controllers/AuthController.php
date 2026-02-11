<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Check employee number via AJAX
     */
    public function checkEmployee(Request $request)
    {
        $request->validate([
            'employee_number' => 'required|min:3'
        ]);
        
        $employeeNumber = $request->employee_number;
        
        $user = User::where('employee_number', $employeeNumber)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found in the system'
            ]);
        }
        
        // Check onboarding status - adjust based on your User model structure
        // You might need to change these fields based on your actual database
        $needsOnboarding = empty($user->password) || 
                           (isset($user->onboarding_completed) && 
                           ($user->onboarding_completed === false || 
                            $user->onboarding_completed === null));
        
        $hasPassword = !empty($user->password);
        
        return response()->json([
            'success' => true,
            'user' => [
                'name' => $user->name,
                'employee_name' => $user->name,
                'employee_number' => $user->employee_number
            ],
            'needs_onboarding' => $needsOnboarding,
            'has_password' => $hasPassword,
            'user_type' => $hasPassword ? 'returning' : 'first_time'
        ]);
    }

    /**
     * Handle employee login
     */
    public function employeeLogin(Request $request)
    {
        $request->validate([
            'employee_number' => 'required|min:3',
            'password' => 'nullable'
        ]);
        
        $user = User::where('employee_number', $request->employee_number)->first();
        
        if (!$user) {
            return back()->withErrors(['employee_number' => 'Employee not found']);
        }
        
        // Check if needs onboarding (no password set)
        if (empty($user->password)) {
            // Auto-login for first-time users
            Auth::login($user);
            
            // Redirect to password setup or dashboard
            if ($request->has('redirect_to_onboarding')) {
                return redirect()->route('onboarding.survey');
            }
            
            return redirect()->route('dashboard')->with('success', 'Welcome! Please set up your password in your profile.');
        }
        
        // For returning users, validate password
        if (empty($request->password)) {
            return back()->withErrors(['password' => 'Password is required']);
        }
        
        if (Hash::check($request->password, $user->password)) {
            Auth::login($user);
            return redirect()->route('dashboard');
        }
        
        return back()->withErrors(['password' => 'Invalid password']);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}