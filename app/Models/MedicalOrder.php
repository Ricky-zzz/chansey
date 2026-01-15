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

    public function transferRequests(): HasMany
    {
        return $this->hasMany(TransferRequest::class);
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
        // 1. EXCEPTIONS (PRN and Once)
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

        // 2. NEW ORDER LOGIC (No logs yet - brand new order)
        if (!$this->latestLog) {
            return [
                'color' => 'error',
                'label' => 'START NOW',
                'disabled' => false,
                'is_due' => true,
                'animate' => true
            ];
        }

        // 3. RECURRING LOGIC (Only runs if logs EXIST)
        // Anchor time is the LAST LOG
        $lastAction = $this->latestLog->created_at;
        
        $nextDue = $lastAction->copy()->addHours($this->interval_in_hours);
        $now = now();

        // 4. COMPARE AND DECIDE
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

        if ($minutesRemaining <= 1440) {  // Up to 24 hours, show relative time
            $hoursRemaining = ceil($minutesRemaining / 60);
            return [
                'color' => 'neutral',
                'label' => "Due in {$hoursRemaining}h",
                'disabled' => true, 
                'is_due' => false
            ];
        }

        // For orders more than 24 hours away
        $daysRemaining = ceil($minutesRemaining / 1440);
        return [
            'color' => 'neutral',
            'label' => "Due in {$daysRemaining}d",
            'disabled' => true, 
            'is_due' => false
        ];
    }
}
