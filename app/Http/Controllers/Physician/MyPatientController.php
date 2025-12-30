<?php

namespace App\Http\Controllers\Physician;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyPatientController extends Controller
{
    // THE ROUNDS LIST
    public function index(Request $request)
    {
        $physician = Auth::user()->physician;

        // Base Query: "Active Admissions Assigned to Me"
        $query = Admission::with(['patient', 'bed.room.station'])
            ->where('admissions.status', 'Admitted')
            ->where('admissions.attending_physician_id', $physician->id);

        // Search Logic (Same as AdmissionController but scoped)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('admission_number', 'like', "%{$search}%")
                  ->orWhereHas('patient', function ($subQ) use ($search) {
                        $subQ->where('last_name', 'like', "{$search}%")
                             ->orWhere('first_name', 'like', "{$search}%");
                  });
            });
        }

        // Sort by Location (Station -> Room) for logical rounds
        $myPatients = $query
            ->join('beds', 'admissions.bed_id', '=', 'beds.id')
            ->join('rooms', 'beds.room_id', '=', 'rooms.id')
            ->join('stations', 'rooms.station_id', '=', 'stations.id')
            ->orderBy('stations.station_name')
            ->orderBy('rooms.room_number')
            ->select('admissions.*') // Prevent ID collision
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('physician.patients.index', compact('myPatients'));
    }

    // THE PATIENT CHART (Read-Only Detail View)
    public function show($id)
    {
        // Ensure the doctor is only viewing THEIR patient (Security)
        $admission = Admission::with([
            'patient',
            'bed.room.station',
            'clinicalLogs', // Need logs to see vitals history
            'doctorOrders', // Need orders to see what's pending
            'treatmentPlan' // The strategy
        ])
        ->where('id', $id)
        ->where('attending_physician_id', Auth::user()->physician->id)
        ->firstOrFail();

        return view('physician.patients.show', compact('admission'));
    }
}