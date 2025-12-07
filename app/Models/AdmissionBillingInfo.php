<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionBillingInfo extends Model
{
    protected $guarded = [];

    public function admission(): BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }
}