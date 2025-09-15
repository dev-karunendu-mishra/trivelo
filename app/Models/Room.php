<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'hotel_id',
        'name',
        'room_number',
        'description',
        'images',
        'type',
        'capacity',
        'beds',
        'bed_type',
        'base_price',
        'weekend_price',
        'holiday_price',
        'size_sqft',
        'amenities',
        'features',
        'is_smoking',
        'is_accessible',
        'is_available',
        'is_active',
        'floor_number',
        'status',
    ];

    protected $casts = [
        'images' => 'array',
        'amenities' => 'array',
        'features' => 'array',
        'base_price' => 'decimal:2',
        'weekend_price' => 'decimal:2',
        'holiday_price' => 'decimal:2',
        'is_smoking' => 'boolean',
        'is_accessible' => 'boolean',
        'is_available' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships

    /**
     * Room belongs to a hotel
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Room has many bookings
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Room has many amenities (many-to-many)
     */
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'room_amenity')->withTimestamps();
    }

    // Scopes

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCapacity($query, $capacity)
    {
        return $query->where('capacity', '>=', $capacity);
    }

    public function scopeAccessible($query)
    {
        return $query->where('is_accessible', true);
    }

    public function scopeNonSmoking($query)
    {
        return $query->where('is_smoking', false);
    }

    public function scopePriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('base_price', [$minPrice, $maxPrice]);
    }

    public function scopeOnFloor($query, $floor)
    {
        return $query->where('floor_number', $floor);
    }

    public function scopeOccupied($query)
    {
        $today = now()->toDateString();
        return $query->whereHas('bookings', function($q) use ($today) {
            $q->where('check_in_date', '<=', $today)
              ->where('check_out_date', '>', $today)
              ->where('status', '!=', 'cancelled');
        });
    }

    // Accessors & Mutators

    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->base_price, 2);
    }

    public function getFormattedTypeAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->type));
    }

    public function getFormattedBedTypeAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->bed_type));
    }

    public function getMainImageAttribute()
    {
        if ($this->images && is_array($this->images) && count($this->images) > 0) {
            return $this->images[0];
        }

        return '/images/room-placeholder.jpg'; // Default placeholder
    }

    public function getFormattedSizeAttribute()
    {
        return $this->size_sqft ? $this->size_sqft . ' sq ft' : null;
    }

    // Helper Methods

    /**
     * Get current price based on date
     */
    public function getCurrentPrice($date = null)
    {
        if (!$date) {
            $date = now();
        }

        // Check if it's weekend
        $dayOfWeek = $date->dayOfWeek;
        if (($dayOfWeek == 0 || $dayOfWeek == 6) && $this->weekend_price) {
            return $this->weekend_price;
        }

        // You can add holiday logic here
        // For now, return base price
        return $this->base_price;
    }

    /**
     * Check if room is available for date range
     */
    public function isAvailableForDates($checkIn, $checkOut)
    {
        if (!$this->is_available || !$this->is_active) {
            return false;
        }

        // Check for overlapping bookings
        $overlappingBookings = $this->bookings()
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in_date', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in_date', '<=', $checkIn)
                          ->where('check_out_date', '>=', $checkOut);
                    });
            })
            ->exists();

        return !$overlappingBookings;
    }

    /**
     * Get booking count for specific period
     */
    public function getBookingCount($startDate = null, $endDate = null)
    {
        $query = $this->bookings()->where('status', '!=', 'cancelled');

        if ($startDate && $endDate) {
            $query->whereBetween('check_in_date', [$startDate, $endDate]);
        }

        return $query->count();
    }

    /**
     * Calculate total revenue for specific period
     */
    public function getTotalRevenue($startDate = null, $endDate = null)
    {
        $query = $this->bookings()
            ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
            ->where('payment_status', 'paid');

        if ($startDate && $endDate) {
            $query->whereBetween('check_in_date', [$startDate, $endDate]);
        }

        return $query->sum('total_amount');
    }

    /**
     * Get occupancy rate for specific period
     */
    public function getOccupancyRate($startDate, $endDate)
    {
        $totalDays = $startDate->diffInDays($endDate);
        $bookedDays = $this->bookings()
            ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
            ->where('check_in_date', '>=', $startDate)
            ->where('check_out_date', '<=', $endDate)
            ->sum('nights');

        return $totalDays > 0 ? ($bookedDays / $totalDays) * 100 : 0;
    }
}
