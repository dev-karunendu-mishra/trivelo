<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class BasicUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user already exists
        $adminUser = User::where('email', 'admin@trivelo.com')->first();
        if (!$adminUser) {
            $adminUser = User::create([
                'name' => 'Super Admin',
                'email' => 'admin@trivelo.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'is_active' => true,
            ]);
            
            // Assign Spatie role if it exists
            try {
                $adminUser->assignRole('admin');
                $adminUser->assignRole('super_admin');
            } catch (\Exception $e) {
                // Role doesn't exist, that's fine
            }
            
            $this->command->info('Admin user created: admin@trivelo.com (password: password)');
        } else {
            // Update role field if needed
            if ($adminUser->role !== 'admin') {
                $adminUser->update(['role' => 'admin']);
            }
            // Ensure admin has both admin and super_admin roles
            try {
                if (!$adminUser->hasRole('admin')) {
                    $adminUser->assignRole('admin');
                }
                if (!$adminUser->hasRole('super_admin')) {
                    $adminUser->assignRole('super_admin');
                }
            } catch (\Exception $e) {
                // Role doesn't exist, that's fine
            }
            $this->command->info('Admin user already exists: admin@trivelo.com');
        }

        // Check if hotel manager user already exists
        $managerUser = User::where('email', 'manager@trivelo.com')->first();
        if (!$managerUser) {
            $managerUser = User::create([
                'name' => 'Hotel Manager',
                'email' => 'manager@trivelo.com',
                'password' => Hash::make('password'),
                'role' => 'hotel_manager',
                'email_verified_at' => now(),
                'phone' => '+1234567890',
                'city' => 'New York',
                'country' => 'USA',
                'is_active' => true,
            ]);
            
            // Assign Spatie role if it exists
            try {
                $managerUser->assignRole('hotel_manager');
            } catch (\Exception $e) {
                // Role doesn't exist, that's fine
            }
            
            $this->command->info('Hotel Manager user created: manager@trivelo.com (password: password)');
        } else {
            // Update role field if needed
            if ($managerUser->role !== 'hotel_manager') {
                $managerUser->update(['role' => 'hotel_manager']);
            }
            $this->command->info('Hotel Manager user already exists: manager@trivelo.com');
        }

        // Check if customer user already exists
        $customerUser = User::where('email', 'customer@trivelo.com')->first();
        if (!$customerUser) {
            $customerUser = User::create([
                'name' => 'John Customer',
                'email' => 'customer@trivelo.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'email_verified_at' => now(),
                'phone' => '+0987654321',
                'city' => 'Los Angeles',
                'country' => 'USA',
                'is_active' => true,
            ]);
            
            // Assign Spatie role if it exists
            try {
                $customerUser->assignRole('customer');
            } catch (\Exception $e) {
                // Role doesn't exist, that's fine
            }
            
            $this->command->info('Customer user created: customer@trivelo.com (password: password)');
        } else {
            // Update role field if needed
            if ($customerUser->role !== 'customer') {
                $customerUser->update(['role' => 'customer']);
            }
            $this->command->info('Customer user already exists: customer@trivelo.com');
        }
    }
}