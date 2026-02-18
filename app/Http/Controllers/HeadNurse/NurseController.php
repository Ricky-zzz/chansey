<?php

namespace App\Http\Controllers\HeadNurse;

use App\Http\Controllers\Controller;
use App\Models\Nurse;
use App\Models\ShiftSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NurseController extends Controller
{
    /**
     * Display a listing of nurses under this head nurse.
     */
    public function index()
    {
        $me = Auth::user()->nurse;

        // Head nurse manages all nurses in their station (excluding themselves)
        $nurses = Nurse::with(['user', 'station', 'shiftSchedule'])
            ->where('station_id', $me->station_id)
            ->where('id', '!=', $me->id)
            ->orderBy('last_name')
            ->paginate(10);

        $title = $me->station->station_name . ' Nurses';
        $schedules = ShiftSchedule::orderBy('name')->get();

        return view('nurse.headnurse.nurses.index', compact('nurses', 'schedules', 'title'));
    }

    /**
     * Update the shift schedule for a nurse.
     */
    public function updateSchedule(Request $request, Nurse $nurse)
    {
        $me = Auth::user()->nurse;

        // Verify the head nurse has authority over this nurse (must be in same station)
        if ($nurse->station_id !== $me->station_id) {
            abort(403, 'You can only manage nurses in your station.');
        }

        $validated = $request->validate([
            'shift_schedule_id' => 'nullable|exists:shift_schedules,id',
        ]);

        $nurse->update([
            'shift_schedule_id' => $validated['shift_schedule_id'],
        ]);

        $scheduleName = $nurse->fresh()->shiftSchedule?->name ?? 'Unassigned';

        return redirect()->route('nurse.headnurse.nurses.index')
            ->with('success', "Shift schedule for {$nurse->first_name} {$nurse->last_name} updated to: {$scheduleName}");
    }

    /**
     * Get scheduled nurses for a specific date in this head nurse's station.
     */
    public function getScheduledNurses(Request $request)
    {
        $me = Auth::user()->nurse;
        $date = $request->query('date');

        if (!$date) {
            return response()->json(['error' => 'Date is required'], 400);
        }

        $dayOfWeek = Carbon::parse($date)->format('l'); // e.g., "Monday"
        $daySingleLetter = substr($dayOfWeek, 0, 1); // e.g., "M"
        $dayThreeLetter = substr($dayOfWeek, 0, 3); // e.g., "Mon"

        $query = Nurse::with(['user', 'shiftSchedule'])
            ->where('id', '!=', $me->id)
            ->where('station_id', $me->station_id)
            ->whereNotNull('shift_schedule_id');

        $nurses = $query->get()->filter(function ($nurse) use ($daySingleLetter, $dayThreeLetter) {
            // Check if the shift schedule includes this day of week
            // Support both formats: "M,W,F" and "Mon,Wed,Fri"
            $daysArray = array_map('trim', explode(',', $nurse->shiftSchedule->days_short ?? ''));
            return in_array($daySingleLetter, $daysArray) || in_array($dayThreeLetter, $daysArray);
        })->values();

        return response()->json([
            'date' => $date,
            'dayOfWeek' => $dayOfWeek,
            'nurses' => $nurses->map(function ($nurse) {
                return [
                    'id' => $nurse->id,
                    'employee_id' => $nurse->employee_id,
                    'first_name' => $nurse->first_name,
                    'last_name' => $nurse->last_name,
                    'license_number' => $nurse->license_number,
                    'profile_image_path' => $nurse->user->profile_image_path,
                    'schedule_name' => $nurse->shiftSchedule->name,
                    'start_time' => Carbon::parse($nurse->shiftSchedule->start_time)->format('g:i A'),
                    'end_time' => Carbon::parse($nurse->shiftSchedule->end_time)->format('g:i A'),
                ];
            }),
        ]);
    }
}
