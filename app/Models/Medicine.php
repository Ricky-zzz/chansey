<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'generic_name',
        'brand_name',
        'dosage',
        'form',
        'stock_on_hand',
        'critical_level',
        'price',
        'expiry_date',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'price' => 'decimal:2',
    ];
}
