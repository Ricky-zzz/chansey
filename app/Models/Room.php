<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'station_id',
        'room_number',
        'room_type',
        'capacity',
        'price_per_night',
        'status',
    ];

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function beds()
    {
        return $this->hasMany(Bed::class);
    }
}
