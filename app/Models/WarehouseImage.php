<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseImage extends Model
{
    protected $fillable = [
        'warehouse_id',
        'image_path'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
