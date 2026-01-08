<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Billing extends Model
{
    protected $fillable = [
        'admission_id',
        'processed_by',
        'breakdown',
        'gross_total',
        'final_total',
        'amount_paid',
        'change',
        'status',
        'receipt_number',
    ];

    protected $casts = [
        'breakdown' => 'array',
        'gross_total' => 'decimal:2',
        'final_total' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'change' => 'decimal:2',
    ];

    // --- RELATIONSHIPS ---

    public function admission(): BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
