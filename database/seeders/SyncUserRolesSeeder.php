<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class SyncUserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();
        
        foreach ($users as $user) {
            $this->command->info("Syncing roles for user: {$user->name} (ID: {$user->id})");
            $this->command->info("  Current role field: {$user->role}");
            $this->command->info("  Current Spatie roles: " . $user->roles->pluck('name')->implode(', '));
            
            // Remove all existing roles first
            $user->syncRoles([]);
            
            // Assign Spatie role based on the role field
            $roleName = $this->mapRoleName($user->role);
            
            if (Role::where('name', $roleName)->exists()) {
                $user->assignRole($roleName);
                $this->command->info("  ✓ Assigned Spatie role: {$roleName}");
            } else {
                $this->command->error("  ✗ Role '{$roleName}' does not exist!");
            }
            
            $this->command->info("  New Spatie roles: " . $user->fresh()->roles->pluck('name')->implode(', '));
            $this->command->info("---");
        }
        
        $this->command->info('User roles synchronized successfully!');
    }
    
    /**
     * Map role field values to Spatie role names
     */
    private function mapRoleName(string $roleField): string
    {
        return match($roleField) {
            'admin', 'super_admin' => 'admin',
            'hotel_manager' => 'hotel_manager',
            'customer' => 'customer',
            default => 'customer' // Default fallback
        };
    }
}