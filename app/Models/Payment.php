<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
    'customer_id',
    'owner_id',
    'amount',
    'status',
    'txn_ref',
    'released_at'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
