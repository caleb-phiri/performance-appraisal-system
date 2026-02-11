<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SessionController extends Controller
{
    /**
     * Start a new session for the application
     */
    public function start()
    {
        // Initialize session if not already started
        if (!Session::has('session_started')) {
            Session::put('session_started', true);
            Session::put('session_id', uniqid());
        }
        
        // Check if onboarding is completed
        if (Session::has('onboarding_completed') && Session::get('onboarding_completed')) {
            return redirect()->route('dashboard');
        }
        
        // Otherwise go to onboarding
        return redirect()->route('onboarding.survey');
    }
    
    /**
     * Reset the session (for testing/restart)
     */
    public function reset()
    {
        Session::flush();
        return redirect('/')->with('info', 'Session has been reset. You can start over.');
    }
}