<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        // Ambil ID employee si Budi (biasanya ID 1 karena dia employee pertama yang kita insert)
        $employee = DB::table('employees')->where('nip', 'EMP2026001')->first();

        if ($employee) {
            $attendances = [
                [
                    'employee_id' => $employee->id,
                    'date' => '2024-01-01',
                    'time_in' => '08:00:00',
                    'time_out' => '17:00:00',
                    'status' => 'hadir',
                    'note' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'employee_id' => $employee->id,
                    'date' => '2024-01-02',
                    'time_in' => '08:05:00',
                    'time_out' => '17:10:00',
                    'status' => 'hadir',
                    'note' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'employee_id' => $employee->id,
                    'date' => '2024-01-03',
                    'time_in' => null,
                    'time_out' => null,
                    'status' => 'sakit',
                    'note' => 'Demam',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            DB::table('attendances')->insert($attendances);
        }
    }
}