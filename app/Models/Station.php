<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = [
        'station_name',
        'station_code',
        'floor_location',
    ];

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

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }

    public function transferRequests()
    {
        return $this->hasMany(TransferRequest::class, 'target_station_id');
    }
}
