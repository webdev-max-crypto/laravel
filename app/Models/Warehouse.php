<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id', 'name', 'location', 'size', 'contact',
        'description', 'image', 'property_doc', 'address',
        'total_space', 'available_space', 'price_per_month',
        'status', 'last_active',
    ];
}
