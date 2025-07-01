<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles first
        $this->call([
            RoleSeeder::class,
        ]);

        // Create default super admin user
        User::updateOrCreate(
            ['email' => 'admin@sbt.com'],
            [
                'name' => 'Super Admin',
                'full_name' => 'Super Administrator',
                'email' => 'admin@sbt.com',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'status' => 'active',
                'username' => 'superadmin',
            ]
        );

        // Create test users for each role
        User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin User',
                'full_name' => 'Administrator User',
                'email' => 'admin@test.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'status' => 'active',
                'username' => 'adminuser',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@test.com'],
            [
                'name' => 'Regular User',
                'full_name' => 'Regular User',
                'email' => 'user@test.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'status' => 'active',
                'username' => 'regularuser',
            ]
        );
    }
}
