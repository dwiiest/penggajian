<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Overtime;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OvertimeController extends Controller
{
    public function index(Request $request)
    {
        $query = Overtime::with(['employee.user', 'employee.position', 'employee.department', 'approver']);

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('department_id', $request->department);
            });
        }

        if ($request->filled('search')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('nik', 'like', '%' . $request->search . '%')
                  ->orWhere('nip', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $overtimes = $query->latest('date')->latest('start_time')->paginate(15);
        
        $departments = Department::active()->get();

        $stats = [
            'total' => Overtime::count(),
            'pending' => Overtime::pending()->count(),
            'approved' => Overtime::approved()->count(),
            'rejected' => Overtime::where('status', 'rejected')->count(),
            'total_hours' => Overtime::approved()->sum('total_hours'),
            'total_pay' => Overtime::approved()->sum('total_pay'),
        ];

        return view('hrd.overtimes.index', compact('overtimes', 'departments', 'stats'));
    }

    public function create()
    {
        $employees = Employee::with(['user', 'position', 'department'])
                            ->whereHas('user', function($q) {
                                $q->where('status', 1);
                            })
                            ->get();

        return view('hrd.overtimes.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'hourly_rate' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ], [
            'employee_id.required' => 'Karyawan harus dipilih',
            'date.required' => 'Tanggal harus diisi',
            'start_time.required' => 'Jam mulai harus diisi',
            'end_time.required' => 'Jam selesai harus diisi',
            'hourly_rate.required' => 'Tarif per jam harus diisi',
        ]);

        $totalHours = Overtime::calculateHours($validated['start_time'], $validated['end_time']);
        $totalPay = Overtime::calculatePay($totalHours, $validated['hourly_rate']);

        $validated['total_hours'] = $totalHours;
        $validated['total_pay'] = $totalPay;
        $validated['status'] = 'pending';

        Overtime::create($validated);

        return redirect()->route('hrd.overtimes.index')
            ->with('success', 'Data lembur berhasil ditambahkan!');
    }

    public function show(Overtime $overtime)
    {
        $overtime->load(['employee.user', 'employee.position', 'employee.department', 'approver']);
        return view('hrd.overtimes.show', compact('overtime'));
    }

    public function edit(Overtime $overtime)
    {
        if ($overtime->status !== 'pending') {
            return redirect()->route('hrd.overtimes.index')
                ->with('error', 'Hanya lembur dengan status pending yang dapat diedit!');
        }

        return view('hrd.overtimes.edit', compact('overtime'));
    }

    public function update(Request $request, Overtime $overtime)
    {
        if ($overtime->status !== 'pending') {
            return redirect()->route('hrd.overtimes.index')
                ->with('error', 'Hanya lembur dengan status pending yang dapat diupdate!');
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'hourly_rate' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ], [
            'date.required' => 'Tanggal harus diisi',
            'start_time.required' => 'Jam mulai harus diisi',
            'end_time.required' => 'Jam selesai harus diisi',
            'hourly_rate.required' => 'Tarif per jam harus diisi',
        ]);

        $totalHours = Overtime::calculateHours($validated['start_time'], $validated['end_time']);
        $totalPay = Overtime::calculatePay($totalHours, $validated['hourly_rate']);

        $validated['total_hours'] = $totalHours;
        $validated['total_pay'] = $totalPay;

        $overtime->update($validated);

        return redirect()->route('hrd.overtimes.index')
            ->with('success', 'Data lembur berhasil diperbarui!');
    }

    public function destroy(Overtime $overtime)
    {
        if ($overtime->status !== 'pending') {
            return redirect()->route('hrd.overtimes.index')
                ->with('error', 'Hanya lembur dengan status pending yang dapat dihapus!');
        }

        $overtime->delete();

        return redirect()->route('hrd.overtimes.index')
            ->with('success', 'Data lembur berhasil dihapus!');
    }

    public function approve(Overtime $overtime)
    {
        if ($overtime->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Lembur sudah diproses sebelumnya!');
        }

        $overtime->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Lembur berhasil disetujui!');
    }

    public function reject(Overtime $overtime)
    {
        if ($overtime->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Lembur sudah diproses sebelumnya!');
        }

        $overtime->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Lembur berhasil ditolak!');
    }

    public function report(Request $request)
    {
        $month = $request->input('month', now()->format('m'));
        $year = $request->input('year', now()->format('Y'));
        $departmentId = $request->input('department');

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $query = Overtime::with(['employee.user', 'employee.position', 'employee.department'])
                        ->approved()
                        ->whereBetween('date', [$startDate, $endDate]);

        if ($departmentId) {
            $query->whereHas('employee', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        $overtimes = $query->get()->groupBy('employee_id');

        $reportData = [];
        foreach ($overtimes as $employeeId => $employeeOvertimes) {
            $employee = $employeeOvertimes->first()->employee;
            
            $reportData[] = [
                'employee' => $employee,
                'total_overtime' => $employeeOvertimes->count(),
                'total_hours' => $employeeOvertimes->sum('total_hours'),
                'total_pay' => $employeeOvertimes->sum('total_pay'),
                'overtimes' => $employeeOvertimes,
            ];
        }

        $departments = Department::active()->get();

        $overallStats = [
            'total_overtime' => $query->count(),
            'total_hours' => $query->sum('total_hours'),
            'total_pay' => $query->sum('total_pay'),
        ];

        return view('hrd.overtimes.report', compact(
            'reportData',
            'departments',
            'month',
            'year',
            'overallStats',
            'startDate',
            'endDate'
        ));
    }
}