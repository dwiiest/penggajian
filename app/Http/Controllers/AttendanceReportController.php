<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('m'));
        $year = $request->input('year', now()->format('Y'));
        $departmentId = $request->input('department');

        $employeesQuery = Employee::with(['user', 'position', 'department']);
        
        if ($departmentId) {
            $employeesQuery->where('department_id', $departmentId);
        }
        
        $employees = $employeesQuery->get();

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $attendances = Attendance::with('employee')
                                ->whereBetween('date', [$startDate, $endDate])
                                ->get()
                                ->groupBy('employee_id');

        $reportData = [];
        foreach ($employees as $employee) {
            $employeeAttendances = $attendances->get($employee->id, collect());
            
            $reportData[] = [
                'employee' => $employee,
                'total_days' => $employeeAttendances->count(),
                'hadir' => $employeeAttendances->where('status', 'hadir')->count(),
                'terlambat' => $employeeAttendances->where('status', 'terlambat')->count(),
                'izin' => $employeeAttendances->where('status', 'izin')->count(),
                'sakit' => $employeeAttendances->where('status', 'sakit')->count(),
                'alpha' => $employeeAttendances->where('status', 'alpha')->count(),
                'cuti' => $employeeAttendances->where('status', 'cuti')->count(),
            ];
        }

        $departments = Department::active()->get();

        $totalAttendances = Attendance::whereBetween('date', [$startDate, $endDate])->count();
        $overallStats = [
            'total' => $totalAttendances,
            'hadir' => Attendance::whereBetween('date', [$startDate, $endDate])
                                ->where('status', 'hadir')->count(),
            'terlambat' => Attendance::whereBetween('date', [$startDate, $endDate])
                                    ->where('status', 'terlambat')->count(),
            'izin' => Attendance::whereBetween('date', [$startDate, $endDate])
                                ->where('status', 'izin')->count(),
            'sakit' => Attendance::whereBetween('date', [$startDate, $endDate])
                                 ->where('status', 'sakit')->count(),
            'alpha' => Attendance::whereBetween('date', [$startDate, $endDate])
                                 ->where('status', 'alpha')->count(),
            'cuti' => Attendance::whereBetween('date', [$startDate, $endDate])
                                ->where('status', 'cuti')->count(),
        ];

        return view('hrd.reports.attendance', compact(
            'reportData',
            'departments',
            'month',
            'year',
            'overallStats',
            'startDate',
            'endDate'
        ));
    }

    public function detail(Request $request, Employee $employee)
    {
        $month = $request->input('month', now()->format('m'));
        $year = $request->input('year', now()->format('Y'));

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $attendances = Attendance::where('employee_id', $employee->id)
                                ->whereBetween('date', [$startDate, $endDate])
                                ->orderBy('date')
                                ->get();

        $employee->load(['user', 'position', 'department']);

        $stats = [
            'total_days' => $attendances->count(),
            'hadir' => $attendances->where('status', 'hadir')->count(),
            'terlambat' => $attendances->where('status', 'terlambat')->count(),
            'izin' => $attendances->where('status', 'izin')->count(),
            'sakit' => $attendances->where('status', 'sakit')->count(),
            'alpha' => $attendances->where('status', 'alpha')->count(),
            'cuti' => $attendances->where('status', 'cuti')->count(),
        ];

        return view('hrd.reports.attendance-detail', compact(
            'employee',
            'attendances',
            'stats',
            'month',
            'year',
            'startDate',
            'endDate'
        ));
    }

    public function export(Request $request)
    {       
        return redirect()->back()->with('info', 'Fitur export akan segera tersedia!');
    }
}