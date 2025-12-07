<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admission extends Model
{
    protected $guarded = [];

    protected $casts = [
        'admission_date' => 'datetime',
        'discharge_date' => 'datetime',
        'known_allergies' => 'array', 
    ];

    // --- RELATIONSHIPS ---

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function bed(): BelongsTo
    {
        return $this->belongsTo(Bed::class);
    }

    public function attendingPhysician(): BelongsTo
    {
        return $this->belongsTo(Physician::class, 'attending_physician_id');
    }

    public function admittingClerk(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admitting_clerk_id');
    }

    public function billingInfo(): HasOne
    {
        return $this->hasOne(AdmissionBillingInfo::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(PatientFile::class);
    }
}