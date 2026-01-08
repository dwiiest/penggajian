<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    public function run()
    {
        $positions = [
            [
                'title' => 'Manager',
                'base_salary' => 10000000,
                'transport_allowance' => 1000000,
                'meal_allowance' => 50000,
            ],
            [
                'title' => 'Senior Developer',
                'base_salary' => 8000000,
                'transport_allowance' => 500000,
                'meal_allowance' => 40000,
            ],
            [
                'title' => 'Staff Admin',
                'base_salary' => 5000000,
                'transport_allowance' => 300000,
                'meal_allowance' => 30000,
            ],
        ];

        DB::table('positions')->insert($positions);
    }
}