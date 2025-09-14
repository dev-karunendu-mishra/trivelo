<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'city',
        'country',
        'date_of_birth',
        'profile_image',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if user is a super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    /**
     * Check if user is a hotel manager
     */
    public function isHotelManager(): bool
    {
        return $this->hasRole('hotel_manager');
    }

    /**
     * Check if user is a customer
     */
    public function isCustomer(): bool
    {
        return $this->hasRole('customer');
    }

    // Model Relationships

    /**
     * User can manage hotels (for hotel managers)
     */
    public function hotels(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Hotel::class);
    }

    /**
     * User has many bookings (for customers)
     */
    public function bookings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * User has many payments
     */
    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * User has many reviews
     */
    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get user's active bookings
     */
    public function activeBookings()
    {
        return $this->bookings()->whereNotIn('status', ['cancelled', 'checked_out']);
    }

    /**
     * Get user's completed bookings
     */
    public function completedBookings()
    {
        return $this->bookings()->where('status', 'checked_out');
    }

    /**
     * Get user's total spending
     */
    public function getTotalSpent()
    {
        return $this->payments()
            ->where('status', 'completed')
            ->where('type', 'payment')
            ->sum('amount');
    }
}
