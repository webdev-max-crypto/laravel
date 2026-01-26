<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FraudReport extends Model
{
    protected $fillable = [
        'booking_id',
        'reported_by',
        'message',
        'status'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
