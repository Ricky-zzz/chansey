<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $guarded = [];

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function beds()
    {
        return $this->hasMany(Bed::class);
    }
}
