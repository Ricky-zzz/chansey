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

    public function getFormattedDateAttribute(): string
    {
        return $this->date instanceof \DateTime ? $this->date->format('M d, Y') : \Carbon\Carbon::parse($this->date)->format('M d, Y');
    }

    public function getFormattedDayAttribute(): string
    {
        return $this->date instanceof \DateTime ? $this->date->format('l') : \Carbon\Carbon::parse($this->date)->format('l');
    }

    public function getFormattedStartTimeAttribute(): string
    {
        return $this->start_time instanceof \DateTime ? $this->start_time->format('h:i A') : \Carbon\Carbon::parse($this->start_time)->format('h:i A');
    }

    public function getFormattedEndTimeAttribute(): string
    {
        return $this->end_time instanceof \DateTime ? $this->end_time->format('h:i A') : \Carbon\Carbon::parse($this->end_time)->format('h:i A');
    }
}
