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
        $this->middleware('web');
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

        if (Auth::attempt($credentials)) {
            /** @var User $user */
            $user = Auth::user();
            
            if ($user->isAdmin()) {
                $request->session()->regenerate();
                return redirect()->intended('admin/dashboard');
            }
            
            Auth::logout();
            return back()->withErrors([
                'email' => 'These credentials do not match our records for admin.',
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

        if (Auth::attempt($credentials)) {
            /** @var User $user */
            $user = Auth::user();
            
            if ($user->isUser()) {
                $request->session()->regenerate();
                return redirect()->intended('user/dashboard');
            }
            
            Auth::logout();
            return back()->withErrors([
                'email' => 'These credentials do not match our records for user.',
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