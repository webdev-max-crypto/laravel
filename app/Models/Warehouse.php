<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'location',
        'size',
        'contact',
        'description',
        'image',
        'property_doc',
        'address',
        'total_space',
        'available_space',
        'price_per_month',
         'jazzcash_number',
        'stripe_account_id',
        'preferred_payment_method',
        'status',
        'last_active',
    ];

    // ✅ ADD THIS RELATIONSHIP
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function markActive()
    {
        $this->update([
        'last_active' => now(),
        'is_flagged' => 0,
        'inactive_reason' => null,
    ]);
}

}
