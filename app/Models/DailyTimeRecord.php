<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyTimeRecord extends Model
{
    protected $fillable = [
        'user_id',
        'time_in',
        'time_out',
        'total_hours',
        'status'
    ];

    protected $casts = [
        'time_in' => 'datetime',
        'time_out' => 'datetime',
        'total_hours' => 'decimal:2'
    ];

    protected $appends = ['formatted_date', 'formatted_date_time', 'formatted_time_in', 'formatted_time_out', 'formatted_hours', 'formatted_day'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Format the date portion of time_in (M d, Y format)
     */
    public function getFormattedDateAttribute()
    {
        return $this->time_in->format('M d, Y');
    }

    /**
     * Format the full date and time of time_in (M d, Y H:i format)
     */
    public function getFormattedDateTimeAttribute()
    {
        return $this->time_in->format('M d, Y H:i');
    }

    /**
     * Format time_in as time only (h:i A format - 12 hour)
     */
    public function getFormattedTimeInAttribute()
    {
        return $this->time_in->format('H:i');
    }

    /**
     * Format time_out as time only (h:i A format - 12 hour)
     */
    public function getFormattedTimeOutAttribute()
    {
        return $this->time_out ? $this->time_out->format('H:i') : '—';
    }

    /**
     * Format total hours with 2 decimals and 'h' suffix
     */
    public function getFormattedHoursAttribute()
    {
        return $this->total_hours ? number_format($this->total_hours, 2) . 'h' : '—';
    }

    /**
     * Get day of week (l format - Monday, Tuesday, etc.)
     */
    public function getFormattedDayAttribute()
    {
        return $this->time_in->format('l');
    }
}
