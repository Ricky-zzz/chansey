<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'full_name',
        'license_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
