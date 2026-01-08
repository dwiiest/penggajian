<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function index()
    {
        $roles = UserRole::withCount('users')->latest()->get();
        return view('admin.user-roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.user-roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:user_roles,name',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
        ], [
            'name.required' => 'Nama role harus diisi',
            'name.unique' => 'Nama role sudah digunakan',
            'status.required' => 'Status harus dipilih',
        ]);

        UserRole::create($validated);

        return redirect()->route('admin.user-roles.index')
            ->with('success', 'Role berhasil ditambahkan!');
    }

    public function show(UserRole $userRole)
    {
        $userRole->load(['users' => function($query) {
            $query->latest()->take(10);
        }]);
        
        return view('admin.user-roles.show', compact('userRole'));
    }

    public function edit(UserRole $userRole)
    {
        return view('admin.user-roles.edit', compact('userRole'));
    }

    public function update(Request $request, UserRole $userRole)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:user_roles,name,' . $userRole->id,
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
        ], [
            'name.required' => 'Nama role harus diisi',
            'name.unique' => 'Nama role sudah digunakan',
            'status.required' => 'Status harus dipilih',
        ]);

        $userRole->update($validated);

        return redirect()->route('admin.user-roles.index')
            ->with('success', 'Role berhasil diperbarui!');
    }

    public function destroy(UserRole $userRole)
    {
        // Check if role has users
        if ($userRole->users()->count() > 0) {
            return redirect()->route('admin.user-roles.index')
                ->with('error', 'Role tidak dapat dihapus karena masih memiliki user!');
        }

        $userRole->delete();

        return redirect()->route('admin.user-roles.index')
            ->with('success', 'Role berhasil dihapus!');
    }

    public function toggleStatus(UserRole $userRole)
    {
        $userRole->update([
            'status' => $userRole->status == 1 ? 0 : 1
        ]);

        return redirect()->back()
            ->with('success', 'Status role berhasil diubah!');
    }
}
