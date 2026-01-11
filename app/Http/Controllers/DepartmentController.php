<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,hrd');
    }

    public function index(Request $request)
    {
        $query = Department::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $departments = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:0,1',
        ]);

        $data['status'] = $data['status'] ?? 1;

        Department::create($data);

        return redirect()->route('admin.departments.index')
                         ->with('success', 'Department berhasil ditambahkan.');
    }

    public function show(Department $department)
    {
        return view('admin.departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:0,1',
        ]);

        $data['status'] = $data['status'] ?? 0;

        $department->update($data);

        return redirect()->route('admin.departments.index')
                         ->with('success', 'Department berhasil diperbarui.');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('admin.departments.index')
                         ->with('success', 'Department berhasil dihapus.');
    }

    public function toggleStatus(Department $department)
    {
        $department->status = $department->status == 1 ? 0 : 1;
        $department->save();

        return redirect()->back()->with('success', 'Status department berhasil diubah.');
    }
}
