<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles if they don't exist
        $roles = ['admin', 'super_admin', 'hotel_manager', 'customer'];
        
        foreach ($roles as $roleName) {
            Role::firstOrCreate(
                ['name' => $roleName],
                ['guard_name' => 'web']
            );
            $this->command->info("Role '{$roleName}' created or already exists.");
        }

        // Create basic permissions
        $permissions = [
            'manage users',
            'manage hotels',
            'manage bookings',
            'manage payments',
            'view analytics',
            'generate reports',
            'manage own hotel',
            'manage own bookings',
            'make bookings',
            'write reviews',
            'view own bookings',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(
                ['name' => $permissionName],
                ['guard_name' => 'web']
            );
            $this->command->info("Permission '{$permissionName}' created or already exists.");
        }

        // Assign permissions to roles
        $adminRole = Role::findByName('admin');
        $superAdminRole = Role::findByName('super_admin');
        $hotelManagerRole = Role::findByName('hotel_manager');
        $customerRole = Role::findByName('customer');

        // Super Admin gets all permissions
        $superAdminRole->syncPermissions(Permission::all());

        // Admin gets most permissions
        $adminRole->syncPermissions([
            'manage users',
            'manage hotels',
            'manage bookings',
            'manage payments',
            'view analytics',
            'generate reports',
        ]);

        // Hotel Manager gets hotel-specific permissions
        $hotelManagerRole->syncPermissions([
            'manage own hotel',
            'manage own bookings',
            'view analytics',
            'generate reports',
        ]);

        // Customer gets basic permissions
        $customerRole->syncPermissions([
            'make bookings',
            'write reviews',
            'view own bookings',
        ]);

        $this->command->info('Roles and permissions created successfully!');
    }
}