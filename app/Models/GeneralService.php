<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralService extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'first_name',
        'last_name',
        'assigned_area',
        'shift_start',
        'shift_end',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}