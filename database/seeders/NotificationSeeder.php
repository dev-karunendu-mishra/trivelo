<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get all users (or at least the first few)
        $users = User::take(5)->get();

        if ($users->isEmpty()) {
            $this->command->info('No users found. Creating notifications for user ID 1.');
            // Create notifications for user ID 1 if no users found
            $this->createSampleNotifications(1);
            return;
        }

        foreach ($users as $user) {
            $this->createSampleNotifications($user->id);
            $this->command->info("Created sample notifications for user: {$user->name} ({$user->email})");
        }
    }

    private function createSampleNotifications($userId)
    {
        $sampleNotifications = [
            [
                'user_id' => $userId,
                'type' => 'booking_confirmation',
                'title' => 'Booking Confirmed!',
                'message' => 'Your reservation at Grand Hotel Plaza has been confirmed for December 25-27, 2025.',
                'data' => json_encode(['booking_id' => 1, 'hotel_name' => 'Grand Hotel Plaza']),
                'priority' => 'high',
                'action_url' => '/customer/bookings',
                'is_actionable' => true,
                'status' => 'unread',
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],
            [
                'user_id' => $userId,
                'type' => 'payment_confirmation',
                'title' => 'Payment Successful',
                'message' => 'Your payment of $299.99 has been processed successfully.',
                'data' => json_encode(['amount' => 299.99, 'payment_id' => 1]),
                'priority' => 'normal',
                'is_actionable' => false,
                'status' => 'unread',
                'created_at' => now()->subHours(1),
                'updated_at' => now()->subHours(1),
            ],
            [
                'user_id' => $userId,
                'type' => 'promotional',
                'title' => '🎉 New Year Special Offer',
                'message' => 'Get 25% off on all bookings for New Year celebrations! Limited time offer.',
                'data' => json_encode(['discount' => '25%', 'offer_code' => 'NY2025']),
                'priority' => 'normal',
                'action_url' => '/',
                'is_actionable' => true,
                'status' => 'unread',
                'expires_at' => now()->addDays(7),
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'user_id' => $userId,
                'type' => 'booking_reminder',
                'title' => 'Upcoming Stay Reminder',
                'message' => 'Your stay at Sunset Resort starts tomorrow. Have a great trip!',
                'data' => json_encode(['booking_id' => 2, 'hotel_name' => 'Sunset Resort']),
                'priority' => 'high',
                'action_url' => '/customer/bookings',
                'is_actionable' => true,
                'status' => 'unread',
                'expires_at' => now()->addDays(2),
                'created_at' => now()->subHours(6),
                'updated_at' => now()->subHours(6),
            ],
            [
                'user_id' => $userId,
                'type' => 'review_reminder',
                'title' => 'Share Your Experience',
                'message' => 'How was your stay at Mountain View Lodge? Your review helps other travelers.',
                'data' => json_encode(['booking_id' => 3, 'hotel_name' => 'Mountain View Lodge']),
                'priority' => 'normal',
                'action_url' => '/customer/reviews',
                'is_actionable' => true,
                'status' => 'read',
                'read_at' => now()->subHours(3),
                'expires_at' => now()->addDays(20),
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subHours(3),
            ],
            [
                'user_id' => $userId,
                'type' => 'system',
                'title' => 'System Maintenance Notice',
                'message' => 'Scheduled maintenance will occur tonight from 2-4 AM. Services may be temporarily unavailable.',
                'priority' => 'low',
                'is_actionable' => false,
                'status' => 'read',
                'read_at' => now()->subHours(12),
                'expires_at' => now()->addHours(6),
                'created_at' => now()->subDay(),
                'updated_at' => now()->subHours(12),
            ],
        ];

        Notification::insert($sampleNotifications);
    }
}
