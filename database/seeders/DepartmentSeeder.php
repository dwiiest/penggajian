<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        $depts = [
            [
                'name' => 'IT Department', 
                'description' => 'Bagian Teknologi Informasi',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'HR Department', 
                'description' => 'Human Resources',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Finance Department', 
                'description' => 'Keuangan perusahaan',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('departments')->insert($depts);
    }
}