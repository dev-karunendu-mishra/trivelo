<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TestRolesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the roles and permissions system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Roles and Permissions System...');
        $this->newLine();

        // Test 1: Check if roles exist
        $this->info('1. Testing roles existence:');
        $roles = ['super_admin', 'hotel_manager', 'customer'];
        
        foreach ($roles as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $this->line("   ✓ Role '$roleName' exists with " . $role->permissions->count() . " permissions");
            } else {
                $this->error("   ✗ Role '$roleName' does not exist!");
                return;
            }
        }

        // Test 2: Check if sample users exist and have correct roles
        $this->newLine();
        $this->info('2. Testing user roles:');
        
        $testUsers = [
            ['email' => 'admin@trivelo.com', 'expected_role' => 'super_admin'],
            ['email' => 'manager@trivelo.com', 'expected_role' => 'hotel_manager'],
            ['email' => 'customer@trivelo.com', 'expected_role' => 'customer'],
        ];

        foreach ($testUsers as $testUser) {
            $user = User::where('email', $testUser['email'])->first();
            if ($user && $user->hasRole($testUser['expected_role'])) {
                $this->line("   ✓ User '{$testUser['email']}' has role '{$testUser['expected_role']}'");
            } else {
                $this->error("   ✗ User '{$testUser['email']}' does not have the correct role!");
            }
        }

        // Test 3: Test permissions
        $this->newLine();
        $this->info('3. Testing permissions:');
        
        $admin = User::where('email', 'admin@trivelo.com')->first();
        if ($admin && $admin->can('manage settings')) {
            $this->line("   ✓ Super admin can 'manage settings'");
        } else {
            $this->error("   ✗ Super admin cannot 'manage settings'!");
        }

        $manager = User::where('email', 'manager@trivelo.com')->first();
        if ($manager && $manager->can('manage own hotel')) {
            $this->line("   ✓ Hotel manager can 'manage own hotel'");
        } else {
            $this->error("   ✗ Hotel manager cannot 'manage own hotel'!");
        }

        $customer = User::where('email', 'customer@trivelo.com')->first();
        if ($customer && $customer->can('create bookings')) {
            $this->line("   ✓ Customer can 'create bookings'");
        } else {
            $this->error("   ✗ Customer cannot 'create bookings'!");
        }

        // Test 4: Test role helper methods
        $this->newLine();
        $this->info('4. Testing role helper methods:');
        
        if ($admin && $admin->isSuperAdmin()) {
            $this->line("   ✓ isSuperAdmin() method works correctly");
        } else {
            $this->error("   ✗ isSuperAdmin() method failed!");
        }

        if ($manager && $manager->isHotelManager()) {
            $this->line("   ✓ isHotelManager() method works correctly");
        } else {
            $this->error("   ✗ isHotelManager() method failed!");
        }

        if ($customer && $customer->isCustomer()) {
            $this->line("   ✓ isCustomer() method works correctly");
        } else {
            $this->error("   ✗ isCustomer() method failed!");
        }

        $this->newLine();
        $this->info('✅ Roles and Permissions system testing completed!');
        
        // Display summary
        $this->newLine();
        $this->info('📊 System Summary:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Users', User::count()],
                ['Total Roles', Role::count()],
                ['Total Permissions', Permission::count()],
                ['Super Admins', User::role('super_admin')->count()],
                ['Hotel Managers', User::role('hotel_manager')->count()],
                ['Customers', User::role('customer')->count()],
            ]
        );
    }
}
