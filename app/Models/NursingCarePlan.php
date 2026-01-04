<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NursingCarePlan extends Model
{
    protected $guarded = [];

    protected $casts = [
        'planning' => 'array',
        'interventions' => 'array',
    ];

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function nurse()
    {
        return $this->belongsTo(Nurse::class);
    }
}
