<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\PositionController;
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

    Route::middleware(['role:admin,hrd'])->group(function () {
        Route::resource('departments', DepartmentController::class);
        Route::post('departments/{department}/toggle-status', [DepartmentController::class, 'toggleStatus'])->name('departments.toggle-status');

        Route::resource('positions', PositionController::class);
        Route::post('positions/{position}/toggle-status', [PositionController::class, 'toggleStatus'])->name('positions.toggle-status');
    });

    // 2. Dashboard HRD
    Route::middleware(['role:hrd'])->prefix('hrd')->name('hrd.')->group(function () {
        Route::get('/', [DashboardController::class, 'hrd'])->name('dashboard');

        Route::resource('employees', EmployeeController::class);
        
        Route::resource('attendances', AttendanceController::class);
        Route::get('attendances-bulk/create', [AttendanceController::class, 'bulkCreate'])->name('attendances.bulk-create');
        Route::post('attendances-bulk/store', [AttendanceController::class, 'bulkStore'])->name('attendances.bulk-store');
        
        Route::resource('overtimes', OvertimeController::class);
        Route::post('overtimes/{overtime}/approve', [OvertimeController::class, 'approve'])->name('overtimes.approve');
        Route::post('overtimes/{overtime}/reject', [OvertimeController::class, 'reject'])->name('overtimes.reject');
        Route::get('overtimes-report', [OvertimeController::class, 'report'])->name('overtimes.report');

        Route::get('reports/attendance', [AttendanceReportController::class, 'index'])->name('reports.attendance');
        Route::get('reports/attendance/{employee}', [AttendanceReportController::class, 'detail'])->name('reports.attendance-detail');
        Route::get('reports/attendance/export', [AttendanceReportController::class, 'export'])->name('reports.attendance-export');
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