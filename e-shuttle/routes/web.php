<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Welcome and Authentication Routes
Route::get('/', [AuthController::class, 'showWelcome'])->name('welcome');

// Admin Routes
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');
Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.dashboard');
Route::get('/admin/map', [DashboardController::class, 'mapView'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.map');
Route::get('/admin/users/create', [DashboardController::class, 'createUserForm'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.users.create');
Route::get('/admin/users/{id}/edit', [DashboardController::class, 'editUserForm'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.users.edit');
Route::get('/admin/halte/create', [DashboardController::class, 'createHalteForm'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.halte.create');
Route::get('/admin/halte/{id}/edit', [DashboardController::class, 'editHalteForm'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.halte.edit');

// User Routes
Route::get('/user/login', [AuthController::class, 'showUserLogin'])->name('user.login');
Route::post('/user/login', [AuthController::class, 'userLogin'])->name('user.login.submit');
Route::get('/user/dashboard', [DashboardController::class, 'userDashboard'])
    ->middleware(['auth', 'role:user'])
    ->name('user.dashboard');

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// API routes for dashboard data
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/api/kerumunan', [DashboardController::class, 'getKerumunanData']);
    Route::get('/api/halte', [DashboardController::class, 'getHalteData']);
    
    // User CRUD API routes
    Route::post('/api/users', [DashboardController::class, 'createUser']);
    Route::put('/api/users/{id}', [DashboardController::class, 'updateUser']);
    Route::delete('/api/users/{id}', [DashboardController::class, 'deleteUser']);
    
    // Halte CRUD API routes for map
    Route::post('/api/halte', [DashboardController::class, 'createHalte']);
    Route::put('/api/halte/{id}', [DashboardController::class, 'updateHalte']);
    Route::delete('/api/halte/{id}', [DashboardController::class, 'deleteHalte']);
});
