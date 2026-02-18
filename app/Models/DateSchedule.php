<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DateSchedule extends Model
{
    protected $table = 'date_schedules';

    protected $fillable = [
        'date',
        'nurse_id',
        'start_shift',
        'end_shift',
        'assignment',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the nurse associated with this date schedule.
     */
    public function nurse(): BelongsTo
    {
        return $this->belongsTo(Nurse::class);
    }
}
