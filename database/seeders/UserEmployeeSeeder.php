<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserEmployeeSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'user_role_id' => 1,
            'name' => 'Risa',
            'email' => 'hdc@gmail.com',
            'password' => Hash::make('mars2000'),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'user_role_id' => 2,
            'name' => 'Mba HRD',
            'email' => 'hrd@company.com',
            'password' => Hash::make('12345678'),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'user_role_id' => 3,
            'name' => 'Mas Finance',
            'email' => 'finance@company.com',
            'password' => Hash::make('12345678'),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'user_role_id' => 4,
            'name' => 'Mas Manager',
            'email' => 'manager@company.com',
            'password' => Hash::make('12345678'),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $userId = DB::table('users')->insertGetId([
            'user_role_id' => 5,
            'name' => 'Budi Karyawan',
            'email' => 'budi@company.com',
            'password' => Hash::make('12345678'),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // Tambahkan data detail karyawan untuk si Budi
        // Kita anggap Budi jabatannya 'Senior Developer' (Position ID 2) dan Dept 'IT' (Dept ID 1)
        DB::table('employees')->insert([
            'user_id' => $userId,
            'position_id' => 2, 
            'department_id' => 1,
            'nik' => '24212384392',
            'nip' => 'EMP2024001',
            'phone_number' => '08123456789',
            'address' => 'Jl. Programmer No. 1',
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }
}