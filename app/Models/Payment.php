<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'transaction_id',
        'payment_intent_id',
        'amount',
        'currency',
        'type',
        'method',
        'status',
        'gateway',
        'gateway_response',
        'receipt_number',
        'description',
        'failure_reason',
        'fee_amount',
        'net_amount',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'gateway_response' => 'array',
        'processed_at' => 'datetime',
    ];

    // Relationships

    /**
     * Payment belongs to a booking
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Payment belongs to a user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByGateway($query, $gateway)
    {
        return $query->where('gateway', $gateway);
    }

    // Accessors & Mutators

    public function getFormattedAmountAttribute()
    {
        return $this->currency . ' ' . number_format($this->amount, 2);
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'yellow',
            'processing' => 'blue',
            'completed' => 'green',
            'failed' => 'red',
            'cancelled' => 'gray',
            'refunded' => 'purple',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    public function getTypeColorAttribute()
    {
        $colors = [
            'payment' => 'green',
            'refund' => 'red',
            'partial_refund' => 'orange',
        ];

        return $colors[$this->type] ?? 'gray';
    }

    // Helper Methods

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isRefund(): bool
    {
        return in_array($this->type, ['refund', 'partial_refund']);
    }

    public function markAsCompleted()
    {
        $this->update(['status' => 'completed', 'processed_at' => now()]);
    }

    public function markAsFailed($reason = null)
    {
        $this->update(['status' => 'failed', 'failure_reason' => $reason]);
    }
}
