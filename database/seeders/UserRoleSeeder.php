<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        $now = now();
        
        $roles = [
            ['name' => 'admin', 'description' => 'Administrator sistem', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'hrd', 'description' => 'Human Resource Development', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'finance', 'description' => 'Finance Department', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'manager', 'description' => 'Manager', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'karyawan', 'description' => 'Karyawan', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('user_roles')->insert($roles);
    }
}