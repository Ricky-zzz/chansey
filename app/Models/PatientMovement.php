<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientMovement extends Model
{
    protected $fillable = [
        'admission_id',
        'room_id',
        'bed_id',
        'room_price',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function bed()
    {
        return $this->belongsTo(Bed::class);
    }
}
