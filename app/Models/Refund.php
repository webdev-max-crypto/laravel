<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = [
        'booking_id', 'requester_id', 'receiver_id',
        'from_role', 'to_role', 'amount', 'reason',
        'status', 'admin_note', 'processed_at',
    ];

    protected $casts = ['processed_at' => 'datetime'];

    public function booking()   { return $this->belongsTo(Booking::class); }
    public function requester() { return $this->belongsTo(User::class, 'requester_id'); }
    public function receiver()  { return $this->belongsTo(User::class, 'receiver_id'); }
}
