<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super_admin',
                'description' => 'Full system access and control',
                'permissions' => [
                    'user_management',
                    'profile_management',
                    'inventory_management',
                    'supplier_management',
                    'sales_management',
                    'reporting',
                    'audit_trails',
                    'system_settings'
                ]
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Manager level access',
                'permissions' => [
                    'user_management',
                    'inventory_management',
                    'supplier_management',
                    'sales_management',
                    'reporting'
                ]
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'description' => 'Basic user access',
                'permissions' => [
                    'view_inventory',
                    'view_sales',
                    'basic_reporting'
                ]
            ]
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}
