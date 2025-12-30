<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicalLog extends Model
{
    protected $fillable = [
        'admission_id',
        'user_id',
        'medical_order_id',
        'type',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    // --- RELATIONSHIPS ---

    public function admission(): BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function medicalOrder(): BelongsTo
    {
        return $this->belongsTo(MedicalOrder::class);
    }
}
