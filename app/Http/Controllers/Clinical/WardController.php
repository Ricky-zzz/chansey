<?php

namespace App\Http\Controllers\Clinical;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WardController extends Controller
{
    public function index(Request $request)
    {
        $nurse = Auth::user()->nurse;

        if (!$nurse->station_id) {
            abort(403, 'You are not assigned to a station.');
        }

        $query = Admission::query()
            ->with(['patient', 'bed.room', 'attendingPhysician'])
            ->where('admissions.status', 'Admitted')
            ->whereHas('bed.room', function ($q) use ($nurse) {
                $q->where('station_id', $nurse->station_id);
            });

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($p) use ($search) {
                    $p->where('last_name', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%");
                })
                    ->orWhereHas('bed', function ($b) use ($search) {
                        $b->where('bed_code', 'like', "%{$search}%");
                    });
            });
        }

        $patients = $query
            ->join('beds', 'admissions.bed_id', '=', 'beds.id')
            ->orderBy('beds.bed_code', 'asc')
            ->select('admissions.*')
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('nurse.clinical.ward.index', compact('patients', 'nurse'));
    }

    // Patient chart View
    public function show($id)
    {
        $admission = Admission::with([
            'patient',
            'bed.room.station',
            'nursingCarePlans',
            'medicalOrders.medicine', 
            'medicalOrders.latestLog',
        ])->findOrFail($id);

        $activeOrders = $admission->medicalOrders
            ->whereIn('status', ['Pending', 'Active'])
            ->sortByDesc(function ($order) {
                return $order->frequency === 'Once' ? 1 : 0;
            });

        $latestLog = $admission->clinicalLogs()->latest()->first();

        // Paginate clinical logs (15 per page, latest first)
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

        return view('nurse.clinical.ward.show', compact('admission', 'activeOrders', 'latestLog', 'vitals', 'clinicalLogs'));
    }
}
