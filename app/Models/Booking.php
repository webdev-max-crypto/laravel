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
    'qr_code',
    'goods_confirmed',
    ];

    public function warehouse() { return $this->belongsTo(Warehouse::class); }
    public function customer() { return $this->belongsTo(User::class,'customer_id'); }
    public function payment() { return $this->hasOne(Payment::class); }
    public function fraudReports() { return $this->hasMany(FraudReport::class); }
    public function generateQr($id)
{
    $booking = Booking::findOrFail($id);

    if (!in_array($booking->payment_status, ['escrow','paid'])) {
        return back()->with('error', 'Payment not verified');
    }

    $booking->update([
        'qr_code'       => Str::uuid(),
        'qr_expires_at' => $booking->end_date,
        'expires_at'    => $booking->end_date
    ]);

    return back()->with('success', 'QR generated successfully');
}
}
