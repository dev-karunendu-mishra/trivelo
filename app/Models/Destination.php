<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'country',
        'state',
        'city',
        'image_url',
        'is_popular',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get hotels in this destination
     */
    public function hotels(): HasMany
    {
        return $this->hasMany(Hotel::class, 'destination_id');
    }

    /**
     * Get active hotels in this destination
     */
    public function activeHotels(): HasMany
    {
        return $this->hotels()->where('is_active', true);
    }

    /**
     * Scope for popular destinations
     */
    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    /**
     * Scope for active destinations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the destination's full location
     */
    public function getFullLocationAttribute(): string
    {
        $parts = array_filter([$this->city, $this->state, $this->country]);
        return implode(', ', $parts);
    }
}
