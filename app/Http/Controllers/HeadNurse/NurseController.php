<?php

namespace App\Http\Controllers\HeadNurse;

use App\Http\Controllers\Controller;
use App\Models\Nurse;
use App\Models\DateSchedule;
use App\Models\Admission;
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

        $nurses = Nurse::with(['user', 'station', 'dateSchedules'])
            ->where('station_id', $me->station_id)
            ->where('id', '!=', $me->id)
            ->orderBy('last_name')
            ->paginate(10);

        $title = $me->station->station_name . ' Nurses';

        $availableNurses = Nurse::where('station_id', $me->station_id)
            ->where('id', '!=', $me->id)
            ->orderBy('last_name')
            ->get();

        $nurseTypes = \App\Models\NurseType::orderBy('name')->get();

        $stationPatients = Admission::with('patient')
            ->whereIn('status', ['Admitted', 'Ready for Discharge'])
            ->where('station_id', $me->station_id)
            ->orderByRaw('(SELECT last_name FROM patients WHERE patients.id = admissions.patient_id)')
            ->get()
            ->map(fn($a) => [
                'id' => $a->patient->id,
                'name' => $a->patient->last_name . ', ' . $a->patient->first_name,
                'patient_unique_id' => $a->patient->patient_unique_id,
            ]);

        return view('nurse.headnurse.nurses.index', compact('nurses', 'title', 'availableNurses', 'nurseTypes', 'stationPatients'));
    }

    public function getNurseDateSchedules(Nurse $nurse)
    {
        $me = Auth::user()->nurse;

        if ($nurse->station_id !== $me->station_id && $me->role_level !== 'Chief') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $dateSchedules = DateSchedule::where('nurse_id', $nurse->id)
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($ds) {
                return [
                    'id' => $ds->id,
                    'date' => $ds->date->format('Y-m-d'),
                    'start_shift' => $ds->start_shift,
                    'end_shift' => $ds->end_shift,
                    'assignment' => $ds->assignment,
                ];
            });

        return response()->json([
            'success' => true,
            'schedules' => $dateSchedules,
        ]);
    }


    public function getScheduledNurses(Request $request)
    {
        $me = Auth::user()->nurse;
        $date = $request->query('date');

        if (!$date) {
            return response()->json(['error' => 'Date is required'], 400);
        }

        try {
            $parsedDate = Carbon::parse($date)->toDateString();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format'], 400);
        }

        $dayOfWeek = Carbon::parse($date)->format('l'); // e.g., "Monday"

        // Get all date schedules for this specific date in the head nurse's station
        $dateSchedules = DateSchedule::with(['nurse' => function ($query) use ($me) {
            $query->where('station_id', $me->station_id)->where('id', '!=', $me->id)->with('user');
        }])
            ->where('date', $parsedDate)
            ->get();

        $nurses = $dateSchedules->filter(function ($ds) {
            return $ds->nurse !== null;
        })->map(function ($dateSchedule) {
            return [
                'id' => $dateSchedule->id,
                'nurse_id' => $dateSchedule->nurse->id,
                'employee_id' => $dateSchedule->nurse->employee_id,
                'first_name' => $dateSchedule->nurse->first_name,
                'last_name' => $dateSchedule->nurse->last_name,
                'license_number' => $dateSchedule->nurse->license_number,
                'profile_image_path' => $dateSchedule->nurse->user->profile_image_path,
                'start_time' => Carbon::parse($dateSchedule->start_shift)->format('g:i A'),
                'end_time' => Carbon::parse($dateSchedule->end_shift)->format('g:i A'),
                'assignment' => $dateSchedule->assignment,
                'dateschedule_id' => $dateSchedule->id,
            ];
        })->values();

        return response()->json([
            'date' => $date,
            'dayOfWeek' => $dayOfWeek,
            'nurses' => $nurses,
        ]);
    }
}
