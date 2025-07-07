<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
    }

    /**
     * Show the admin dashboard.
     */
    public function adminDashboard(): View
    {
        /** @var User $user */
        $user = Auth::user();
        
        return view('admin.dashboard', [
            'user' => $user,
            'stats' => [
                'total_users' => 100, // Example data
                'active_users' => 75,
                'pending_requests' => 5,
            ]
        ]);
    }

    /**
     * Show the user dashboard.
     */
    public function userDashboard(): View
    {
        /** @var User $user */
        $user = Auth::user();
        
        return view('user.dashboard', [
            'user' => $user,
            'account_status' => 'Active',
        ]);
    }
}