<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController
{
    public function index()
    {
        return view('admin.index');
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
