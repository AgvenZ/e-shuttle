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

// User Routes
Route::get('/user/login', [AuthController::class, 'showUserLogin'])->name('user.login');
Route::post('/user/login', [AuthController::class, 'userLogin'])->name('user.login.submit');
Route::get('/user/dashboard', [DashboardController::class, 'userDashboard'])
    ->middleware(['auth', 'role:user'])
    ->name('user.dashboard');

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
