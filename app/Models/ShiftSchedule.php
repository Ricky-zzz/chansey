<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'monday' => 'boolean',
        'tuesday' => 'boolean',
        'wednesday' => 'boolean',
        'thursday' => 'boolean',
        'friday' => 'boolean',
        'saturday' => 'boolean',
        'sunday' => 'boolean',
    ];

    protected $appends = ['total_hours_per_week', 'days_short'];

    /**
     * Get total hours per week based on checked days
     */
    public function getTotalHoursPerWeekAttribute(): float
    {
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);
        
        // Handle overnight shifts
        if ($end->lt($start)) {
            $hoursPerDay = 24 - $start->diffInMinutes($end) / 60;
        } else {
            $hoursPerDay = $start->diffInMinutes($end) / 60;
        }

        $daysCount = collect([
            $this->monday,
            $this->tuesday,
            $this->wednesday,
            $this->thursday,
            $this->friday,
            $this->saturday,
            $this->sunday,
        ])->filter()->count();

        return round($hoursPerDay * $daysCount, 1);
    }

    /**
     * Get short day names (M, T, W, TH, F, SA, SU)
     */
    public function getDaysShortAttribute(): string
    {
        $days = [];
        if ($this->monday) $days[] = 'M';
        if ($this->tuesday) $days[] = 'T';
        if ($this->wednesday) $days[] = 'W';
        if ($this->thursday) $days[] = 'TH';
        if ($this->friday) $days[] = 'F';
        if ($this->saturday) $days[] = 'SA';
        if ($this->sunday) $days[] = 'SU';

        return implode(', ', $days) ?: 'None';
    }

    public function nurses()
    {
        return $this->hasMany(Nurse::class);
    }
}
