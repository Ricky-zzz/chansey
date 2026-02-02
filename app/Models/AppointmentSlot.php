<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentSlot extends Model
{
    protected $guarded = [];

    public function physician() {
        return $this->belongsTo(Physician::class);
    }

    public function appointments() {
        return $this->hasMany(Appointment::class);
    }

    // Helper: Check if full
    public function isFull() {
        return $this->appointments()->count() >= $this->capacity;
    }
    
    // Helper: Count remaining
    public function remaining_slots() {
        return $this->capacity - $this->appointments()->count();
    }
}