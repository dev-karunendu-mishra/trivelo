<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'category',
        'type',
        'is_premium',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_premium' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Boot method to generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($amenity) {
            $amenity->slug = Str::slug($amenity->name);
        });

        static::updating(function ($amenity) {
            $amenity->slug = Str::slug($amenity->name);
        });
    }

    // Relationships

    /**
     * Amenity belongs to many hotels
     */
    public function hotels(): BelongsToMany
    {
        return $this->belongsToMany(Hotel::class, 'hotel_amenity')->withTimestamps();
    }

    /**
     * Amenity belongs to many rooms
     */
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'room_amenity')->withTimestamps();
    }

    // Scopes

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePremium($query)
    {
        return $query->where('is_premium', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForHotels($query)
    {
        return $query->whereIn('type', ['hotel', 'both']);
    }

    public function scopeForRooms($query)
    {
        return $query->whereIn('type', ['room', 'both']);
    }

    // Accessors & Mutators

    public function getFormattedCategoryAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->category));
    }

    public function getFormattedTypeAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->type));
    }

    // Helper Methods

    public function isForHotels(): bool
    {
        return in_array($this->type, ['hotel', 'both']);
    }

    public function isForRooms(): bool
    {
        return in_array($this->type, ['room', 'both']);
    }

    /**
     * Get usage count across hotels and rooms
     */
    public function getUsageCount()
    {
        $hotelCount = $this->hotels()->count();
        $roomCount = $this->rooms()->count();

        return $hotelCount + $roomCount;
    }
}
