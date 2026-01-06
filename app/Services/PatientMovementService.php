<?php

namespace App\Services;

use App\Models\PatientMovement;
use App\Models\Bed;
use App\Models\Admission;
use Illuminate\Support\Facades\DB;

class PatientMovementService
{
    /**
     * Create the initial movement record when a patient is admitted.
     *
     * @param Admission $admission
     * @param Bed $bed
     * @return PatientMovement
     */
    public function createInitialMovement(Admission $admission, Bed $bed): PatientMovement
    {
        // Ensure room relationship is loaded
        $bed->loadMissing('room');

        return PatientMovement::create([
            'admission_id' => $admission->id,
            'room_id' => $bed->room_id,
            'room_price' => $bed->room->price_per_night,
            'bed_id' => $bed->id,
            'started_at' => now(),
            'ended_at' => null,
        ]);
    }

    /**
     * Transfer a patient to a new bed/room.
     * Ends the current movement and creates a new one.
     *
     * @param Admission $admission
     * @param Bed $newBed
     * @param string|null $transferReason Optional reason for the transfer
     * @return PatientMovement The new movement record
     * @throws \Exception
     */
    public function transferPatient(Admission $admission, Bed $newBed, ?string $transferReason = null): PatientMovement
    {
        return DB::transaction(function () use ($admission, $newBed, $transferReason) {
            $currentMovement = $this->getCurrentMovement($admission);
            
            if ($currentMovement) {
                $this->endMovement($currentMovement);
                
                $oldBed = Bed::find($currentMovement->bed_id);
                if ($oldBed) {
                    $oldBed->update(['status' => 'Available']);
                }
            }

            // Load room relationship for new bed
            $newBed->loadMissing('room');

            $newBed->update(['status' => 'Occupied']);

            $admission->update([
                'bed_id' => $newBed->id,
                'station_id' => $newBed->room->station_id,
            ]);

            return PatientMovement::create([
                'admission_id' => $admission->id,
                'room_id' => $newBed->room_id,
                'room_price' => $newBed->room->price_per_night,
                'bed_id' => $newBed->id,
                'started_at' => now(),
                'ended_at' => null,
            ]);
        });
    }

    /**
     * End the current active movement for an admission.
     *
     * @param PatientMovement $movement
     * @return PatientMovement
     */
    public function endMovement(PatientMovement $movement): PatientMovement
    {
        $movement->update(['ended_at' => now()]);
        return $movement->fresh();
    }

    /**
     * End all active movements for an admission (used during discharge).
     *
     * @param Admission $admission
     * @return int 
     */
    public function endAllMovements(Admission $admission): int
    {
        return PatientMovement::where('admission_id', $admission->id)
            ->whereNull('ended_at')
            ->update(['ended_at' => now()]);
    }

    /**
     * Get the current active movement for an admission.
     *
     * @param Admission $admission
     * @return PatientMovement|null
     */
    public function getCurrentMovement(Admission $admission): ?PatientMovement
    {
        return PatientMovement::where('admission_id', $admission->id)
            ->whereNull('ended_at')
            ->with(['bed', 'room'])
            ->first();
    }

    /**
     * Get all movements for an admission (movement history).
     *
     * @param Admission $admission
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMovementHistory(Admission $admission)
    {
        return PatientMovement::where('admission_id', $admission->id)
            ->with(['bed', 'room'])
            ->orderBy('started_at', 'asc')
            ->get();
    }

    /**
     * Calculate total room charges for an admission based on movements.
     *
     * @param Admission $admission
     * @return float
     */
    public function calculateTotalRoomCharges(Admission $admission): float
    {
        $movements = $this->getMovementHistory($admission);
        $totalCharge = 0.0;

        foreach ($movements as $movement) {
            $endTime = $movement->ended_at ?? now();
            $startTime = $movement->started_at;
            
            // Calculate days (minimum 1 day charge)
            $days = max(1, $startTime->diffInDays($endTime) + 1);
            
            $totalCharge += $movement->room_price * $days;
        }

        return $totalCharge;
    }

    /**
     * Get the duration of stay for an admission in days.
     *
     * @param Admission $admission
     * @return int
     */
    public function getTotalStayDuration(Admission $admission): int
    {
        $firstMovement = PatientMovement::where('admission_id', $admission->id)
            ->orderBy('started_at', 'asc')
            ->first();

        if (!$firstMovement) {
            return 0;
        }

        $lastMovement = PatientMovement::where('admission_id', $admission->id)
            ->orderBy('started_at', 'desc')
            ->first();

        $endTime = $lastMovement->ended_at ?? now();
        
        return $firstMovement->started_at->diffInDays($endTime) + 1;
    }
}
