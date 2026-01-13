<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Overtime;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class KaryawanController extends Controller
{
    public function index()
    {
        $employee = auth()->user()->employee;
        
        if (!$employee) {
            return redirect()->route('login')->with('error', 'Anda belum terdaftar sebagai karyawan!');
        }

        $todayAttendance = Attendance::where('employee_id', $employee->id)
                                    ->whereDate('date', today())
                                    ->first();

        $thisMonth = now()->format('m');
        $thisYear = now()->format('Y');

        $monthlyAttendance = Attendance::where('employee_id', $employee->id)
                                      ->whereMonth('date', $thisMonth)
                                      ->whereYear('date', $thisYear)
                                      ->get();

        $stats = [
            'total_days' => $monthlyAttendance->count(),
            'hadir' => $monthlyAttendance->where('status', 'hadir')->count(),
            'terlambat' => $monthlyAttendance->where('status', 'terlambat')->count(),
            'izin' => $monthlyAttendance->whereIn('status', ['izin', 'sakit', 'cuti'])->count(),
            'alpha' => $monthlyAttendance->where('status', 'alpha')->count(),
        ];

        $recentAttendances = Attendance::where('employee_id', $employee->id)
                                      ->latest('date')
                                      ->take(7)
                                      ->get();

        $overtimeStats = [
            'total' => Overtime::where('employee_id', $employee->id)
                              ->whereMonth('date', $thisMonth)
                              ->whereYear('date', $thisYear)
                              ->count(),
            'approved' => Overtime::where('employee_id', $employee->id)
                                ->whereMonth('date', $thisMonth)
                                ->whereYear('date', $thisYear)
                                ->where('status', 'approved')
                                ->count(),
            'pending' => Overtime::where('employee_id', $employee->id)
                               ->whereMonth('date', $thisMonth)
                               ->whereYear('date', $thisYear)
                               ->where('status', 'pending')
                               ->count(),
            'total_pay' => Overtime::where('employee_id', $employee->id)
                                 ->whereMonth('date', $thisMonth)
                                 ->whereYear('date', $thisYear)
                                 ->where('status', 'approved')
                                 ->sum('total_pay'),
        ];

        // Get latest payroll
        $latestPayroll = Payroll::where('employee_id', $employee->id)
                                ->latest('year')
                                ->latest('month')
                                ->first();

        return view('karyawan.index', compact(
            'employee',
            'todayAttendance',
            'stats',
            'recentAttendances',
            'overtimeStats',
            'latestPayroll'
        ));
    }

    public function profile()
    {
        $employee = auth()->user()->employee;
        
        if (!$employee) {
            return redirect()->route('login')->with('error', 'Anda belum terdaftar sebagai karyawan!');
        }

        return view('karyawan.profile', compact('employee'));
    }

    public function attendances(Request $request)
    {
        $employee = auth()->user()->employee;
        
        $month = $request->input('month', now()->format('m'));
        $year = $request->input('year', now()->format('Y'));

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $attendances = Attendance::where('employee_id', $employee->id)
                                ->whereBetween('date', [$startDate, $endDate])
                                ->orderBy('date', 'desc')
                                ->paginate(15);

        $stats = [
            'total_days' => Attendance::where('employee_id', $employee->id)
                                     ->whereBetween('date', [$startDate, $endDate])
                                     ->count(),
            'hadir' => Attendance::where('employee_id', $employee->id)
                                ->whereBetween('date', [$startDate, $endDate])
                                ->where('status', 'hadir')
                                ->count(),
            'terlambat' => Attendance::where('employee_id', $employee->id)
                                    ->whereBetween('date', [$startDate, $endDate])
                                    ->where('status', 'terlambat')
                                    ->count(),
            'izin' => Attendance::where('employee_id', $employee->id)
                               ->whereBetween('date', [$startDate, $endDate])
                               ->where('status', 'izin')
                               ->count(),
            'sakit' => Attendance::where('employee_id', $employee->id)
                                ->whereBetween('date', [$startDate, $endDate])
                                ->where('status', 'sakit')
                                ->count(),
            'alpha' => Attendance::where('employee_id', $employee->id)
                                ->whereBetween('date', [$startDate, $endDate])
                                ->where('status', 'alpha')
                                ->count(),
            'cuti' => Attendance::where('employee_id', $employee->id)
                               ->whereBetween('date', [$startDate, $endDate])
                               ->where('status', 'cuti')
                               ->count(),
        ];

        return view('karyawan.attendances', compact(
            'employee',
            'attendances',
            'stats',
            'month',
            'year',
            'startDate',
            'endDate'
        ));
    }

    public function overtimes(Request $request)
    {
        $employee = auth()->user()->employee;
        
        $status = $request->input('status');
        $month = $request->input('month', now()->format('m'));
        $year = $request->input('year', now()->format('Y'));

        $query = Overtime::where('employee_id', $employee->id)
                        ->whereMonth('date', $month)
                        ->whereYear('date', $year);

        if ($status) {
            $query->where('status', $status);
        }

        $overtimes = $query->latest('date')->get();

        $stats = [
            'total' => $overtimes->count(),
            'pending' => $overtimes->where('status', 'pending')->count(),
            'approved' => $overtimes->where('status', 'approved')->count(),
            'rejected' => $overtimes->where('status', 'rejected')->count(),
            'total_hours' => $overtimes->where('status', 'approved')->sum('total_hours'),
            'total_pay' => $overtimes->where('status', 'approved')->sum('total_pay'),
        ];

        return view('karyawan.overtimes', compact(
            'employee',
            'overtimes',
            'stats',
            'month',
            'year'
        ));
    }

    public function clockIn(Request $request)
    {
        $employee = auth()->user()->employee;
        
        if (!$employee) {
            return redirect()->back()->with('error', 'Anda belum terdaftar sebagai karyawan!');
        }

        $todayAttendance = Attendance::where('employee_id', $employee->id)
                                    ->whereDate('date', today())
                                    ->first();

        if ($todayAttendance && $todayAttendance->time_in) {
            return redirect()->back()->with('warning', 'Anda sudah melakukan clock in hari ini!');
        }

        $timeIn = now()->format('H:i:s');
        $workStartTime = '08:00:00';
        
        $status = $timeIn <= $workStartTime ? 'hadir' : 'terlambat';

        if ($todayAttendance) {
            $todayAttendance->update([
                'time_in' => $timeIn,
                'status' => $status,
            ]);
        } else {
            Attendance::create([
                'employee_id' => $employee->id,
                'date' => today(),
                'time_in' => $timeIn,
                'status' => $status,
            ]);
        }

        $message = $status === 'hadir' 
            ? 'Clock In berhasil! Selamat bekerja!' 
            : 'Clock In berhasil! Anda terlambat.';

        return redirect()->back()->with('success', $message);
    }

    public function clockOut(Request $request)
    {
        $employee = auth()->user()->employee;
        
        if (!$employee) {
            return redirect()->back()->with('error', 'Anda belum terdaftar sebagai karyawan!');
        }

        $todayAttendance = Attendance::where('employee_id', $employee->id)
                                    ->whereDate('date', today())
                                    ->first();

        if (!$todayAttendance || !$todayAttendance->time_in) {
            return redirect()->back()->with('error', 'Anda belum melakukan clock in hari ini!');
        }

        if ($todayAttendance->time_out) {
            return redirect()->back()->with('warning', 'Anda sudah melakukan clock out hari ini!');
        }

        $todayAttendance->update([
            'time_out' => now()->format('H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Clock Out berhasil! Terima kasih atas kerja keras Anda hari ini!');
    }

    public function submitOvertime(Request $request)
    {
        $employee = auth()->user()->employee;
        
        if (!$employee) {
            return redirect()->back()->with('error', 'Anda belum terdaftar sebagai karyawan!');
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'required|string|max:500',
        ], [
            'date.required' => 'Tanggal harus diisi',
            'start_time.required' => 'Jam mulai harus diisi',
            'end_time.required' => 'Jam selesai harus diisi',
            'end_time.after' => 'Jam selesai harus setelah jam mulai',
            'description.required' => 'Deskripsi pekerjaan harus diisi',
        ]);

        $totalHours = Overtime::calculateHours($validated['start_time'], $validated['end_time']);
        
        $hourlyRate = 50000; // Rp 50,000 per jam
        $totalPay = Overtime::calculatePay($totalHours, $hourlyRate);

        Overtime::create([
            'employee_id' => $employee->id,
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'total_hours' => $totalHours,
            'hourly_rate' => $hourlyRate,
            'total_pay' => $totalPay,
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Pengajuan lembur berhasil dikirim! Menunggu persetujuan HRD.');
    }

    public function payslips(Request $request)
    {
        $employee = auth()->user()->employee;
        
        if (!$employee) {
            return redirect()->route('login')->with('error', 'Anda belum terdaftar sebagai karyawan!');
        }

        $year = $request->input('year', now()->format('Y'));

        $payrolls = Payroll::where('employee_id', $employee->id)
                          ->where('year', $year)
                          ->orderBy('year', 'desc')
                          ->orderByRaw("FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December') DESC")
                          ->paginate(12);

        $years = Payroll::where('employee_id', $employee->id)
                       ->selectRaw('DISTINCT year')
                       ->orderBy('year', 'desc')
                       ->pluck('year');

        return view('karyawan.payslips', compact('employee', 'payrolls', 'years', 'year'));
    }

    public function downloadPayslip($id)
    {
        $payroll = Payroll::with([
            'employee.user',
            'employee.position',
            'employee.department'
        ])->findOrFail($id);

        $monthNumber = date('m', strtotime($payroll->month));
        $startDate = Carbon::create($payroll->year, $monthNumber, 1);
        $endDate = Carbon::create($payroll->year, $monthNumber, 1)->endOfMonth();

        $attendances = Attendance::where('employee_id', $payroll->employee_id)
                                ->whereBetween('date', [$startDate, $endDate])
                                ->get();

        $overtimes = Overtime::where('employee_id', $payroll->employee_id)
                            ->whereBetween('date', [$startDate, $endDate])
                            ->where('status', 'approved')
                            ->get();

        $breakdown = [
            'basic_salary' => $payroll->basic_salary,
            'transport_allowance' => $payroll->employee->position->transport_allowance * $attendances->where('status', 'hadir')->count(),
            'meal_allowance' => $payroll->employee->position->meal_allowance * $attendances->where('status', 'hadir')->count(),
            'overtime_pay' => $overtimes->sum('total_pay'),
            'total_allowance' => $payroll->total_allowance,
            'total_deduction' => $payroll->total_deduction,
            'net_salary' => $payroll->net_salary,
        ];

        $pdf = Pdf::loadView('finance.payrolls.payslip-pdf', compact(
            'payroll',
            'breakdown',
            'attendances',
            'overtimes'
        ));

        $filename = "Payslip-{$payroll->employee->user->name}-{$payroll->month}-{$payroll->year}.pdf";

        return $pdf->download($filename);
    }
}