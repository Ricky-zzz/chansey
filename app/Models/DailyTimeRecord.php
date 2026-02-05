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
}
