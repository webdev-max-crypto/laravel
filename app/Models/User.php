<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * Columns that can be filled.
     */
    protected $fillable = [
        'name',
    'email',
    'password',
    'role',
    'phone',
    'profile_photo',
    'cnic',
    'property_document',
    'cnic_front',
    'cnic_back',
    'is_verified',
    'agreement_accepted',
    ];

    /**
     * Hidden fields.
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Casts.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    // ==========================
    //   ROLE CHECK FUNCTIONS
    // ==========================
public function isAdmin(): bool { return $this->role === 'admin'; }
public function isOwner(): bool { return $this->role === 'owner'; }
public function isCustomer(): bool { return $this->role === 'customer'; }


    // ==========================
    //      RELATIONSHIPS
    // ==========================

    // An owner has many warehouses
    public function warehouses()
    {
        return $this->hasMany(Warehouse::class, 'owner_id');
    }

    // A customer has many bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    // A customer can leave many reviews
    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    // A user can report fraud cases
    public function fraudReports()
    {
        return $this->hasMany(FraudReport::class, 'reported_by');
    }
}
