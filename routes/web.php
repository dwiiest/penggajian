<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\KaryawanController;
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
        Route::get('overtimes-report/export', [OvertimeController::class, 'exportReport'])->name('overtimes.report.export');
        
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('attendance', [AttendanceReportController::class, 'index'])
                ->name('attendance.index');

            Route::get('attendance/export', [AttendanceReportController::class, 'export'])
                ->name('attendance.export');

            Route::get('attendance/{employee}', [AttendanceReportController::class, 'detail'])
                ->name('attendance.detail'); 
        });
    });
    
    // 3. Dashboard Finance
        Route::middleware(['role:finance'])->prefix('finance')->name('finance.')->group(function () {
        Route::get('/', [FinanceController::class, 'index'])->name('dashboard');
        
        // Payroll Management
        Route::get('payrolls', [FinanceController::class, 'payrolls'])->name('payrolls.index');
        Route::get('payrolls/create', [FinanceController::class, 'create'])->name('payrolls.create');
        Route::post('payrolls/generate', [FinanceController::class, 'generate'])->name('payrolls.generate');
        Route::get('payrolls/{payroll}', [FinanceController::class, 'show'])->name('payrolls.show');
        Route::get('payrolls/{payroll}/edit', [FinanceController::class, 'edit'])->name('payrolls.edit');
        Route::put('payrolls/{payroll}', [FinanceController::class, 'update'])->name('payrolls.update');
        Route::delete('payrolls/{payroll}', [FinanceController::class, 'destroy'])->name('payrolls.destroy');
        Route::post('payrolls/{payroll}/pay', [FinanceController::class, 'pay'])->name('payrolls.pay');
        Route::post('payrolls/bulk-pay', [FinanceController::class, 'bulkPay'])->name('payrolls.bulk-pay');
        
        // Export & Download
        Route::get('payrolls/export/excel', [FinanceController::class, 'export'])->name('payrolls.export');
        Route::get('payrolls/{payroll}/download-payslip', [FinanceController::class, 'downloadPayslip'])->name('payrolls.download-payslip');
        
        // Reports
        Route::get('reports', [FinanceController::class, 'reports'])->name('reports.index');
    });

    // 4. Dashboard Manager
    Route::middleware(['role:manager'])->prefix('manager')->name('manager.')->group(function () {
        Route::get('/', [DashboardController::class, 'manager'])->name('dashboard');
    });

    // 5. Dashboard Karyawan
    Route::middleware(['role:karyawan'])->prefix('karyawan')->name('karyawan.')->group(function () {
        Route::get('/', [KaryawanController::class, 'index'])->name('dashboard');
        Route::get('/profile', [KaryawanController::class, 'profile'])->name('profile');
        Route::get('/attendances', [KaryawanController::class, 'attendances'])->name('attendances');
        Route::post('/clock-in', [KaryawanController::class, 'clockIn'])->name('clock-in');
        Route::post('/clock-out', [KaryawanController::class, 'clockOut'])->name('clock-out');
        Route::get('/overtimes', [KaryawanController::class, 'overtimes'])->name('overtimes');
        Route::post('/overtimes/submit', [KaryawanController::class, 'submitOvertime'])->name('overtimes.submit');
        Route::get('/payslips', [KaryawanController::class, 'payslips'])->name('payslips');
        Route::get('/payslips/{id}/download', [KaryawanController::class, 'downloadPayslip'])->name('payslips.download');
    });
});