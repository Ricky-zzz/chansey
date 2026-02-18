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

    protected $appends = ['formatted_date_of_birth'];

    // --- RELATIONSHIPS ---

    public function admissions(): HasMany
    {
        return $this->hasMany(Admission::class);
    }

    public function activeAdmission()
    {
        return $this->hasOne(Admission::class)->where('status', 'Admitted');
    }

    public function files(): HasMany
    {
        return $this->hasMany(PatientFile::class);
    }

    public function patientLoads(): HasMany
    {
        return $this->hasMany(PatientLoad::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function getFullNameAttribute(): string
    {
        $middleInitial = $this->middle_name ? ' ' . strtoupper($this->middle_name[0]) . '.' : '';
        return trim("{$this->last_name}, {$this->first_name}" . $middleInitial);
    }

    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->diffInYears();
    }

    /**
     * Format date of birth (M d, Y)
     */
    public function getFormattedDateOfBirthAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->format('M d, Y') : 'N/A';
    }
}
