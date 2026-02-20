<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Memo extends Model
{
    protected $fillable = [
        'created_by_user_id',
        'title',
        'content',
        'attachment_path',
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Target roles via pivot (Staff, Head, Supervisor, Chief)
     */
    public function targetRoles(): HasMany
    {
        return $this->hasMany(MemoTargetRole::class);
    }

    /**
     * Target units via pivot
     */
    public function targetUnits(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'memo_target_units');
    }

    /**
     * Target stations via pivot
     */
    public function targetStations(): BelongsToMany
    {
        return $this->belongsToMany(Station::class, 'memo_target_stations');
    }

    // ==========================================
    // HELPER METHODS
    // ==========================================

    /**
     * Sync target roles (accepts array of role strings)
     */
    public function syncTargetRoles(array $roles): void
    {
        $this->targetRoles()->delete();
        foreach ($roles as $role) {
            $this->targetRoles()->create(['role' => $role]);
        }
    }

    /**
     * Get array of target role strings
     */
    public function getTargetRoleNames(): array
    {
        return $this->targetRoles->pluck('role')->toArray();
    }
}
