<?php

namespace App\Http\Controllers;

use App\Exports\PayrollExport;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Attendance;
use App\Models\Overtime;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class FinanceController extends Controller
{
    /**
     * Dashboard Finance - Overview penggajian
     */
    public function index()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $monthName = now()->format('F');

        $monthlyPayrolls = Payroll::with(['employee.user', 'employee.position'])
                                 ->where('year', $currentYear)
                                 ->where('month', $monthName)
                                 ->get();

        $totalPayroll = $monthlyPayrolls->sum('net_salary');
        $totalEmployees = $monthlyPayrolls->count();
        
        $stats = [
            'total_employees' => $totalEmployees,
            'total_payroll' => $totalPayroll,
            'processed' => $monthlyPayrolls->where('status', 'paid')->count(),
            'pending' => $monthlyPayrolls->where('status', 'draft')->count(),
            'average_salary' => $totalEmployees > 0 ? $totalPayroll / $totalEmployees : 0,
        ];

        $recentPayrolls = Payroll::with(['employee.user', 'employee.position', 'employee.department'])
                                ->latest()
                                ->take(10)
                                ->get();

        $chartData = $this->getPayrollChartData();

        $departmentSummary = DB::table('payrolls')
            ->join('employees', 'payrolls.employee_id', '=', 'employees.id')
            ->join('departments', 'employees.department_id', '=', 'departments.id')
            ->where('payrolls.year', $currentYear)
            ->where('payrolls.month', $monthName)
            ->groupBy('departments.id', 'departments.name')
            ->select(
                'departments.name as department_name',
                DB::raw('COUNT(payrolls.id) as employee_count'),
                DB::raw('SUM(payrolls.basic_salary) as basic_salary'),
                DB::raw('SUM(payrolls.total_allowance) as total_allowance'),
                DB::raw('SUM(payrolls.total_deduction) as total_deduction'),
                DB::raw('SUM(payrolls.net_salary) as net_salary'),
                DB::raw('SUM(CASE WHEN payrolls.status = "paid" THEN 1 ELSE 0 END) as paid_count')
            )
            ->get();

        $departmentChartData = [
            'labels' => $departmentSummary->pluck('department_name')->toArray(),
            'data' => $departmentSummary->pluck('employee_count')->toArray(),
        ];

        return view('finance.index', compact(
            'stats',
            'monthlyPayrolls',
            'recentPayrolls',
            'chartData',
            'departmentSummary',
            'departmentChartData',
            'currentMonth',
            'currentYear',
            'monthName'
        ));
    }

    /**
     * Halaman daftar penggajian
     */
    public function payrolls(Request $request)
    {
        $month = $request->input('month', now()->format('F'));
        $year = $request->input('year', now()->year);
        $status = $request->input('status');

        $query = Payroll::with(['employee.user', 'employee.position', 'employee.department'])
                       ->where('month', $month)
                       ->where('year', $year);

        if ($status) {
            $query->where('status', $status);
        }

        $payrolls = $query->latest()->paginate(20);

        // Summary
        $summary = [
            'total' => $payrolls->total(),
            'total_salary' => Payroll::where('month', $month)
                                    ->where('year', $year)
                                    ->sum('net_salary'),
            'total_allowance' => Payroll::where('month', $month)
                                       ->where('year', $year)
                                       ->sum('total_allowance'),
            'total_deduction' => Payroll::where('month', $month)
                                       ->where('year', $year)
                                       ->sum('total_deduction'),
            'draft' => Payroll::where('month', $month)
                             ->where('year', $year)
                             ->where('status', 'draft')
                             ->count(),
            'paid' => Payroll::where('month', $month)
                            ->where('year', $year)
                            ->where('status', 'paid')
                            ->count(),
        ];

        // Years untuk filter
        $years = Payroll::selectRaw('DISTINCT year')
                       ->orderBy('year', 'desc')
                       ->pluck('year');

        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        return view('finance.payrolls.index', compact(
            'payrolls',
            'summary',
            'month',
            'year',
            'status',
            'years',
            'months'
        ));
    }

    /**
     * Generate/Create new payroll
     */
    public function create()
    {
        $employees = Employee::with(['user', 'position', 'department'])
                           ->whereHas('user', function($q) {
                               $q->where('status', 1);
                           })
                           ->get();

        $month = now()->format('F');
        $year = now()->year;

        // Check existing payrolls
        $existingPayrolls = Payroll::where('month', $month)
                                  ->where('year', $year)
                                  ->pluck('employee_id')
                                  ->toArray();

        return view('finance.payrolls.create', compact(
            'employees',
            'month',
            'year',
            'existingPayrolls'
        ));
    }

    /**
     * Generate payroll untuk karyawan yang dipilih
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|string',
            'year' => 'required|integer',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
        ]);

        $month = $validated['month'];
        $year = $validated['year'];
        $successCount = 0;
        $skipCount = 0;
        $errors = [];

        foreach ($validated['employee_ids'] as $employeeId) {
            // Check if payroll already exists
            $exists = Payroll::where('employee_id', $employeeId)
                            ->where('month', $month)
                            ->where('year', $year)
                            ->exists();

            if ($exists) {
                $skipCount++;
                continue;
            }

            try {
                $this->generatePayrollForEmployee($employeeId, $month, $year);
                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Employee ID {$employeeId}: {$e->getMessage()}";
            }
        }

        $message = "Berhasil generate {$successCount} payroll";
        if ($skipCount > 0) {
            $message .= ", {$skipCount} sudah ada";
        }
        if (count($errors) > 0) {
            $message .= ", " . count($errors) . " error";
        }

        return redirect()->route('finance.payrolls.index', [
            'month' => $month,
            'year' => $year
        ])->with('success', $message);
    }

    /**
     * Generate payroll untuk satu karyawan
     */
    private function generatePayrollForEmployee($employeeId, $month, $year)
    {
        $employee = Employee::with('position')->findOrFail($employeeId);
        
        // Convert month name to number
        $monthNumber = date('m', strtotime($month));
        
        // Hitung jumlah hari kerja di bulan tersebut
        $startDate = Carbon::create($year, $monthNumber, 1);
        $endDate = Carbon::create($year, $monthNumber, 1)->endOfMonth();
        
        // Basic salary dari position
        $basicSalary = $employee->position->base_salary;
        
        // Hitung attendance untuk allowance harian
        $attendances = Attendance::where('employee_id', $employeeId)
                                ->whereBetween('date', [$startDate, $endDate])
                                ->where('status', 'hadir')
                                ->count();
        
        // Allowances
        $transportAllowance = $employee->position->transport_allowance * $attendances;
        $mealAllowance = $employee->position->meal_allowance * $attendances;
        
        // Overtime pay
        $overtimePay = Overtime::where('employee_id', $employeeId)
                              ->whereBetween('date', [$startDate, $endDate])
                              ->where('status', 'approved')
                              ->sum('total_pay');
        
        $totalAllowance = $transportAllowance + $mealAllowance + $overtimePay;
        
        // Deductions (bisa dikembangkan: BPJS, pajak, dll)
        $totalDeduction = 0;
        
        // Calculate net salary
        $netSalary = ($basicSalary + $totalAllowance) - $totalDeduction;
        
        // Create payroll
        Payroll::create([
            'employee_id' => $employeeId,
            'month' => $month,
            'year' => $year,
            'basic_salary' => $basicSalary,
            'total_allowance' => $totalAllowance,
            'total_deduction' => $totalDeduction,
            'net_salary' => $netSalary,
            'status' => 'draft',
        ]);
    }

    /**
     * Show detail payroll
     */
    public function show($id)
    {
        $payroll = Payroll::with([
            'employee.user',
            'employee.position',
            'employee.department'
        ])->findOrFail($id);

        // Get attendance details
        $monthNumber = date('m', strtotime($payroll->month));
        $startDate = Carbon::create($payroll->year, $monthNumber, 1);
        $endDate = Carbon::create($payroll->year, $monthNumber, 1)->endOfMonth();

        $attendances = Attendance::where('employee_id', $payroll->employee_id)
                                ->whereBetween('date', [$startDate, $endDate])
                                ->get();

        // Get overtime details
        $overtimes = Overtime::where('employee_id', $payroll->employee_id)
                            ->whereBetween('date', [$startDate, $endDate])
                            ->where('status', 'approved')
                            ->get();

        // Breakdown calculation
        $breakdown = [
            'basic_salary' => $payroll->basic_salary,
            'transport_allowance' => $payroll->employee->position->transport_allowance * $attendances->where('status', 'hadir')->count(),
            'meal_allowance' => $payroll->employee->position->meal_allowance * $attendances->where('status', 'hadir')->count(),
            'overtime_pay' => $overtimes->sum('total_pay'),
            'total_allowance' => $payroll->total_allowance,
            'total_deduction' => $payroll->total_deduction,
            'net_salary' => $payroll->net_salary,
        ];

        $attendanceStats = [
            'total' => $attendances->count(),
            'hadir' => $attendances->where('status', 'hadir')->count(),
            'terlambat' => $attendances->where('status', 'terlambat')->count(),
            'izin' => $attendances->whereIn('status', ['izin', 'sakit', 'cuti'])->count(),
            'alpha' => $attendances->where('status', 'alpha')->count(),
        ];

        return view('finance.payrolls.show', compact(
            'payroll',
            'breakdown',
            'attendances',
            'overtimes',
            'attendanceStats'
        ));
    }

    /**
     * Edit payroll
     */
    public function edit($id)
    {
        $payroll = Payroll::with([
            'employee.user',
            'employee.position',
            'employee.department'
        ])->findOrFail($id);

        if ($payroll->status === 'paid') {
            return redirect()->back()->with('error', 'Payroll yang sudah dibayar tidak dapat diedit!');
        }

        return view('finance.payrolls.edit', compact('payroll'));
    }

    /**
     * Update payroll
     */
    public function update(Request $request, $id)
    {
        $payroll = Payroll::findOrFail($id);

        if ($payroll->status === 'paid') {
            return redirect()->back()->with('error', 'Payroll yang sudah dibayar tidak dapat diubah!');
        }

        $validated = $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'total_allowance' => 'required|numeric|min:0',
            'total_deduction' => 'required|numeric|min:0',
        ]);

        $netSalary = ($validated['basic_salary'] + $validated['total_allowance']) - $validated['total_deduction'];

        $payroll->update([
            'basic_salary' => $validated['basic_salary'],
            'total_allowance' => $validated['total_allowance'],
            'total_deduction' => $validated['total_deduction'],
            'net_salary' => $netSalary,
        ]);

        return redirect()->route('finance.payrolls.show', $payroll->id)
                        ->with('success', 'Payroll berhasil diupdate!');
    }

    /**
     * Approve/Pay payroll
     */
    public function pay($id)
    {
        $payroll = Payroll::findOrFail($id);

        if ($payroll->status === 'paid') {
            return redirect()->back()->with('warning', 'Payroll sudah dibayar!');
        }

        $payroll->update([
            'status' => 'paid',
            'payment_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Payroll berhasil dibayar!');
    }

    /**
     * Bulk pay payrolls
     */
    public function bulkPay(Request $request)
    {
        $validated = $request->validate([
            'payroll_ids' => 'required|array',
            'payroll_ids.*' => 'exists:payrolls,id',
        ]);

        $count = 0;
        foreach ($validated['payroll_ids'] as $id) {
            $payroll = Payroll::find($id);
            if ($payroll && $payroll->status !== 'paid') {
                $payroll->update([
                    'status' => 'paid',
                    'payment_date' => now(),
                ]);
                $count++;
            }
        }

        return redirect()->back()->with('success', "{$count} payroll berhasil dibayar!");
    }

    /**
     * Delete payroll
     */
    public function destroy($id)
    {
        $payroll = Payroll::findOrFail($id);

        if ($payroll->status === 'paid') {
            return redirect()->back()->with('error', 'Payroll yang sudah dibayar tidak dapat dihapus!');
        }

        $payroll->delete();

        return redirect()->route('finance.payrolls.index')
                        ->with('success', 'Payroll berhasil dihapus!');
    }

    /**
     * Export payroll to Excel
     */
    public function export(Request $request)
    {
        $month = $request->input('month', now()->format('F'));
        $year = $request->input('year', now()->year);

        $payrolls = Payroll::with(['employee.user', 'employee.position', 'employee.department'])
                          ->where('month', $month)
                          ->where('year', $year)
                          ->get();

        return Excel::download(
            new PayrollExport($payrolls, $month, $year),
            "Payroll-{$month}-{$year}.xlsx"
        );
    }

    /**
     * Download payslip PDF untuk karyawan tertentu
     */
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

    /**
     * Laporan keuangan
     */
    public function reports(Request $request)
    {
        $year = $request->input('year', now()->year);

        // Data per bulan
        $monthlyData = [];
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        foreach ($months as $month) {
            $monthlyData[$month] = [
                'total_employees' => Payroll::where('month', $month)
                                           ->where('year', $year)
                                           ->count(),
                'total_salary' => Payroll::where('month', $month)
                                        ->where('year', $year)
                                        ->sum('net_salary'),
                'total_allowance' => Payroll::where('month', $month)
                                           ->where('year', $year)
                                           ->sum('total_allowance'),
                'total_deduction' => Payroll::where('month', $month)
                                           ->where('year', $year)
                                           ->sum('total_deduction'),
            ];
        }

        // Summary tahunan
        $yearlyData = [
            'total_employees' => Payroll::where('year', $year)->distinct('employee_id')->count('employee_id'),
            'total_salary' => Payroll::where('year', $year)->sum('net_salary'),
            'total_allowance' => Payroll::where('year', $year)->sum('total_allowance'),
            'total_deduction' => Payroll::where('year', $year)->sum('total_deduction'),
        ];

        // Years untuk filter
        $years = Payroll::selectRaw('DISTINCT year')
                       ->orderBy('year', 'desc')
                       ->pluck('year');

        return view('finance.reports.index', compact(
            'monthlyData',
            'yearlyData',
            'year',
            'years',
            'months'
        ));
    }

    /**
     * Get chart data untuk dashboard
     */
    private function getPayrollChartData()
    {
        $data = [];
        $months = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('F');
            $year = $date->year;
            
            $total = Payroll::where('month', $monthName)
                          ->where('year', $year)
                          ->sum('net_salary');
            
            $months[] = $date->format('M Y');
            $data[] = $total;
        }
        
        return [
            'labels' => $months,
            'data' => $data,
        ];
    }
}