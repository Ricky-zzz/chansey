<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nurse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'first_name',
        'last_name',
        'license_number',
        'designation',
        'station_id',
        'shift_start',
        'shift_end',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function station()
    {
        return $this->belongsTo(Station::class);
    }
}
