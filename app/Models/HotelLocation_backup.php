<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        "hotel_id",
        "address",
        "city",
        "state",
        "country",
        "postal_code",
        "latitude",
        "longitude",
    ];

    protected $casts = [
        "latitude" => "decimal:8",
        "longitude" => "decimal:8",
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country
        ]);
        
        return implode(", ", $parts);
    }
}

