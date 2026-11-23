<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator Flowchart Lab',
                'password' => Hash::make('123456'),
                'role' => 'admin',
                'school' => 'SMK Negeri 1 Rekayasa',
                'nis' => 'ADMIN-01',
                'kelas' => 'Administrator',
            ]
        );
    }
}
