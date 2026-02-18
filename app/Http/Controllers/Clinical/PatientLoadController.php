<?php

namespace App\Http\Controllers\Clinical;

use App\Http\Controllers\Controller;
use App\Models\PatientLoad;
use App\Models\Admission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientLoadController extends Controller
{
    /**
     * Display a listing of assigned patients for the current nurse.
     */
    public function index(Request $request)
    {
        $nurse = Auth::user()->nurse;

        $patientLoads = PatientLoad::with(['patient', 'nurse'])
            ->where('nurse_id', $nurse->id)
            ->get()
            ->map(function ($load) {
                // Get the active admission for this patient in the nurse's station
                $admission = Admission::where('patient_id', $load->patient_id)
                    ->where('station_id', $load->nurse->station_id)
                    ->whereIn('status', ['Admitted', 'Ready for Discharge'])
                    ->first();

                return [
                    'id' => $load->id,
                    'patient_id' => $load->patient_id,
                    'admission_id' => $admission?->id,
                    'patient_name' => $load->patient->last_name . ', ' . $load->patient->first_name,
                    'patient_unique_id' => $load->patient->patient_unique_id,
                    'acuity' => $load->acuity->value,
                    'score' => $load->score,
                    'description' => $load->description,
                    'sex' => $load->patient->sex,
                    'age' => $load->patient->getAgeAttribute(),
                ];
            });

        $patientCount = $patientLoads->count();
        $title = "My Patient Load ({$patientCount})";

        return view('nurse.clinical.load.index', compact('patientLoads', 'title', 'nurse'));
    }

    /**
     * Get details of a specific assignment (via AJAX).
     */
    public function getAssignmentDetails(PatientLoad $patientLoad)
    {
        // Verify the nurse owns this assignment
        if ($patientLoad->nurse_id !== Auth::user()->nurse->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'assignment' => [
                'id' => $patientLoad->id,
                'patient_name' => $patientLoad->patient->last_name . ', ' . $patientLoad->patient->first_name,
                'patient_unique_id' => $patientLoad->patient->patient_unique_id,
                'acuity' => $patientLoad->acuity->value,
                'score' => $patientLoad->score,
                'description' => $patientLoad->description,
            ]
        ]);
    }
}
