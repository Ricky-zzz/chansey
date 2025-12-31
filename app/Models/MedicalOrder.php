<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicalOrder extends Model
{
    protected $fillable = [
        'admission_id',
        'physician_id',
        'type',
        'instruction',
        'medicine_id',
        'quantity',
        'frequency',
        'status',
        'fulfilled_by_user_id',
        'fulfilled_at',
    ];

    protected $casts = [
        'fulfilled_at' => 'datetime',
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

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function fulfilledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'fulfilled_by_user_id');
    }

    public function clinicalLogs(): HasMany
    {
        return $this->hasMany(ClinicalLog::class);
    }
    public function latestLog()
    {
        return $this->hasOne(ClinicalLog::class)->latestOfMany();
    }

    public function getIntervalInHoursAttribute()
    {
        return match ($this->frequency) {
            'Every 1 Hour'  => 1,
            'Every 2 Hours' => 2, 
            'Every 4 Hours' => 4,
            'Every 6 Hours' => 6,
            'Every 8 Hours' => 8,
            'Every 12 Hours' => 12,
            'Daily'         => 24,
            default         => null,
        };
    }
}
