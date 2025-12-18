<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $guarded = [];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function beds()
    {
        return $this->hasManyThrough(Bed::class, Room::class);
    }
    public function nurses()
    {
        return $this->hasMany(Nurse::class);
    }
}
