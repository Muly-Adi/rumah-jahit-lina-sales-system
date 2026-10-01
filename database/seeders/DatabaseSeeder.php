<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Customer Seeder
        User::create([
            'nama' => 'Customer 1',
            'username' => 'customer1',
            'email' => 'customer1@example.com',
            'no_hp' => '081234567890',
            'password' => bcrypt('password123'),
            'alamat' => 'Jl. Example No. 1, City',
            'role' => 'customer',
        ]);

        // Admin Seeder
        User::create([
            'nama' => 'Admin 1',
            'username' => 'admin1',
            'email' => 'admin1@example.com',
            'no_hp' => '081234567891',
            'password' => bcrypt('password123'),
            'alamat' => 'Jl. Example No. 2, City',
            'role' => 'admin',
        ]);

        // Karyawan Seeder
        User::create([
            'nama' => 'Karyawan 1',
            'username' => 'karyawan1',
            'email' => 'karyawan1@example.com',
            'no_hp' => '081234567892',
            'password' => bcrypt('password123'),
            'alamat' => 'Jl. Example No. 3, City',
            'role' => 'karyawan',
        ]);
    }
}
