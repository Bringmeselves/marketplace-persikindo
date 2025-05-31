<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Optional: Nonaktifkan foreign key constraints sementara (untuk reset bersih)
        Schema::disableForeignKeyConstraints();

        try {
            // Memanggil RoleSeeder untuk membuat role dan user default
            $this->call([
                RoleSeeder::class,  // Memanggil RoleSeeder untuk membuat roles dan pengguna default
                // Seeder lain bisa ditambahkan di sini jika ada
                // misalnya: ProductSeeder::class, AnggotaSeeder::class, dll.
            ]);
        } catch (\Throwable $e) {
            $this->command->error("Seeding error: " . $e->getMessage());
        }

        // Aktifkan kembali foreign key constraints
        Schema::enableForeignKeyConstraints();
    }
}