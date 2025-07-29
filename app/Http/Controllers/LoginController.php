<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        // Validate the input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Check if email is verified
            if (Auth::user()->hasVerifiedEmail()) {
                $user = Auth::user();
                
                // Check if this is first login
                if ($user->first_login) {
                    // Mark as no longer first login
                    $user->update(['first_login' => false]);
                    // Redirect to dashboard for first-timers
                    return redirect()->intended(route('dashboard'));
                } else {
                    // Redirect to landing page for returning users
                    return redirect()->intended(route('landing'));
                }
            } else {
                return redirect()->route('verification.notice');
            }
        }

        // Authentication failed
        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}
