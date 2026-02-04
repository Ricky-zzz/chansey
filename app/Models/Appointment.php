<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointment extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'contact_number',
        'purpose',
        'appointment_slot_id',
        'status'
    ];

    public function appointmentSlot()
    {
        return $this->belongsTo(AppointmentSlot::class);
    }

    public function physician()
    {
        return $this->hasOneThrough(
            Physician::class,
            AppointmentSlot::class,
            'id',              // Foreign key on appointment_slots
            'id',              // Foreign key on physicians
            'appointment_slot_id', // Local key on appointments
            'physician_id'     // Local key on appointment_slots
        );
    }

    // Accessor to get physician through slot (simpler approach)
    public function getPhysicianAttribute()
    {
        return $this->appointmentSlot?->physician;
    }

    // Accessor to get department through slot->physician
    public function getDepartmentAttribute()
    {
        return $this->appointmentSlot?->physician?->department;
    }

    // Accessor to get scheduled date from slot
    public function getScheduledDateAttribute()
    {
        return $this->appointmentSlot?->date;
    }

    // Accessor to get scheduled time from slot
    public function getScheduledTimeAttribute()
    {
        return $this->appointmentSlot?->start_time;
    }

    // Formatted date accessors
    public function getFormattedDateAttribute()
    {
        return $this->appointmentSlot?->date
            ? Carbon::parse($this->appointmentSlot->date)->format('F d, Y')
            : null;
    }

    public function getFormattedDayAttribute()
    {
        return $this->appointmentSlot?->date
            ? Carbon::parse($this->appointmentSlot->date)->format('l')
            : null;
    }

    public function getFormattedStartTimeAttribute()
    {
        return $this->appointmentSlot?->start_time
            ? Carbon::parse($this->appointmentSlot->start_time)->format('h:i A')
            : null;
    }

    public function getFormattedEndTimeAttribute()
    {
        return $this->appointmentSlot?->end_time
            ? Carbon::parse($this->appointmentSlot->end_time)->format('h:i A')
            : null;
    }
}
