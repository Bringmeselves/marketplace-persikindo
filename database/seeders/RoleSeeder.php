<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buat admin user jika belum ada
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'admin',
            ]
        );

        // Buat regular user jika belum ada
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'user',
            ]
        );

          // Buat regular user jika belum ada
        User::firstOrCreate(
            ['email' => 'user1@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'user',
            ]
        );

        // Buat anggota user jika belum ada
        User::firstOrCreate(
            ['email' => 'anggota@example.com'],
            [
                'name' => 'Anggota User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'anggota',
            ]
        );

        // Buat anggota user jika belum ada
        User::firstOrCreate(
            ['email' => 'anggota1@example.com'],
            [
                'name' => 'Anggota User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'anggota',
            ]
        );
    }
}