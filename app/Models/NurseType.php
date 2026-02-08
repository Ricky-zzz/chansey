<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NurseType extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function nurses()
    {
        return $this->hasMany(Nurse::class);
    }
}
