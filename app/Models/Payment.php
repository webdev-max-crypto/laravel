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
        'payment_method',
        'sms_content',
        'payment_date',
        'txn_ref',
        'released_at'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
