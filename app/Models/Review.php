<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hotel_id',
        'booking_id',
        'rating',
        'cleanliness_rating',
        'service_rating',
        'location_rating',
        'value_rating',
        'title',
        'review',
        'images',
        'pros',
        'cons',
        'is_verified',
        'is_anonymous',
        'status',
        'admin_notes',
        'helpful_count',
        'stayed_at',
    ];

    protected $casts = [
        'images' => 'array',
        'pros' => 'array',
        'cons' => 'array',
        'is_verified' => 'boolean',
        'is_anonymous' => 'boolean',
        'stayed_at' => 'datetime',
    ];

    // Relationships

    /**
     * Review belongs to a user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Review belongs to a hotel
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Review may belong to a booking
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    // Scopes

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    public function scopeMinRating($query, $minRating)
    {
        return $query->where('rating', '>=', $minRating);
    }

    // Accessors & Mutators

    public function getAuthorNameAttribute()
    {
        if ($this->is_anonymous) {
            return 'Anonymous';
        }

        return $this->user->name ?? 'Unknown User';
    }

    public function getStarRatingAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    public function getAverageSubRatingAttribute()
    {
        $ratings = array_filter([
            $this->cleanliness_rating,
            $this->service_rating,
            $this->location_rating,
            $this->value_rating
        ]);

        return count($ratings) > 0 ? array_sum($ratings) / count($ratings) : null;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            'flagged' => 'orange',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    // Helper Methods

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function approve()
    {
        $this->update(['status' => 'approved']);
        
        // Update hotel's average rating
        $this->hotel->updateAverageRating();
    }

    public function reject($adminNotes = null)
    {
        $this->update(['status' => 'rejected', 'admin_notes' => $adminNotes]);
    }

    public function flag($adminNotes = null)
    {
        $this->update(['status' => 'flagged', 'admin_notes' => $adminNotes]);
    }

    public function incrementHelpfulCount()
    {
        $this->increment('helpful_count');
    }
}
