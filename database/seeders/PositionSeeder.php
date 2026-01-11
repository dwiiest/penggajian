<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        $positions = [
            [
                'title' => 'Manager',
                'base_salary' => 10000000,
                'transport_allowance' => 1000000,
                'meal_allowance' => 50000,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Senior Developer',
                'base_salary' => 8000000,
                'transport_allowance' => 500000,
                'meal_allowance' => 40000,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Staff Admin',
                'base_salary' => 5000000,
                'transport_allowance' => 300000,
                'meal_allowance' => 30000,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Marketing Staff',
                'base_salary' => 5500000,
                'transport_allowance' => 700000,
                'meal_allowance' => 500000,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Accountant',
                'base_salary' => 8000000,
                'transport_allowance' => 600000,
                'meal_allowance' => 600000,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('positions')->insert($positions);
    }
}