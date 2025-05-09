<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User; // Pastikan Anda mengimpor model User Anda
use Illuminate\Support\Facades\Hash; // Untuk mengenkripsi password

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Anda bisa menambahkan permission di sini jika diperlukan
        // $adminRole->givePermissionTo('edit articles');
        // $userRole->givePermissionTo('publish articles');

        // Create a default admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Ganti dengan password yang kuat
            'email_verified_at' => now(), // Tandai email sudah diverifikasi
        ]);

        // Assign the admin role to the admin user
        $adminUser->assignRole('admin');

        // Create a default regular user
        $regularUser = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'), // Ganti dengan password yang kuat
            'email_verified_at' => now(), // Tandai email sudah diverifikasi
        ]);

        // Assign the user role to the regular user
        $regularUser->assignRole('user');

        $this->command->info('Roles and default users created successfully!');
    }
}