<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Patient extends Model
{
    protected $guarded = [];

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