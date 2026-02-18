<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Endorsment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'station_id',
        'admission_id',
        'outgoing_nurse_id',
        'incoming_nurse_id',
        'submitted_by_id',
        'submitted_at',
        'diagnosis',
        'current_condition',
        'code_status',
        'date_admitted',
        'known_allergies',
        'medication_history',
        'past_medical_history',
        'latest_vitals',
        'pain_scale',
        'iv_lines',
        'wounds',
        'labs_pending',
        'abnormal_findings',
        'upcoming_medications',
        'labs_follow_up',
        'monitor_instructions',
        'special_precautions',
        'bed_occupancy',
        'equipment_issues',
        'pending_admissions',
        'station_issues',
        'critical_incidents',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'date_admitted' => 'datetime',
        'created_at' => 'datetime',
        // JSON columns
        'known_allergies' => 'array',
        'medication_history' => 'array',
        'past_medical_history' => 'array',
        'latest_vitals' => 'array',
        'iv_lines' => 'array',
        'wounds' => 'array',
        'labs_pending' => 'array',
        'abnormal_findings' => 'array',
        'upcoming_medications' => 'array',
        'labs_follow_up' => 'array',
        'monitor_instructions' => 'array',
        'special_precautions' => 'array',
        'equipment_issues' => 'array',
        'pending_admissions' => 'array',
        'station_issues' => 'array',
        'critical_incidents' => 'array',
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

    // outgoing_nurse_id is a direct foreign key to users
    public function outgoingNurse()
    {
        return $this->belongsTo(User::class, 'outgoing_nurse_id');
    }

    // incoming_nurse_id is a foreign key to nurses
    public function incomingNurse(): BelongsTo
    {
        return $this->belongsTo(Nurse::class, 'incoming_nurse_id');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by_id');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(EndorsmentNote::class);
    }

    public function viewers(): HasMany
    {
        return $this->hasMany(EndorsmentViewer::class);
    }

    /**
     * Scopes & Methods
     */

    /**
     * Check if endorsement is locked (submitted)
     */
    public function isLocked(): bool
    {
        return $this->submitted_at !== null;
    }

    /**
     * Check if endorsement is a draft (not submitted)
     */
    public function isDraft(): bool
    {
        return $this->submitted_at === null;
    }

    /**
     * Get view count
     */
    public function getViewCount(): int
    {
        return $this->viewers()->count();
    }

    /**
     * Get unique viewers count
     */
    public function getUniqueViewerCount(): int
    {
        return $this->viewers()->distinct('user_id')->count();
    }

    /**
     * Get last viewer info
     */
    public function getLastViewer()
    {
        return $this->viewers()
            ->with('user')
            ->latest('viewed_at')
            ->first();
    }

    /**
     * Get amendment notes (append-only)
     */
    public function getAmendments()
    {
        return $this->notes()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
