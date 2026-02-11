<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckOnboarding
{
    public function handle(Request $request, Closure $next)
    {
        // Routes that require onboarding completion
        $protectedRoutes = ['dashboard', 'appraisals.*', 'profile'];
        
        if (in_array($request->route()->getName(), $protectedRoutes) || 
            str_starts_with($request->route()->getName(), 'appraisals.')) {
            
            if (!Session::has('onboarding_completed') || !Session::get('onboarding_completed')) {
                return redirect()->route('onboarding.survey')
                    ->with('error', 'Please complete your profile first.');
            }
        }
        
        return $next($request);
    }
}