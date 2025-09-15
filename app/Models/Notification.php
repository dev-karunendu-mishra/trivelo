<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'channel',
        'status',
        'read_at',
        'priority',
        'action_url',
        'is_actionable',
        'expires_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_actionable' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Notification types constants
    const TYPE_BOOKING_CONFIRMATION = 'booking_confirmation';
    const TYPE_BOOKING_REMINDER = 'booking_reminder';
    const TYPE_PAYMENT_CONFIRMATION = 'payment_confirmation';
    const TYPE_PROMOTIONAL = 'promotional';
    const TYPE_SYSTEM = 'system';
    const TYPE_REVIEW_REMINDER = 'review_reminder';

    // Priority levels
    const PRIORITY_LOW = 'low';
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    // Status
    const STATUS_UNREAD = 'unread';
    const STATUS_READ = 'read';

    /**
     * Get the user that owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('status', self::STATUS_UNREAD);
    }

    /**
     * Scope to get read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('status', self::STATUS_READ);
    }

    /**
     * Scope to get notifications by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to get non-expired notifications
     */
    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update([
            'status' => self::STATUS_READ,
            'read_at' => now(),
        ]);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread()
    {
        $this->update([
            'status' => self::STATUS_UNREAD,
            'read_at' => null,
        ]);
    }

    /**
     * Check if notification is read
     */
    public function isRead(): bool
    {
        return $this->status === self::STATUS_READ;
    }

    /**
     * Check if notification is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get formatted time ago
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get priority badge class for UI
     */
    public function getPriorityClassAttribute(): string
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'badge-secondary',
            self::PRIORITY_NORMAL => 'badge-primary',
            self::PRIORITY_HIGH => 'badge-warning',
            self::PRIORITY_URGENT => 'badge-danger',
            default => 'badge-primary',
        };
    }

    /**
     * Get icon for notification type
     */
    public function getIconAttribute(): string
    {
        return match($this->type) {
            self::TYPE_BOOKING_CONFIRMATION => 'bi-check-circle',
            self::TYPE_BOOKING_REMINDER => 'bi-alarm',
            self::TYPE_PAYMENT_CONFIRMATION => 'bi-credit-card-check',
            self::TYPE_PROMOTIONAL => 'bi-megaphone',
            self::TYPE_SYSTEM => 'bi-gear',
            self::TYPE_REVIEW_REMINDER => 'bi-star',
            default => 'bi-bell',
        };
    }

    /**
     * Create a booking confirmation notification
     */
    public static function createBookingConfirmation($user, $booking)
    {
        return self::create([
            'user_id' => $user->id,
            'type' => self::TYPE_BOOKING_CONFIRMATION,
            'title' => 'Booking Confirmed!',
            'message' => "Your booking at {$booking->hotel->name} has been confirmed for " . 
                        $booking->check_in->format('M d, Y') . " - " . $booking->check_out->format('M d, Y'),
            'data' => [
                'booking_id' => $booking->id,
                'hotel_id' => $booking->hotel_id,
                'hotel_name' => $booking->hotel->name,
            ],
            'priority' => self::PRIORITY_HIGH,
            'action_url' => route('customer.bookings.show', $booking),
            'is_actionable' => true,
        ]);
    }

    /**
     * Create a payment confirmation notification
     */
    public static function createPaymentConfirmation($user, $payment)
    {
        return self::create([
            'user_id' => $user->id,
            'type' => self::TYPE_PAYMENT_CONFIRMATION,
            'title' => 'Payment Successful',
            'message' => "Payment of $" . number_format($payment->amount, 2) . " has been processed successfully.",
            'data' => [
                'payment_id' => $payment->id,
                'booking_id' => $payment->booking_id,
                'amount' => $payment->amount,
            ],
            'priority' => self::PRIORITY_NORMAL,
            'is_actionable' => false,
        ]);
    }

    /**
     * Create a booking reminder notification
     */
    public static function createBookingReminder($user, $booking)
    {
        $daysUntilCheckIn = now()->diffInDays($booking->check_in, false);
        
        return self::create([
            'user_id' => $user->id,
            'type' => self::TYPE_BOOKING_REMINDER,
            'title' => 'Upcoming Stay Reminder',
            'message' => "Your stay at {$booking->hotel->name} is " . 
                        ($daysUntilCheckIn > 0 ? "in {$daysUntilCheckIn} days" : "today") . "!",
            'data' => [
                'booking_id' => $booking->id,
                'hotel_id' => $booking->hotel_id,
                'check_in' => $booking->check_in->toDateString(),
            ],
            'priority' => self::PRIORITY_NORMAL,
            'action_url' => route('customer.bookings.show', $booking),
            'is_actionable' => true,
            'expires_at' => $booking->check_out->addDay(),
        ]);
    }
}
