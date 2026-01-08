<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HospitalFee extends Model
{
    protected $fillable = [
        'name',
        'price',
        'unit',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
