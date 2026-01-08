<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'position_id' => 'required',
            'department_id' => 'required',
            'nip' => 'required|unique:employees',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'user_role_id' => 5,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('12345678'),
            ]);

            Employee::create([
                'user_id' => $user->id,
                'position_id' => $request->position_id,
                'department_id' => $request->department_id,
                'nik' => $request->nik,
                'nip' => $request->nip,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
            ]);
        });

        return redirect()->back()->with('success', 'Karyawan berhasil ditambahkan');
    }
}
