<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Department;
use App\Models\Position;

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
        return view('hrd.index');
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
