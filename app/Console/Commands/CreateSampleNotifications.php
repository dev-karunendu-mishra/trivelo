<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class CreateSampleNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:create-samples {--user-id= : Specific user ID to create notifications for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create sample notifications for testing purposes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $notificationService = new NotificationService();
        
        $userId = $this->option('user-id');
        
        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found.");
                return 1;
            }
            $users = [$user];
        } else {
            // Get all customer users
            $users = User::role('customer')->get();
            
            if ($users->isEmpty()) {
                $this->error('No customer users found. Please create some users first.');
                return 1;
            }
        }

        $this->info('Creating sample notifications...');
        $totalCreated = 0;

        foreach ($users as $user) {
            $created = $notificationService->createSampleNotifications($user);
            $totalCreated += $created;
            $this->info("Created {$created} notifications for user: {$user->name} ({$user->email})");
        }

        $this->info("\n✅ Successfully created {$totalCreated} sample notifications for " . count($users) . " user(s).");
        
        return 0;
    }
}
