<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model {
    protected $fillable = ['name', 'description'];
    
    public function physicians() {
        return $this->hasMany(Physician::class);
    }

    public function appointments() {
        return $this->hasMany(Appointment::class);
    }
}
