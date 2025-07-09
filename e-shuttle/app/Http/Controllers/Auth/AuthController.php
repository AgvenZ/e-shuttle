<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['logout']);
    }

    /**
     * Show the welcome page.
     */
    public function showWelcome(): View
    {
        return view('welcome');
    }

    /**
     * Show the admin login page.
     */
    public function showAdminLogin(): View
    {
        return view('auth.admin-login');
    }

    /**
     * Show the user login page.
     */
    public function showUserLogin(): View
    {
        return view('auth.user-login');
    }

    /**
     * Handle admin login request.
     */
    public function adminLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            /** @var User $user */
            $user = Auth::user();
            
            if ($user->isAdmin()) {
                $request->session()->regenerate();
                
                // Get intended URL or default to admin dashboard
                $intendedUrl = $request->session()->pull('url.intended', route('admin.dashboard'));
                
                // Ensure the intended URL is for admin routes
                if (str_contains($intendedUrl, '/admin/')) {
                    return redirect($intendedUrl);
                }
                
                return redirect()->route('admin.dashboard');
            }
            
            // Don't logout, just redirect to appropriate dashboard
            if ($user->isUser()) {
                return redirect()->route('user.dashboard')->with('info', 'You are logged in as a user. Please use the user login page.');
            }
            
            // If role is neither admin nor user, logout
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return back()->withErrors([
                'email' => 'Your account does not have admin privileges.',
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Handle user login request.
     */
    public function userLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            /** @var User $user */
            $user = Auth::user();
            
            if ($user->isUser()) {
                $request->session()->regenerate();
                
                // Get intended URL or default to user dashboard
                $intendedUrl = $request->session()->pull('url.intended', route('user.dashboard'));
                
                // Ensure the intended URL is for user routes
                if (str_contains($intendedUrl, '/user/')) {
                    return redirect($intendedUrl);
                }
                
                return redirect()->route('user.dashboard');
            }
            
            // Don't logout, just redirect to appropriate dashboard
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('info', 'You are logged in as an admin. Please use the admin login page.');
            }
            
            // If role is neither admin nor user, logout
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return back()->withErrors([
                'email' => 'Your account does not have user privileges.',
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}