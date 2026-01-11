<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['first_name', 'last_name', 'email', 'contact_number', 'department_id', 'purpose', 'physician_id', 'scheduled_at', 'end_time', 'status'];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function physician()
    {
        return $this->belongsTo(Physician::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}