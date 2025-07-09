<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Halte;
use App\Models\Kerumunan;
use Illuminate\View\View;
use Exception;

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
        
        // Get all users for the table
        $users = User::orderBy('created_at', 'desc')->get();
        
        return view('admin.dashboard', [
            'user' => $user,
            'users' => $users,
            'stats' => [
                'total_users' => User::count(),
                'active_users' => User::where('role', 'user')->count(),
                'admin_users' => User::where('role', 'admin')->count(),
            ]
        ]);
    }
    
    /**
     * Show the map view for CCTV locations.
     */
    public function mapView(): View
    {
        /** @var User $user */
        $user = Auth::user();
        
        return view('admin.map', [
            'user' => $user
        ]);
    }
    
    /**
     * Get kerumunan data from Laravel database
     */
    public function getKerumunanData()
    {
        try {
            $kerumunanData = Kerumunan::with('halte')->orderBy('waktu', 'desc')->get();
            
            // Transform data to match expected format
            $transformedData = $kerumunanData->map(function ($kerumunan) {
                return [
                    'id_kerumunan' => $kerumunan->id_kerumunan,
                    'id_halte' => $kerumunan->id_halte,
                    'nama_halte' => $kerumunan->halte ? $kerumunan->halte->nama_halte : 'Unknown',
                    'waktu' => $kerumunan->waktu,
                    'jumlah_kerumunan' => $kerumunan->jumlah_kerumunan
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $transformedData
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get halte data from Laravel database
     */
    public function getHalteData()
    {
        try {
            $halteData = Halte::all();
            
            // Transform data to match expected format
            $transformedData = $halteData->map(function ($halte) {
                return [
                    'id_halte' => $halte->id,
                    'nama_halte' => $halte->nama_halte,
                    'cctv' => $halte->cctv,
                    'latitude' => $halte->latitude,
                    'longitude' => $halte->longitude
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $transformedData
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show the user dashboard.
     */
    public function userDashboard(): View
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Get CCTV statistics from halte data
        $cctvStats = $this->getCctvStatistics();
        
        // Get recent kerumunan data for monitoring (read-only)
        $recentKerumunan = Kerumunan::with('halte')
            ->orderBy('waktu', 'desc')
            ->limit(10)
            ->get();
            
        // Get halte data for map view (read-only)
        $halteData = Halte::all();
        
        // Calculate statistics for user dashboard
        $stats = [
            'total_halte' => Halte::count(),
            'active_cctv' => Halte::whereNotNull('cctv')->where('cctv', '!=', '')->count(),
            'total_kerumunan_today' => Kerumunan::whereDate('waktu', today())->count(),
        ];
        
        return view('user.dashboard', [
            'user' => $user,
            'account_status' => 'Active',
            'recent_kerumunan' => $recentKerumunan,
            'halte_data' => $halteData,
            'stats' => $stats,
            'cctv_stats' => $cctvStats
        ]);
    }
    
    /**
     * Get CCTV statistics from halte data
     */
    private function getCctvStatistics()
    {
        try {
            $locations = Halte::all();
            $totalLocations = $locations->count();
            $activeCameras = 0;
            
            foreach ($locations as $location) {
                if (!empty(trim($location->cctv))) {
                    $activeCameras++;
                }
            }
            
            $inactiveCameras = $totalLocations - $activeCameras;
            $coveragePercentage = $totalLocations > 0 ? round(($activeCameras / $totalLocations) * 100) : 0;
            
            return [
                'total_locations' => $totalLocations,
                'active_cameras' => $activeCameras,
                'inactive_cameras' => $inactiveCameras,
                'coverage_area' => $coveragePercentage . '%',
                'last_update' => now()->diffForHumans()
            ];
        } catch (Exception $e) {
            // Log error but don't show fallback data
            error_log('Failed to fetch CCTV data: ' . $e->getMessage());
            
            // Return empty data if database fails
            return [
                'total_locations' => 0,
                'active_cameras' => 0,
                'inactive_cameras' => 0,
                'coverage_area' => '0%',
                'last_update' => '-'
            ];
        }
    }
    
    /**
     * Create a new user
     */
    public function createUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'role' => 'required|in:user,admin'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update an existing user
     */
    public function updateUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
                'role' => 'required|in:user,admin'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete a user
     */
    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Prevent deleting the currently logged-in user
            if ($user->id === Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete your own account'
                ], 403);
            }
            
            $user->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Create a new halte location
     */
    public function createHalte(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_halte' => 'required|string|max:255',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'cctv' => 'required|url'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Save data to Laravel database
            $halte = Halte::create([
                'nama_halte' => $request->nama_halte,
                'latitude' => (float) $request->latitude,
                'longitude' => (float) $request->longitude,
                'cctv' => $request->cctv
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Halte location created successfully',
                'data' => [
                    'id_halte' => $halte->id,
                    'nama_halte' => $halte->nama_halte,
                    'cctv' => $halte->cctv,
                    'latitude' => $halte->latitude,
                    'longitude' => $halte->longitude
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create halte: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update an existing halte location
     */
    public function updateHalte(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_halte' => 'required|string|max:255',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'cctv' => 'required|url'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Update data in Laravel database
            $halte = Halte::findOrFail($id);
            $halte->update([
                'nama_halte' => $request->nama_halte,
                'latitude' => (float) $request->latitude,
                'longitude' => (float) $request->longitude,
                'cctv' => $request->cctv
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Halte location updated successfully',
                'data' => [
                    'id_halte' => $halte->id,
                    'nama_halte' => $halte->nama_halte,
                    'cctv' => $halte->cctv,
                    'latitude' => $halte->latitude,
                    'longitude' => $halte->longitude
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update halte: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete a halte location
     */
    public function deleteHalte($id)
    {
        try {
            $halte = Halte::findOrFail($id);
            $halte->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Halte location deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete halte: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Show the form for creating a new user
     */
    public function createUserForm(): View
    {
        return view('admin.users.form');
    }
    
    /**
     * Show the form for editing an existing user
     */
    public function editUserForm($id): View
    {
        $user = User::findOrFail($id);
        return view('admin.users.form', compact('user'));
    }
    
    /**
     * Show the form for creating a new halte
     */
    public function createHalteForm(): View
    {
        return view('admin.halte.form');
    }
    
    /**
     * Show the form for editing an existing halte
     */
    public function editHalteForm($id): View
    {
        try {
            $halte = Halte::findOrFail($id);
            return view('admin.halte.form', compact('halte'));
        } catch (Exception $e) {
            abort(404, 'Halte not found');
        }
    }
}