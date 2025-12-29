<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionBillingInfo extends Model
{
    protected $fillable = [
        'admission_id',
        'payment_type',
        'primary_insurance_provider',
        'policy_number',
        'approval_code',
        'guarantor_name',
        'guarantor_relationship',
        'guarantor_contact',
    ];

    public function admission(): BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }
}