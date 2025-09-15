<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if not exists
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@trivelo.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'is_active' => true,
                'last_login' => now(),
            ]
        );

        $this->command->info('Admin user created/updated: ' . $admin->email);
    }
}
