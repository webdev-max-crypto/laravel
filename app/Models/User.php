<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use App\Models\CustomDatabaseNotification;
use App\Models\FraudReport;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone',
        'profile_photo', 'cnic', 'property_document',
        'cnic_front', 'cnic_back', 'is_verified',
        'agreement_accepted', 'stripe_account_id', 'stripe_account_status','cnic',
    'country',
    'city'
    ];

    protected $hidden = [
        'password', 'two_factor_secret', 
        'two_factor_recovery_codes', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    // Role helpers
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isOwner(): bool { return $this->role === 'owner'; }
    public function isCustomer(): bool { return $this->role === 'customer'; }

    // Relationships
    public function warehouses() { return $this->hasMany(Warehouse::class, 'owner_id'); }
    public function bookings() { return $this->hasMany(Booking::class, 'customer_id'); }
    public function reviews() { return $this->hasMany(Review::class, 'user_id'); }
public function reports()
{
    return $this->hasMany(FraudReport::class);
}

    // --------------------------
    // Notifications fix for 'is_read'
    // --------------------------

    /**
     * Laravel notifications ke liye correct morphMany relation
     */
    public function notifications()
    {
        return $this->morphMany(CustomDatabaseNotification::class, 'notifiable')
                    ->orderBy('created_at', 'desc');
    }
    
}