<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Risa', 
            'email' => 'hdc.@gmail.com',
            'password' => Hash::make('mars2000'), 
            'users_role_id' => 1, 
            'status_akun' => 1,
        ]);
    }
}
