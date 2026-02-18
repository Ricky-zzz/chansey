<?php

namespace App\Http\Controllers\HeadNurse;

use App\Http\Controllers\Controller;
use App\Models\DateSchedule;
use App\Models\Nurse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DateScheduleController extends Controller
{
    /**
     * Store a newly created date schedule in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'nurse_id' => 'required|exists:nurses,id',
            'start_shift' => 'required|date_format:H:i',
            'end_shift' => 'required|date_format:H:i|after:start_shift',
            'assignment' => 'nullable|string|max:255',
        ]);

        // Verify the nurse belongs to the current head nurse's station
        $nurse = Nurse::findOrFail($validated['nurse_id']);
        $headNurse = Auth::user()->nurse;

        // Check authorization: head nurse can only manage nurses in their station
        if ($nurse->station_id !== $headNurse->station_id && $headNurse->role_level !== 'Chief') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $dateSchedule = DateSchedule::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Schedule assigned successfully',
            'date_schedule' => $dateSchedule->load('nurse'),
        ]);
    }

    /**
     * Update the specified date schedule in storage.
     */
    public function update(Request $request, DateSchedule $dateSchedule)
    {
        $validated = $request->validate([
            'date' => 'sometimes|date',
            'nurse_id' => 'sometimes|exists:nurses,id',
            'start_shift' => 'sometimes|date_format:H:i',
            'end_shift' => 'sometimes|date_format:H:i',
            'assignment' => 'nullable|string|max:255',
        ]);

        // Verify authorization
        $nurse = $dateSchedule->nurse;
        $headNurse = Auth::user()->nurse;

        if ($nurse->station_id !== $headNurse->station_id && $headNurse->role_level !== 'Chief') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // If updating nurse_id, verify the new nurse belongs to the authorized station
        if (isset($validated['nurse_id']) && $validated['nurse_id'] !== $nurse->id) {
            $newNurse = Nurse::findOrFail($validated['nurse_id']);
            if ($newNurse->station_id !== $headNurse->station_id && $headNurse->role_level !== 'Chief') {
                return response()->json(['error' => 'Cannot assign nurse from different station'], 403);
            }
        }

        // Validate end_shift is after start_shift if both provided
        if (isset($validated['start_shift']) && isset($validated['end_shift'])) {
            // Both provided, validate
            if ($validated['end_shift'] <= $validated['start_shift']) {
                return response()->json(['error' => 'End shift must be after start shift'], 422);
            }
        } elseif (isset($validated['end_shift']) && !isset($validated['start_shift'])) {
            // Only end_shift provided, compare with existing start_shift
            if ($validated['end_shift'] <= $dateSchedule->start_shift) {
                return response()->json(['error' => 'End shift must be after start shift'], 422);
            }
        }

        $dateSchedule->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Schedule updated successfully',
            'date_schedule' => $dateSchedule->load('nurse'),
        ]);
    }

    /**
     * Remove the specified date schedule from storage.
     */
    public function destroy(DateSchedule $dateSchedule)
    {
        // Verify authorization
        $nurse = $dateSchedule->nurse;
        $headNurse = Auth::user()->nurse;

        if ($nurse->station_id !== $headNurse->station_id && $headNurse->role_level !== 'Chief') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $dateSchedule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Schedule deleted successfully',
        ]);
    }
}
