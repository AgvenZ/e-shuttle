<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
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
     * Get kerumunan data from Python backend API
     */
    public function getKerumunanData()
    {
        try {
            $response = file_get_contents('http://localhost:5000/kerumunan');
            $data = json_decode($response, true);
            
            if ($data && isset($data['data'])) {
                return response()->json([
                    'success' => true,
                    'data' => $data['data']
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No data found'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get halte data from Python backend API
     */
    public function getHalteData()
    {
        try {
            $response = file_get_contents('http://localhost:5000/halte');
            $data = json_decode($response, true);
            
            if ($data && isset($data['data'])) {
                return response()->json([
                    'success' => true,
                    'data' => $data['data']
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No data found'
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
        
        return view('user.dashboard', [
            'user' => $user,
            'account_status' => 'Active',
        ]);
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
            
            // Send data to Python backend
            $data = [
                'nama_halte' => $request->nama_halte,
                'latitude' => (float) $request->latitude,
                'longitude' => (float) $request->longitude,
                'cctv' => $request->cctv
            ];
            
            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => 'Content-Type: application/json',
                    'content' => json_encode($data)
                ]
            ]);
            
            $response = file_get_contents('http://localhost:5000/halte', false, $context);
            $result = json_decode($response, true);
            
            if ($result && isset($result['message'])) {
                return response()->json([
                    'success' => true,
                    'message' => 'Halte location created successfully',
                    'data' => $result
                ]);
            }
            
            throw new Exception('Failed to create halte location');
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
            
            // Send data to Python backend
            $data = [
                'nama_halte' => $request->nama_halte,
                'latitude' => (float) $request->latitude,
                'longitude' => (float) $request->longitude,
                'cctv' => $request->cctv
            ];
            
            $context = stream_context_create([
                'http' => [
                    'method' => 'PUT',
                    'header' => 'Content-Type: application/json',
                    'content' => json_encode($data)
                ]
            ]);
            
            $response = file_get_contents("http://localhost:5000/halte/{$id}", false, $context);
            $result = json_decode($response, true);
            
            if ($result && isset($result['message'])) {
                return response()->json([
                    'success' => true,
                    'message' => 'Halte location updated successfully',
                    'data' => $result
                ]);
            }
            
            throw new Exception('Failed to update halte location');
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
            $context = stream_context_create([
                'http' => [
                    'method' => 'DELETE'
                ]
            ]);
            
            $response = file_get_contents("http://localhost:5000/halte/{$id}", false, $context);
            $result = json_decode($response, true);
            
            if ($result && isset($result['message'])) {
                return response()->json([
                    'success' => true,
                    'message' => 'Halte location deleted successfully'
                ]);
            }
            
            throw new Exception('Failed to delete halte location');
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
        // Get halte data from Python backend
        try {
            $response = file_get_contents("http://localhost:5000/halte/{$id}");
            $result = json_decode($response, true);
            
            if ($result && isset($result['data'])) {
                $halte = $result['data'];
                return view('admin.halte.form', compact('halte'));
            }
            
            throw new Exception('Halte not found');
        } catch (Exception $e) {
            abort(404, 'Halte not found');
        }
    }
}