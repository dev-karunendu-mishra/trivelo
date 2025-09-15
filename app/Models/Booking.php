<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'user_id',
        'hotel_id',
        'room_id',
        'check_in_date',
        'check_out_date',
        'nights',
        'adults',
        'children',
        'room_rate',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'currency',
        'status',
        'payment_status',
        'special_requests',
        'guest_details',
        'promo_code',
        'confirmed_at',
        'checked_in_at',
        'checked_out_at',
        'cancelled_at',
        'cancellation_reason',
        'refund_amount',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'room_rate' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'guest_details' => 'array',
        'confirmed_at' => 'datetime',
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Boot method to generate booking number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            $booking->booking_number = 'TRV-' . date('Y') . '-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
            $booking->nights = Carbon::parse($booking->check_in_date)->diffInDays(Carbon::parse($booking->check_out_date));
        });
    }

    // Relationships

    /**
     * Booking belongs to a user (customer)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Customer (alias for user relationship)
     */
    public function customer(): BelongsTo
    {
        return $this->user();
    }

    /**
     * Booking belongs to a hotel
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Booking belongs to a room
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Booking has many payments
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Booking may have a review
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    // Scopes

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['cancelled']);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('check_in_date', '>', now())
                    ->whereIn('status', ['confirmed', 'pending']);
    }

    public function scopeCurrent($query)
    {
        return $query->where('check_in_date', '<=', now())
                    ->where('check_out_date', '>', now())
                    ->whereIn('status', ['confirmed', 'checked_in']);
    }

    public function scopePast($query)
    {
        return $query->where('check_out_date', '<', now())
                    ->whereIn('status', ['checked_out', 'completed']);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPaymentStatus($query, $paymentStatus)
    {
        return $query->where('payment_status', $paymentStatus);
    }

    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('check_in_date', [$startDate, $endDate]);
    }

    // Accessors & Mutators

    public function getFormattedTotalAttribute()
    {
        return $this->currency . ' ' . number_format($this->total_amount, 2);
    }

    public function getFormattedDatesAttribute()
    {
        return $this->check_in_date->format('M d, Y') . ' - ' . $this->check_out_date->format('M d, Y');
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'checked_in' => 'green',
            'checked_out' => 'purple',
            'cancelled' => 'red',
            'no_show' => 'gray',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    public function getPaymentStatusColorAttribute()
    {
        $colors = [
            'pending' => 'yellow',
            'partial' => 'orange',
            'paid' => 'green',
            'refunded' => 'red',
        ];

        return $colors[$this->payment_status] ?? 'gray';
    }

    public function getDaysUntilCheckinAttribute()
    {
        return $this->check_in_date->diffInDays(now(), false);
    }

    public function getDaysUntilCheckoutAttribute()
    {
        return $this->check_out_date->diffInDays(now(), false);
    }

    public function getGuestNameAttribute(): string
    {
        $guestDetails = $this->guest_details ?? [];
        return trim(($guestDetails['first_name'] ?? '') . ' ' . ($guestDetails['last_name'] ?? ''));
    }

    public function getGuestEmailAttribute(): string
    {
        $guestDetails = $this->guest_details ?? [];
        return $guestDetails['email'] ?? '';
    }

    public function getGuestPhoneAttribute(): string
    {
        $guestDetails = $this->guest_details ?? [];
        return $guestDetails['phone'] ?? '';
    }

    public function getNumberOfGuestsAttribute(): int
    {
        return $this->adults + $this->children;
    }

    public function getSubtotalAmountAttribute(): float
    {
        return $this->subtotal;
    }

    // Helper Methods

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isCheckedIn(): bool
    {
        return $this->status === 'checked_in';
    }

    public function isCheckedOut(): bool
    {
        return $this->status === 'checked_out';
    }

    public function confirm()
    {
        $this->update(['status' => 'confirmed', 'confirmed_at' => now()]);
    }

    public function checkIn()
    {
        $this->update(['status' => 'checked_in', 'checked_in_at' => now()]);
    }

    public function checkOut()
    {
        $this->update(['status' => 'checked_out', 'checked_out_at' => now()]);
    }

    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $reason
        ]);
    }

    /**
     * Calculate refund amount based on cancellation policy
     */
    public function calculateRefundAmount(): float
    {
        $daysUntilCheckin = $this->check_in_date->diffInDays(now());
        
        // Sample cancellation policy - adjust as needed
        if ($daysUntilCheckin >= 7) {
            return $this->total_amount; // Full refund
        } elseif ($daysUntilCheckin >= 3) {
            return $this->total_amount * 0.5; // 50% refund
        } else {
            return 0; // No refund
        }
    }

    /**
     * Get total paid amount
     */
    public function getTotalPaid(): float
    {
        return $this->payments()
            ->where('status', 'completed')
            ->where('type', 'payment')
            ->sum('amount');
    }

    /**
     * Get remaining balance
     */
    public function getRemainingBalance(): float
    {
        return max(0, $this->total_amount - $this->getTotalPaid());
    }

    /**
     * Check if booking is fully paid
     */
    public function isFullyPaid(): bool
    {
        return $this->getRemainingBalance() <= 0;
    }

    /**
     * Check if cancellation is allowed
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']) && 
               $this->check_in_date->gt(now());
    }
}
