<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Allow access to admin and supervisor users
        if ($user->user_type === 'admin' || $user->user_type === 'supervisor') {
            return $next($request);
        }

        abort(403, 'Unauthorized access. Admin or supervisor privileges required.');
    }
}