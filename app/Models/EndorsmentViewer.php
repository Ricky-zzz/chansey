<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EndorsmentViewer extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'endorsment_id',
        'user_id',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function endorsment(): BelongsTo
    {
        return $this->belongsTo(Endorsment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
