<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Faculty;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin account
        Admin::create([
            'username' => 'admin',
            'name' => 'Administrator',
            'password' => Hash::make('admin123'),
        ]);

        // Create faculties
        $faculties = [
            ['name' => 'Fakultas Teknik', 'code' => 'FT'],
            ['name' => 'Fakultas Ekonomi', 'code' => 'FE'],
            ['name' => 'Fakultas Hukum', 'code' => 'FH'],
            ['name' => 'Fakultas Kedokteran', 'code' => 'FK'],
            ['name' => 'Fakultas MIPA', 'code' => 'FMIPA'],
            ['name' => 'Fakultas Pertanian', 'code' => 'FP'],
        ];

        foreach ($faculties as $faculty) {
            Faculty::create($faculty);
        }
    }
}
