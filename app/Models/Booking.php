<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $fillable = [
        'warehouse_id',
        'customer_id',
        'user_id',
        'area',
        'items',
        'items_detail',
        'months',
        'total_price',
        'status',
        'payment_status',
        'payment_method',
        'payment_slip',
        'qr_code',
        'qr_expires_at',
        'expires_at',
        'goods_confirmed',
        'storage_confirmed'
    ];

    // -----------------------------
    // Relationships
    // -----------------------------

    // Booking belongs to a Warehouse
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    // Booking belongs to a Customer
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // Payment associated with booking
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Booking may have multiple fraud reports
    public function fraudReports()
    {
        return $this->hasMany(FraudReport::class);
    }

    // 🔹 Owner of the warehouse (via warehouse relation)
    public function owner()
    {
        return $this->hasOneThrough(
            User::class,         // Final model (owner)
            Warehouse::class,    // Intermediate model
            'id',                // Warehouse primary key
            'id',                // User primary key
            'warehouse_id',      // Foreign key on Booking (to warehouse)
            'user_id'            // Foreign key on Warehouse (to owner)
        );
    }

    // -----------------------------
    // QR code generation
    // -----------------------------
    public function generateQr()
    {
        if (!in_array($this->payment_status, ['escrow','paid'])) {
            return false; // Payment not confirmed
        }

        $this->update([
            'qr_code'       => Str::uuid(),
            'qr_expires_at' => $this->end_date ?? now(),
            'expires_at'    => $this->end_date ?? now()
        ]);

        return true;
    }
}
