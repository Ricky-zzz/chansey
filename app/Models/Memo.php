<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Memo extends Model
{
    protected $fillable = [
        'created_by_user_id',
        'title',
        'content',
        'target_roles',
        'target_units',
        'target_stations',
        'attachment_path',
    ];

    protected $casts = [
        'target_roles' => 'array',
        'target_units' => 'array',
        'target_stations' => 'array',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
