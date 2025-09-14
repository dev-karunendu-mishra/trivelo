<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage user roles',

            // Hotel Management
            'view hotels',
            'create hotels',
            'edit hotels',
            'delete hotels',
            'approve hotels',
            'manage own hotel',

            // Room Management
            'view rooms',
            'create rooms',
            'edit rooms',
            'delete rooms',
            'manage own rooms',

            // Booking Management
            'view bookings',
            'create bookings',
            'edit bookings',
            'cancel bookings',
            'view own bookings',
            'manage hotel bookings',

            // Payment Management
            'view payments',
            'process payments',
            'refund payments',
            'view own payments',

            // Reviews Management
            'view reviews',
            'create reviews',
            'edit own reviews',
            'delete reviews',
            'moderate reviews',

            // System Management
            'view dashboard',
            'view analytics',
            'manage settings',
            'manage notifications',
            'view reports',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Super Admin Role - Full access
        $superAdmin = Role::create(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Hotel Manager Role - Manage their own hotel and bookings
        $hotelManager = Role::create(['name' => 'hotel_manager']);
        $hotelManager->givePermissionTo([
            'view hotels',
            'manage own hotel',
            'view rooms',
            'create rooms',
            'edit rooms',
            'delete rooms',
            'manage own rooms',
            'view bookings',
            'manage hotel bookings',
            'view payments',
            'view own payments',
            'view reviews',
            'moderate reviews',
            'view dashboard',
            'view analytics',
            'view reports',
        ]);

        // Customer Role - Book hotels and manage their own bookings
        $customer = Role::create(['name' => 'customer']);
        $customer->givePermissionTo([
            'view hotels',
            'view rooms',
            'create bookings',
            'view own bookings',
            'cancel bookings',
            'process payments',
            'view own payments',
            'create reviews',
            'edit own reviews',
            'view reviews',
        ]);

        // Create default super admin user
        $superAdminUser = \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'admin@trivelo.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $superAdminUser->assignRole('super_admin');

        // Create sample hotel manager
        $hotelManagerUser = \App\Models\User::create([
            'name' => 'Hotel Manager',
            'email' => 'manager@trivelo.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'phone' => '+1234567890',
            'city' => 'New York',
            'country' => 'USA',
            'is_active' => true,
        ]);
        $hotelManagerUser->assignRole('hotel_manager');

        // Create sample customer
        $customerUser = \App\Models\User::create([
            'name' => 'John Customer',
            'email' => 'customer@trivelo.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'phone' => '+0987654321',
            'city' => 'Los Angeles',
            'country' => 'USA',
            'is_active' => true,
        ]);
        $customerUser->assignRole('customer');

        $this->command->info('Roles and permissions created successfully!');
        $this->command->info('Default users created:');
        $this->command->info('Super Admin: admin@trivelo.com (password: password)');
        $this->command->info('Hotel Manager: manager@trivelo.com (password: password)');
        $this->command->info('Customer: customer@trivelo.com (password: password)');
    }
}
