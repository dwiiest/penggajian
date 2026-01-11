<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['employee.user', 'employee.position', 'employee.department']);

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        } else {
            $query->whereDate('date', today());
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

        $attendances = $query->latest('date')->latest('time_in')->paginate(15);
        
        $departments = Department::active()->get();

        $today = today();
        $stats = [
            'total' => Attendance::whereDate('date', $today)->count(),
            'hadir' => Attendance::whereDate('date', $today)->where('status', 'hadir')->count(),
            'terlambat' => Attendance::whereDate('date', $today)->where('status', 'terlambat')->count(),
            'izin' => Attendance::whereDate('date', $today)->whereIn('status', ['izin', 'sakit', 'cuti'])->count(),
            'alpha' => Attendance::whereDate('date', $today)->where('status', 'alpha')->count(),
        ];

        return view('hrd.attendances.index', compact('attendances', 'departments', 'stats'));
    }

    public function create()
    {
        $employees = Employee::with(['user', 'position', 'department'])
                            ->whereHas('user', function($q) {
                                $q->where('status', 1);
                            })
                            ->get();

        return view('hrd.attendances.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i|after:time_in',
            'status' => 'required|in:hadir,terlambat,izin,sakit,alpha,cuti',
            'note' => 'nullable|string',
        ], [
            'employee_id.required' => 'Karyawan harus dipilih',
            'date.required' => 'Tanggal harus diisi',
            'status.required' => 'Status harus dipilih',
            'time_out.after' => 'Jam keluar harus setelah jam masuk',
        ]);

        $exists = Attendance::where('employee_id', $validated['employee_id'])
                           ->whereDate('date', $validated['date'])
                           ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Absensi untuk karyawan ini pada tanggal tersebut sudah ada!');
        }

        Attendance::create($validated);

        return redirect()->route('hrd.attendances.index')
            ->with('success', 'Data absensi berhasil ditambahkan!');
    }

    public function show(Attendance $attendance)
    {
        $attendance->load(['employee.user', 'employee.position', 'employee.department']);
        return view('hrd.attendances.show', compact('attendance'));
    }

    public function edit(Attendance $attendance)
    {
        return view('hrd.attendances.edit', compact('attendance'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i|after:time_in',
            'status' => 'required|in:hadir,terlambat,izin,sakit,alpha,cuti',
            'note' => 'nullable|string',
        ], [
            'date.required' => 'Tanggal harus diisi',
            'status.required' => 'Status harus dipilih',
            'time_out.after' => 'Jam keluar harus setelah jam masuk',
        ]);

        $attendance->update($validated);

        return redirect()->route('hrd.attendances.index')
            ->with('success', 'Data absensi berhasil diperbarui!');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('hrd.attendances.index')
            ->with('success', 'Data absensi berhasil dihapus!');
    }

    public function bulkCreate()
    {
        $employees = Employee::with(['user', 'position', 'department'])
                            ->whereHas('user', function($q) {
                                $q->where('status', 1);
                            })
                            ->get();

        return view('hrd.attendances.bulk-create', compact('employees'));
    }

    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.employee_id' => 'required|exists:employees,id',
            'attendances.*.time_in' => 'nullable|date_format:H:i',
            'attendances.*.time_out' => 'nullable|date_format:H:i',
            'attendances.*.status' => 'required|in:hadir,terlambat,izin,sakit,alpha,cuti',
            'attendances.*.note' => 'nullable|string',
        ]);

        $date = $validated['date'];
        $created = 0;

        foreach ($validated['attendances'] as $attendanceData) {
            if (empty($attendanceData['status'])) {
                continue;
            }

            $exists = Attendance::where('employee_id', $attendanceData['employee_id'])
                               ->whereDate('date', $date)
                               ->exists();

            if (!$exists) {
                Attendance::create([
                    'employee_id' => $attendanceData['employee_id'],
                    'date' => $date,
                    'time_in' => $attendanceData['time_in'] ?? null,
                    'time_out' => $attendanceData['time_out'] ?? null,
                    'status' => $attendanceData['status'],
                    'note' => $attendanceData['note'] ?? null,
                ]);
                $created++;
            }
        }

        return redirect()->route('hrd.attendances.index')
            ->with('success', "Berhasil menambahkan {$created} data absensi!");
    }
}