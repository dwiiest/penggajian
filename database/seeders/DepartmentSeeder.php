<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $depts = [
            ['name' => 'IT Department', 'description' => 'Bagian Teknologi Informasi'],
            ['name' => 'HR Department', 'description' => 'Human Resources'],
            ['name' => 'Finance Department', 'description' => 'Keuangan perusahaan'],
        ];

        DB::table('departments')->insert($depts);
    }
}