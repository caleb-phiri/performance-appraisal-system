<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSupervisorOnboarding
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // Check if user is authenticated
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Check if user is supervisor
        if ($user->user_type === 'supervisor') {
            // Check if supervisor is onboarded
            if (!$user->is_onboarded) {
                // Store intended URL for redirect after onboarding
                session(['url.intended' => $request->fullUrl()]);
                
                return redirect()->route('onboarding.survey')
                    ->with('warning', 'Please complete your profile setup first to access supervisor features.');
            }
        }
        
        return $next($request);
    }
}