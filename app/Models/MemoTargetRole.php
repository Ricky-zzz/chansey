<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemoTargetRole extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'memo_id',
        'role',
    ];

    public function memo(): BelongsTo
    {
        return $this->belongsTo(Memo::class);
    }
}
