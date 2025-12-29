<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Patient extends Model
{
    protected $fillable = [
        'patient_unique_id',
        'created_by_user_id',
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'sex',
        'civil_status',
        'nationality',
        'religion',
        'address_permanent',
        'address_present',
        'contact_number',
        'email',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_number',
        'philhealth_number',
        'senior_citizen_id',
    ];

    protected $casts = [
        'date_of_birth' => 'datetime',
    ];

    // --- RELATIONSHIPS ---

    public function admissions(): HasMany
    {
        return $this->hasMany(Admission::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(PatientFile::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }
    
    // Calculate Age automatically
    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->diffInYears();
    }
}