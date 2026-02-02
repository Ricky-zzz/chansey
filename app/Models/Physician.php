<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Physician extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'first_name',
        'last_name',
        'department_id',
        'employment_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class, 'attending_physician_id');
    }

    public function treatmentPlans()
    {
        return $this->hasMany(TreatmentPlan::class);
    }

    public function medicalOrders()
    {
        return $this->hasMany(MedicalOrder::class);
    }

    public function appointmentSlots()
    {
        return $this->hasMany(AppointmentSlot::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name} ({$this->department?->name})";
    }
}