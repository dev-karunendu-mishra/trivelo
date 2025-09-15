<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;

class NotificationService
{
    /**
     * Send a booking confirmation notification
     */
    public function sendBookingConfirmation(Booking $booking)
    {
        return Notification::createBookingConfirmation($booking->user, $booking);
    }

    /**
     * Send a payment confirmation notification
     */
    public function sendPaymentConfirmation(Payment $payment)
    {
        return Notification::createPaymentConfirmation($payment->user, $payment);
    }

    /**
     * Send booking reminder notifications
     */
    public function sendBookingReminders()
    {
        // Get bookings that are 1 day away from check-in
        $upcomingBookings = Booking::where('check_in', today()->addDay())
            ->where('status', 'confirmed')
            ->with(['user', 'hotel'])
            ->get();

        $sent = 0;
        foreach ($upcomingBookings as $booking) {
            // Check if reminder already sent
            $existingReminder = Notification::where('user_id', $booking->user_id)
                ->where('type', Notification::TYPE_BOOKING_REMINDER)
                ->whereJsonContains('data->booking_id', $booking->id)
                ->first();

            if (!$existingReminder) {
                Notification::createBookingReminder($booking->user, $booking);
                $sent++;
            }
        }

        return $sent;
    }

    /**
     * Send promotional notification to users
     */
    public function sendPromotionalNotification($userIds, $title, $message, $actionUrl = null, $priority = 'normal')
    {
        $sent = 0;
        foreach ($userIds as $userId) {
            Notification::create([
                'user_id' => $userId,
                'type' => Notification::TYPE_PROMOTIONAL,
                'title' => $title,
                'message' => $message,
                'priority' => $priority,
                'action_url' => $actionUrl,
                'is_actionable' => !empty($actionUrl),
                'expires_at' => now()->addDays(30), // Promotional notifications expire after 30 days
            ]);
            $sent++;
        }

        return $sent;
    }

    /**
     * Send system notification
     */
    public function sendSystemNotification($userIds, $title, $message, $priority = 'normal')
    {
        $sent = 0;
        foreach ($userIds as $userId) {
            Notification::create([
                'user_id' => $userId,
                'type' => Notification::TYPE_SYSTEM,
                'title' => $title,
                'message' => $message,
                'priority' => $priority,
                'is_actionable' => false,
            ]);
            $sent++;
        }

        return $sent;
    }

    /**
     * Send review reminder notification
     */
    public function sendReviewReminder(Booking $booking)
    {
        // Only send review reminders for completed bookings without reviews
        if ($booking->status !== 'checked_out' || $booking->review) {
            return false;
        }

        // Check if reminder already sent
        $existingReminder = Notification::where('user_id', $booking->user_id)
            ->where('type', Notification::TYPE_REVIEW_REMINDER)
            ->whereJsonContains('data->booking_id', $booking->id)
            ->first();

        if ($existingReminder) {
            return false;
        }

        return Notification::create([
            'user_id' => $booking->user_id,
            'type' => Notification::TYPE_REVIEW_REMINDER,
            'title' => 'Share Your Experience',
            'message' => "How was your stay at {$booking->hotel->name}? Leave a review to help other travelers.",
            'data' => [
                'booking_id' => $booking->id,
                'hotel_id' => $booking->hotel_id,
                'hotel_name' => $booking->hotel->name,
            ],
            'priority' => Notification::PRIORITY_NORMAL,
            'action_url' => route('customer.reviews.create', $booking),
            'is_actionable' => true,
            'expires_at' => now()->addDays(30), // Review reminders expire after 30 days
        ]);
    }

    /**
     * Clean up expired notifications
     */
    public function cleanupExpiredNotifications()
    {
        return Notification::where('expires_at', '<', now())->delete();
    }

    /**
     * Get notification statistics for a user
     */
    public function getUserNotificationStats(User $user)
    {
        return [
            'total' => $user->notifications()->count(),
            'unread' => $user->notifications()->unread()->count(),
            'read' => $user->notifications()->read()->count(),
            'today' => $user->notifications()->whereDate('created_at', today())->count(),
            'this_week' => $user->notifications()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'by_type' => $user->notifications()
                ->selectRaw('type, count(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray(),
        ];
    }

    /**
     * Create sample notifications for testing
     */
    public function createSampleNotifications(User $user)
    {
        $sampleNotifications = [
            [
                'type' => Notification::TYPE_BOOKING_CONFIRMATION,
                'title' => 'Booking Confirmed!',
                'message' => 'Your reservation at Grand Hotel Plaza has been confirmed for December 25-27, 2025.',
                'priority' => Notification::PRIORITY_HIGH,
                'data' => ['booking_id' => 1, 'hotel_name' => 'Grand Hotel Plaza'],
                'action_url' => route('customer.bookings'),
                'is_actionable' => true,
            ],
            [
                'type' => Notification::TYPE_PAYMENT_CONFIRMATION,
                'title' => 'Payment Successful',
                'message' => 'Your payment of $299.99 has been processed successfully.',
                'priority' => Notification::PRIORITY_NORMAL,
                'data' => ['amount' => 299.99, 'payment_id' => 1],
                'is_actionable' => false,
            ],
            [
                'type' => Notification::TYPE_PROMOTIONAL,
                'title' => '🎉 New Year Special Offer',
                'message' => 'Get 25% off on all bookings for New Year celebrations! Limited time offer.',
                'priority' => Notification::PRIORITY_NORMAL,
                'action_url' => route('home'),
                'is_actionable' => true,
                'expires_at' => now()->addDays(7),
            ],
            [
                'type' => Notification::TYPE_BOOKING_REMINDER,
                'title' => 'Upcoming Stay Reminder',
                'message' => 'Your stay at Sunset Resort starts tomorrow. Have a great trip!',
                'priority' => Notification::PRIORITY_HIGH,
                'data' => ['booking_id' => 2, 'hotel_name' => 'Sunset Resort'],
                'action_url' => route('customer.bookings'),
                'is_actionable' => true,
                'expires_at' => now()->addDays(2),
            ],
            [
                'type' => Notification::TYPE_REVIEW_REMINDER,
                'title' => 'Share Your Experience',
                'message' => 'How was your stay at Mountain View Lodge? Your review helps other travelers.',
                'priority' => Notification::PRIORITY_NORMAL,
                'data' => ['booking_id' => 3, 'hotel_name' => 'Mountain View Lodge'],
                'action_url' => route('customer.reviews'),
                'is_actionable' => true,
                'expires_at' => now()->addDays(20),
            ],
            [
                'type' => Notification::TYPE_SYSTEM,
                'title' => 'System Maintenance Notice',
                'message' => 'Scheduled maintenance will occur tonight from 2-4 AM. Services may be temporarily unavailable.',
                'priority' => Notification::PRIORITY_LOW,
                'is_actionable' => false,
                'expires_at' => now()->addDays(1),
            ],
        ];

        foreach ($sampleNotifications as $data) {
            $notification = $user->notifications()->create(array_merge($data, [
                'status' => rand(0, 1) ? 'unread' : 'read', // Random read/unread status
                'read_at' => isset($data['status']) && $data['status'] === 'read' ? now()->subHours(rand(1, 24)) : null,
                'created_at' => now()->subHours(rand(1, 168)), // Random time within last week
            ]));
        }

        return count($sampleNotifications);
    }
}