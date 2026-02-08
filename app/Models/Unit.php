<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function stations()
    {
        return $this->hasMany(Station::class);
    }

    public function nurses()
    {
        return $this->hasMany(Nurse::class);
    }
}
