<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EndorsmentNote extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'endorsment_id',
        'user_id',
        'note',
        'note_type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
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
