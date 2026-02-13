<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StationTask extends Model
{
    protected $fillable = [
        'station_id',
        'created_by_user_id',
        'assigned_to_nurse_id',
        'admission_id',
        'title',
        'description',
        'priority',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function assignee()
    {
        return $this->belongsTo(Nurse::class, 'assigned_to_nurse_id');
    }

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }
}
