<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Models\Position;
use App\Models\Department;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['user', 'position', 'department']);

        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        if ($request->filled('position')) {
            $query->where('position_id', $request->position);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nik', 'like', '%' . $request->search . '%')
                  ->orWhere('nip', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $employees = $query->latest()->paginate(10);
        $departments = Department::active()->get();
        $positions = Position::all();

        return view('hrd.employees.index', compact('employees', 'departments', 'positions'));
    }

    public function create()
    {
        $users = User::whereDoesntHave('employee')
                     ->where('status', 1)
                     ->orderBy('name')
                     ->get();
        
        $departments = Department::active()->get();
        $positions = Position::all();

        return view('hrd.employees.create', compact('users', 'departments', 'positions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:employees,user_id',
            'position_id' => 'required|exists:positions,id',
            'department_id' => 'required|exists:departments,id',
            'nik' => 'required|string|max:255|unique:employees,nik',
            'nip' => 'required|string|max:255|unique:employees,nip',
            'phone_number' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
        ], [
            'user_id.required' => 'User harus dipilih',
            'user_id.exists' => 'User tidak ditemukan',
            'user_id.unique' => 'User sudah terdaftar sebagai karyawan',
            'position_id.required' => 'Jabatan harus dipilih',
            'department_id.required' => 'Departemen harus dipilih',
            'nik.required' => 'NIK harus diisi',
            'nik.unique' => 'NIK sudah digunakan',
            'nip.required' => 'NIP harus diisi',
            'nip.unique' => 'NIP sudah digunakan',
        ]);

        Employee::create($validated);

        return redirect()->route('hrd.employees.index')
            ->with('success', 'Data karyawan berhasil ditambahkan!');
    }

    public function show(Employee $employee)
    {
        $employee->load(['user', 'position', 'department', 'attendances' => function($query) {
            $query->latest()->take(10);
        }]);

        return view('hrd.employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::active()->get();
        $positions = Position::all();

        return view('hrd.employees.edit', compact('employee', 'departments', 'positions'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'position_id' => 'required|exists:positions,id',
            'department_id' => 'required|exists:departments,id',
            'nik' => 'required|string|max:255|unique:employees,nik,' . $employee->id,
            'nip' => 'required|string|max:255|unique:employees,nip,' . $employee->id,
            'phone_number' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
        ], [
            'position_id.required' => 'Jabatan harus dipilih',
            'department_id.required' => 'Departemen harus dipilih',
            'nik.required' => 'NIK harus diisi',
            'nik.unique' => 'NIK sudah digunakan',
            'nip.required' => 'NIP harus diisi',
            'nip.unique' => 'NIP sudah digunakan',
        ]);

        $employee->update($validated);

        return redirect()->route('hrd.employees.index')
            ->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->attendances()->count() > 0) {
            return redirect()->route('hrd.employees.index')
                ->with('error', 'Karyawan tidak dapat dihapus karena memiliki data absensi!');
        }

        if ($employee->payrolls()->count() > 0) {
            return redirect()->route('hrd.employees.index')
                ->with('error', 'Karyawan tidak dapat dihapus karena memiliki data penggajian!');
        }

        $employee->delete();

        return redirect()->route('hrd.employees.index')
            ->with('success', 'Data karyawan berhasil dihapus!');
    }
}