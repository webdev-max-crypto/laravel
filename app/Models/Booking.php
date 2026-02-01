<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'warehouse_id','customer_id','start_date','end_date','total_price','status','user_id','payment_slip'
    ];

    public function warehouse() { return $this->belongsTo(Warehouse::class); }
    public function customer() { return $this->belongsTo(User::class,'customer_id'); }
    public function payment() { return $this->hasOne(Payment::class); }
    public function fraudReports() { return $this->hasMany(FraudReport::class); }
}
