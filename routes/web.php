<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use Illuminate\Support\Facades\Route;

Route::get('/home', function () {
    return view('index');
});

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // 1. Dashboard Admin
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
   
        Route::resource('user-roles', UserRoleController::class);
        Route::post('user-roles/{userRole}/toggle-status', [UserRoleController::class, 'toggleStatus'])->name('user-roles.toggle-status');
        
        Route::resource('users', UserController::class);
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::get('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('users/{user}/update-password', [UserController::class, 'updatePassword'])->name('users.update-password');
    });

    // 2. Dashboard HRD
    Route::middleware(['role:hrd'])->prefix('hrd')->name('hrd.')->group(function () {
        Route::get('/', [DashboardController::class, 'hrd'])->name('dashboard');
   
    });
    
    // 3. Dashboard Finance
        Route::middleware(['role:finance'])->prefix('finance')->name('finance.')->group(function () {
        Route::get('/', [DashboardController::class, 'finance'])->name('dashboard');
    });

    // 4. Dashboard Manager
    Route::middleware(['role:manager'])->prefix('manager')->name('manager.')->group(function () {
        Route::get('/', [DashboardController::class, 'manager'])->name('dashboard');
    });

    // 5. Dashboard Karyawan
    Route::middleware(['role:karyawan'])->prefix('karyawan')->name('karyawan.')->group(function () {
        Route::get('/', [DashboardController::class, 'karyawan'])->name('dashboard');
    });
});