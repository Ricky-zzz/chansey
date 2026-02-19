<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'station_id',
        'admission_id',
        'created_by_id',
        'resolved_by_id',
        'time_of_incident',
        'time_reported',
        'location_details',
        'incident_category',
        'severity_level',
        'narrative',
        'what_happened',
        'how_discovered',
        'action_taken',
        'injury',
        'injury_type',
        'vitals',
        'doctor_notified',
        'family_notified',
        'root_cause',
        'follow_up_actions',
        'follow_up_instructions',
        'witness',
        'status',
        'resolved_at',
    ];

    protected $casts = [
        'time_of_incident' => 'datetime',
        'time_reported' => 'datetime',
        'resolved_at' => 'datetime',
        'injury' => 'boolean',
        'doctor_notified' => 'boolean',
        'family_notified' => 'boolean',
        'vitals' => 'array',
        'witness' => 'array',
    ];

    /**
     * Relationships
     */
    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }

    public function admission(): BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by_id');
    }

    /**
     * Many-to-many relationship with Users (involved staff)
     */
    public function involvedStaff(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'incident_staff', 'incident_id', 'staff_id')
            ->withPivot('role_in_incident');
    }

    /**
     * Helper Methods
     */
    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }

    public function isUnderInvestigation(): bool
    {
        return $this->status === 'investigating';
    }

    public function isUnresolved(): bool
    {
        return $this->status === 'unresolved';
    }

    public function getInvolvedStaffCount(): int
    {
        return $this->involvedStaff()->count();
    }

    public function isStaffInvolved(int $userId): bool
    {
        return $this->involvedStaff()->where('staff_id', $userId)->exists();
    }
}
