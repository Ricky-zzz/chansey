<?php

namespace App\Services;

use App\Models\Endorsment;
use App\Models\EndorsmentNote;
use App\Models\EndorsmentViewer;
use App\Models\Admission;
use App\Models\Station;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EndorsmentService
{
    /**
     * Create a draft endorsement
     */
    public function createEndorsement(
        int $stationId,
        int $admissionId,
        int $outgoingNurseId,
        int $incomingNurseId,
        array $data
    ): Endorsment {
        return Endorsment::create([
            'station_id' => $stationId,
            'admission_id' => $admissionId,
            'outgoing_nurse_id' => $outgoingNurseId,
            'incoming_nurse_id' => $incomingNurseId,
            'diagnosis' => $data['diagnosis'] ?? null,
            'current_condition' => $data['current_condition'] ?? null,
            'code_status' => $data['code_status'] ?? 'Low',
            'date_admitted' => $data['date_admitted'] ?? null,
            'known_allergies' => $data['known_allergies'] ?? null,
            'medication_history' => $data['medication_history'] ?? null,
            'past_medical_history' => $data['past_medical_history'] ?? null,
            'latest_vitals' => $data['latest_vitals'] ?? null,
            'pain_scale' => $data['pain_scale'] ?? null,
            'iv_lines' => $data['iv_lines'] ?? null,
            'wounds' => $data['wounds'] ?? null,
            'labs_pending' => $data['labs_pending'] ?? null,
            'abnormal_findings' => $data['abnormal_findings'] ?? null,
            'upcoming_medications' => $data['upcoming_medications'] ?? null,
            'labs_follow_up' => $data['labs_follow_up'] ?? null,
            'monitor_instructions' => $data['monitor_instructions'] ?? null,
            'special_precautions' => $data['special_precautions'] ?? null,
            'bed_occupancy' => $data['bed_occupancy'] ?? null,
            'equipment_issues' => $data['equipment_issues'] ?? null,
            'pending_admissions' => $data['pending_admissions'] ?? null,
            'station_issues' => $data['station_issues'] ?? null,
            'critical_incidents' => $data['critical_incidents'] ?? null,
        ]);
    }

    /**
     * Submit (lock) an endorsement
     *
     * Sets submitted_at timestamp and submitted_by_id, making it immutable
     */
    public function submitEndorsement(int $endorsementId, int $submittedByUserId): Endorsment
    {
        $endorsement = Endorsment::findOrFail($endorsementId);

        if ($endorsement->isLocked()) {
            throw new \Exception('This endorsement has already been submitted and cannot be modified.');
        }

        $endorsement->update([
            'submitted_at' => now(),
            'submitted_by_id' => $submittedByUserId,
        ]);

        // Optional: Fire event for notification
        // event(new EndorsementSubmitted($endorsement));

        return $endorsement->refresh();
    }

    /**
     * Add an amendment note (append-only, no edits to original endorsement)
     *
     * Allows both creator and receiver to document issues/corrections without modifying the endorsement
     */
    public function addNote(int $endorsementId, int $userId, string $note, string $noteType = 'amendment'): EndorsmentNote
    {
        $endorsement = Endorsment::findOrFail($endorsementId);

        // Optional: Can only add notes to submitted endorsements
        if (!$endorsement->isLocked()) {
            throw new \Exception('Notes can only be added to submitted endorsements.');
        }

        return EndorsmentNote::create([
            'endorsment_id' => $endorsementId,
            'user_id' => $userId,
            'note' => $note,
            'note_type' => $noteType,
        ]);
    }

    /**
     * Record a view of the endorsement
     *
     * Logs every view (including multiple views by same person) for audit trail
     */
    public function recordView(int $endorsementId, int $userId): EndorsmentViewer
    {
        return EndorsmentViewer::create([
            'endorsment_id' => $endorsementId,
            'user_id' => $userId,
            'viewed_at' => now(),
        ]);
    }

    /**
     * Get all endorsements created by a nurse (with pagination)
     *
     * Used by outgoing nurse to see drafts and submitted endorsements they created
     */
    public function getCreatedEndorsements(int $nurseUserId, int $perPage = 15)
    {
        return Endorsment::where('outgoing_nurse_id', $nurseUserId)
            ->with('admission', 'incomingNurse', 'station')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    /**
     * Get all endorsements received by a nurse (only submitted ones)
     *
     * Used by incoming nurse to see all endorsements they need to acknowledge
     */
    public function getReceivedEndorsements(int $nurseUserId)
    {
        return Endorsment::where('incoming_nurse_id', $nurseUserId)
            ->whereNotNull('submitted_at') // Only show submitted endorsements
            ->with('admission', 'outgoingNurse', 'station', 'viewers', 'notes')
            ->orderByDesc('submitted_at')
            ->get();
    }

    /**
     * Get all endorsements for a station (for head nurse)
     *
     * Shows all endorsements made within a specific station
     */
    public function getStationEndorsements(int $stationId, int $perPage = 20)
    {
        return Endorsment::where('station_id', $stationId)
            ->whereNotNull('submitted_at') // Only show submitted
            ->with('admission', 'outgoingNurse', 'incomingNurse', 'station')
            ->orderByDesc('submitted_at')
            ->paginate($perPage);
    }

    /**
     * Get viewer information for an endorsement
     */
    public function getViewerInfo(int $endorsementId)
    {
        return [
            'total_views' => EndorsmentViewer::where('endorsment_id', $endorsementId)->count(),
            'unique_viewers' => EndorsmentViewer::where('endorsment_id', $endorsementId)
                ->distinct('user_id')
                ->count(),
            'last_viewer' => EndorsmentViewer::where('endorsment_id', $endorsementId)
                ->with('user')
                ->latest('viewed_at')
                ->first(),
            'all_viewers' => EndorsmentViewer::where('endorsment_id', $endorsementId)
                ->with('user')
                ->orderByDesc('viewed_at')
                ->get(),
        ];
    }

    /**
     * Get amendment notes for an endorsement
     */
    public function getNotes(int $endorsementId)
    {
        return EndorsmentNote::where('endorsment_id', $endorsementId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
