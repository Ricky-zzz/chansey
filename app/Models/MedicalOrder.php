<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

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

    public function result()
    {
        return $this->hasOne(PatientFile::class, 'medical_order_id');
    }

    /**
     * Get the lab result file uploaded for this order.
     * Only applies when this order is type 'Laboratory'.
     */
    public function labResultFile()
    {
        return $this->hasOne(PatientFile::class, 'medical_order_id')
                    ->where('document_type', 'Lab Result')
                    ->ofMany('id', 'max');
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


    public function getTimerStatusAttribute()
    {
        if ($this->frequency === 'PRN') {
            return [
                'color' => 'success', 
                'label' => 'Available (PRN)',
                'disabled' => false,
                'is_due' => false
            ];
        }

        if ($this->frequency === 'Once' || !$this->interval_in_hours) {
            return [
                'color' => 'primary',
                'label' => 'Execute Order',
                'disabled' => false,
                'is_due' => true
            ];
        }

        $lastAction = $this->latestLog ? $this->latestLog->created_at : $this->created_at;
        
        $nextDue = $lastAction->copy()->addHours($this->interval_in_hours);
        $now = now();

        
        if ($now->greaterThanOrEqualTo($nextDue)) {
            $overdueBy = $now->diffForHumans($nextDue, ['syntax' => Carbon::DIFF_ABSOLUTE]);
            return [
                'color' => 'error', 
                'label' => "DUE NOW (Late by $overdueBy)",
                'disabled' => false,
                'is_due' => true,
                'animate' => true 
            ];
        }

        $minutesRemaining = $now->diffInMinutes($nextDue);

        if ($minutesRemaining <= 30) {
            return [
                'color' => 'warning', 
                'label' => "Due in {$minutesRemaining}m",
                'disabled' => false,
                'is_due' => true
            ];
        }

        return [
            'color' => 'neutral',
            'label' => "Next: " . $nextDue->format('H:i'), 
            'disabled' => true, 
            'is_due' => false
        ];
    }
}
