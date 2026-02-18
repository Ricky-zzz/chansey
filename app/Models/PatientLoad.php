<?php

namespace App\Models;

use App\Enums\AcuityLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientLoad extends Model
{
    protected $fillable = [
        'patient_id',
        'nurse_id',
        'acuity',
        'description',
    ];

    protected $casts = [
        'acuity' => AcuityLevel::class,
    ];

    /**
     * Get the patient record.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the nurse record.
     */
    public function nurse(): BelongsTo
    {
        return $this->belongsTo(Nurse::class);
    }

    /**
     * Get the score for this acuity level.
     */
    public function getScoreAttribute(): int
    {
        return $this->acuity->score();
    }
}
