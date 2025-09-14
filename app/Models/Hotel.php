<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Hotel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'images',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'star_rating',
        'amenities',
        'policies',
        'status',
        'is_featured',
        'is_active',
        'verified_at',
    ];

    protected $casts = [
        'images' => 'array',
        'amenities' => 'array',
        'policies' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'average_rating' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'verified_at' => 'datetime',
    ];

    protected $dates = [
        'verified_at',
    ];

    // Relationships
    
    /**
     * Hotel belongs to a user (hotel manager)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Hotel manager (alias for user relationship)
     */
    public function manager(): BelongsTo
    {
        return $this->user();
    }

    /**
     * Hotel has many rooms
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Active rooms only
     */
    public function activeRooms(): HasMany
    {
        return $this->rooms()->where('is_active', true);
    }

    /**
     * Available rooms only
     */
    public function availableRooms(): HasMany
    {
        return $this->rooms()->where('is_available', true)->where('is_active', true);
    }

    /**
     * Hotel has many bookings
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Hotel has many reviews
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Approved reviews only
     */
    public function approvedReviews(): HasMany
    {
        return $this->reviews()->where('status', 'approved');
    }

    /**
     * Hotel has many amenities (many-to-many)
     */
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'hotel_amenity')->withTimestamps();
    }

    // Scopes

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInCity($query, $city)
    {
        return $query->where('city', 'like', "%{$city}%");
    }

    public function scopeInCountry($query, $country)
    {
        return $query->where('country', 'like', "%{$country}%");
    }

    public function scopeByStarRating($query, $rating)
    {
        return $query->where('star_rating', $rating);
    }

    public function scopeMinStarRating($query, $minRating)
    {
        return $query->where('star_rating', '>=', $minRating);
    }

    // Accessors & Mutators

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->city}, {$this->state} {$this->postal_code}, {$this->country}";
    }

    public function getFormattedRatingAttribute()
    {
        return number_format($this->average_rating, 1);
    }

    public function getStarRatingTextAttribute()
    {
        $stars = [
            1 => 'One Star',
            2 => 'Two Star',
            3 => 'Three Star',
            4 => 'Four Star',
            5 => 'Five Star'
        ];

        return $stars[$this->star_rating] ?? 'Unrated';
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

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function approve()
    {
        $this->update(['status' => 'approved', 'verified_at' => now()]);
    }

    public function reject()
    {
        $this->update(['status' => 'rejected']);
    }

    public function suspend()
    {
        $this->update(['status' => 'suspended']);
    }

    public function updateAverageRating()
    {
        $averageRating = $this->approvedReviews()->avg('rating');
        $totalReviews = $this->approvedReviews()->count();

        $this->update([
            'average_rating' => $averageRating ?: 0,
            'total_reviews' => $totalReviews
        ]);
    }

    /**
     * Get the main image URL
     */
    public function getMainImageAttribute()
    {
        if ($this->images && is_array($this->images) && count($this->images) > 0) {
            return $this->images[0];
        }

        return '/images/hotel-placeholder.jpg'; // Default placeholder
    }

    /**
     * Check if hotel has specific amenity
     */
    public function hasAmenity($amenityName): bool
    {
        return $this->amenities()->where('name', $amenityName)->exists();
    }

    /**
     * Get available room types
     */
    public function getAvailableRoomTypes()
    {
        return $this->availableRooms()->select('type')->distinct()->pluck('type');
    }

    /**
     * Get price range for rooms
     */
    public function getPriceRange()
    {
        $minPrice = $this->availableRooms()->min('base_price');
        $maxPrice = $this->availableRooms()->max('base_price');

        return [
            'min' => $minPrice,
            'max' => $maxPrice
        ];
    }
}
