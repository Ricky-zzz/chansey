<?php

namespace App\Http\Controllers\Physician;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Medicine;
use App\Models\MedicalOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyPatientController extends Controller
{
    // THE ROUNDS LIST
    public function index(Request $request)
    {
        $physician = Auth::user()->physician;

        // Base Query: 
        $query = Admission::with(['patient', 'bed.room.station'])
            ->where('admissions.status', 'Admitted')
            ->where('admissions.attending_physician_id', $physician->id);

        // Search Logic 
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('admission_number', 'like', "%{$search}%")
                    ->orWhereHas('patient', function ($subQ) use ($search) {
                        $subQ->where('last_name', 'like', "{$search}%")
                            ->orWhere('first_name', 'like', "{$search}%");
                    });
            });
        }

        // Pagination & Ordering
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

    // patient chart
    public function show($id)
    {
        $physician = Auth::user()->physician;

        $admission = Admission::with([
            'patient',
            'bed.room.station',
            'treatmentPlan',
            'medicalOrders' => function ($q) {
                $q->latest();
            }
        ])
            ->where('id', $id)
            ->where('attending_physician_id', $physician->id)
            ->firstOrFail();

        $latestLog = $admission->clinicalLogs()->latest()->first();
        
        $clinicalLogs = $admission->clinicalLogs()
            ->with('user')
            ->latest()
            ->paginate(15);

        $vitals = null;
        if ($latestLog && isset($latestLog->data['bp_systolic'])) {
            $vitals = $latestLog->data;
        } else {
            $vitals = [
                'bp_systolic' => $admission->bp_systolic,
                'bp_diastolic' => $admission->bp_diastolic,
                'temp' => $admission->temp,
                'hr' => $admission->pulse_rate,
                'o2' => $admission->o2_sat,
                'recorded_at' => $admission->admission_date,
            ];
        }

        $medicines = Medicine::where('stock_on_hand', '>', 0)->get();

        return view('physician.patients.show', compact('admission', 'latestLog', 'medicines', 'vitals', 'clinicalLogs'));
    }
}
