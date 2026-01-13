<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Overtime;
use App\Models\Position;
use Illuminate\Support\Facades\DB;

class DashboardController
{
    public function index()
    {
        $rolesCount = UserRole::count();
        $usersCount = User::count();
        $departmentsCount = Department::count();
        $positionsCount = Position::count();

        return view('admin.index', compact('rolesCount', 'usersCount', 'departmentsCount', 'positionsCount'));
    }

    public function manager()
    {
        return view('manager.index');
    }

    public function hrd()
    {
        $totalEmployees = Employee::count();
        
        $today = today();
        $todayAttendance = Attendance::whereDate('date', $today)->get();
        
        $attendanceStats = [
            'total' => $totalEmployees,
            'hadir' => $todayAttendance->where('status', 'hadir')->count(),
            'terlambat' => $todayAttendance->where('status', 'terlambat')->count(),
            'izin' => $todayAttendance->whereIn('status', ['izin', 'sakit', 'cuti'])->count(),
            'alpha' => $totalEmployees - $todayAttendance->count(), // Yang belum absen dianggap alpha
        ];
        
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        $overtimeStats = [
            'total' => Overtime::whereMonth('date', $currentMonth)
                                          ->whereYear('date', $currentYear)
                                          ->count(),
            'pending' => Overtime::whereMonth('date', $currentMonth)
                                            ->whereYear('date', $currentYear)
                                            ->where('status', 'pending')
                                            ->count(),
            'approved' => Overtime::whereMonth('date', $currentMonth)
                                             ->whereYear('date', $currentYear)
                                             ->where('status', 'approved')
                                             ->count(),
            'rejected' => Overtime::whereMonth('date', $currentMonth)
                                             ->whereYear('date', $currentYear)
                                             ->where('status', 'rejected')
                                             ->count(),
        ];
        
        $recentAttendances = Attendance::with(['employee.user', 'employee.position', 'employee.department'])
                                                  ->latest('created_at')
                                                  ->take(5)
                                                  ->get();
        
        $pendingOvertimes = Overtime::with(['employee.user', 'employee.position'])
                                               ->where('status', 'pending')
                                               ->latest()
                                               ->take(5)
                                               ->get();
        
        $departmentSummary = DB::table('attendances')
            ->join('employees', 'attendances.employee_id', '=', 'employees.id')
            ->join('departments', 'employees.department_id', '=', 'departments.id')
            ->whereDate('attendances.date', $today)
            ->groupBy('departments.id', 'departments.name')
            ->select(
                'departments.name as department_name',
                DB::raw('COUNT(attendances.id) as total_attendance'),
                DB::raw('SUM(CASE WHEN attendances.status = "hadir" THEN 1 ELSE 0 END) as hadir'),
                DB::raw('SUM(CASE WHEN attendances.status = "terlambat" THEN 1 ELSE 0 END) as terlambat'),
                DB::raw('SUM(CASE WHEN attendances.status IN ("izin", "sakit", "cuti") THEN 1 ELSE 0 END) as izin'),
                DB::raw('SUM(CASE WHEN attendances.status = "alpha" THEN 1 ELSE 0 END) as alpha')
            )
            ->get();
        
        $chartData = $this->getAttendanceChartData();
        
        return view('hrd.index', compact(
            'totalEmployees',
            'attendanceStats',
            'overtimeStats',
            'recentAttendances',
            'pendingOvertimes',
            'departmentSummary',
            'chartData'
        ));
    }
    
    /**
     * Get attendance chart data for last 7 days
     */
    private function getAttendanceChartData()
    {
        $dates = [];
        $hadirData = [];
        $terlambatData = [];
        $izinData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dates[] = $date->format('d M');
            
            $dayAttendance = Attendance::whereDate('date', $date)->get();
            
            $hadirData[] = $dayAttendance->where('status', 'hadir')->count();
            $terlambatData[] = $dayAttendance->where('status', 'terlambat')->count();
            $izinData[] = $dayAttendance->whereIn('status', ['izin', 'sakit', 'cuti'])->count();
        }
        
        return [
            'labels' => $dates,
            'hadir' => $hadirData,
            'terlambat' => $terlambatData,
            'izin' => $izinData,
        ];
    }

    public function finance()
    {
        return view('finance.index');
    }

    public function karyawan()
    {
        return view('karyawan.index');
    }
}