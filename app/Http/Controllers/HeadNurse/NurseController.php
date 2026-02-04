<?php

namespace App\Http\Controllers\HeadNurse;

use App\Http\Controllers\Controller;
use App\Models\Nurse;
use App\Models\ShiftSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NurseController extends Controller
{
    /**
     * Display a listing of nurses under this head nurse.
     */
    public function index()
    {
        $me = Auth::user()->nurse;
        
        if ($me->designation === 'Clinical') {
            // Clinical Head manages nurses in THEIR Station (excluding themselves)
            $nurses = Nurse::with(['user', 'station', 'shiftSchedule'])
                ->where('station_id', $me->station_id)
                ->where('id', '!=', $me->id)
                ->orderBy('last_name')
                ->paginate(10);
            
            $title = $me->station->station_name . ' Nurses';
        } else {
            // Admitting Head manages ALL Admitting Nurses (excluding themselves)
            $nurses = Nurse::with(['user', 'shiftSchedule'])
                ->where('designation', 'Admitting')
                ->where('id', '!=', $me->id)
                ->orderBy('last_name')
                ->paginate(10);
            
            $title = 'Admitting Nurses';
        }

        $schedules = ShiftSchedule::orderBy('name')->get();

        return view('nurse.headnurse.nurses.index', compact('nurses', 'schedules', 'title'));
    }

    /**
     * Update the shift schedule for a nurse.
     */
    public function updateSchedule(Request $request, Nurse $nurse)
    {
        $me = Auth::user()->nurse;

        // Verify the head nurse has authority over this nurse
        if ($me->designation === 'Clinical') {
            if ($nurse->station_id !== $me->station_id) {
                abort(403, 'You can only manage nurses in your station.');
            }
        } else {
            if ($nurse->designation !== 'Admitting') {
                abort(403, 'You can only manage admitting nurses.');
            }
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
}
