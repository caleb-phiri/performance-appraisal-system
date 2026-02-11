<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOnboarding
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is not authenticated, allow the request to continue
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();
        
        // If user is not onboarded and trying to access dashboard, redirect to onboarding
        if (!$user->is_onboarded && !$request->routeIs('onboarding.*')) {
            return redirect()->route('onboarding.show')
                ->with('info', 'Please complete your profile setup first.');
        }
        
        // If user is onboarded and trying to access onboarding page, redirect to dashboard
        if ($user->is_onboarded && $request->routeIs('onboarding.*')) {
            if ($user->user_type === 'supervisor') {
                return redirect()->route('supervisor.dashboard');
            }
            return redirect()->route('dashboard');
        }
        
        return $next($request);
    }
}