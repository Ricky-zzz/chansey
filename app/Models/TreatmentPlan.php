<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TreatmentPlan extends Model
{
    protected $fillable = [
        'admission_id',
        'physician_id',
        'main_problem',
        'goals',
        'interventions',
        'expected_outcome',
        'evaluation',
        'status',
    ];

    protected $casts = [
        'goals' => 'array',
        'interventions' => 'array',
    ];

    // --- RELATIONSHIPS ---

    public function admission(): BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }

    public function physician(): BelongsTo
    {
        return $this->belongsTo(Physician::class);
    }
}
