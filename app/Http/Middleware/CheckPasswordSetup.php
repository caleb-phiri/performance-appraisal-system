<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordSetup
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Check if user is authenticated
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Check if user needs to set up password
        if (!$user->password && !$user->password_setup_skipped) {
            // Allow access to profile password page and logout
            if (!$request->is('profile/password*') && !$request->is('logout')) {
                return redirect()->route('profile.password')->with('info', 'Please set up your password to continue.');
            }
        }
        
        return $next($request);
    }
}