<?php

namespace App\Http\Controllers\HeadNurse;

use App\Http\Controllers\Controller;
use App\Models\PatientLoad;
use App\Models\Patient;
use App\Models\Nurse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientLoadController extends Controller
{
    /**
     * Store a new patient-nurse assignment or update if duplicate.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'nurse_id' => 'required|exists:nurses,id',
            'acuity' => 'required|in:Severe,High,Moderate,Low',
            'description' => 'nullable|string',
        ]);

        // Verify head nurse has access to this station
        $nurse = Nurse::find($validated['nurse_id']);
        if ($nurse->station_id !== Auth::user()->nurse->station_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if assignment already exists
        $existing = PatientLoad::where('patient_id', $validated['patient_id'])
            ->where('nurse_id', $validated['nurse_id'])
            ->first();

        if ($existing) {
            // Update existing assignment
            $existing->update([
                'acuity' => $validated['acuity'],
                'description' => $validated['description'],
            ]);
            return response()->json(['message' => 'Assignment updated', 'patientLoad' => $existing], 200);
        }

        // Create new assignment
        $patientLoad = PatientLoad::create($validated);

        return response()->json(['message' => 'Nurse assigned to patient', 'patientLoad' => $patientLoad], 201);
    }

    /**
     * Update a patient-nurse assignment.
     */
    public function update(Request $request, PatientLoad $patientLoad)
    {
        // Verify head nurse has access
        if ($patientLoad->nurse->station_id !== Auth::user()->nurse->station_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'acuity' => 'required|in:Severe,High,Moderate,Low',
            'description' => 'nullable|string',
        ]);

        $patientLoad->update($validated);

        return response()->json(['message' => 'Assignment updated', 'patientLoad' => $patientLoad], 200);
    }

    /**
     * Delete a patient-nurse assignment.
     */
    public function destroy(PatientLoad $patientLoad)
    {
        // Verify head nurse has access
        if ($patientLoad->nurse->station_id !== Auth::user()->nurse->station_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $patientLoad->delete();

        return response()->json(['message' => 'Assignment removed'], 200);
    }

    /**
     * Get all nurses assigned to a specific patient.
     */
    public function getPatientNurses(Patient $patient)
    {
        $loads = $patient->patientLoads()
            ->with(['nurse:id,first_name,last_name,employee_id'])
            ->get()
            ->map(function ($load) {
                return [
                    'id' => $load->id,
                    'nurse_id' => $load->nurse_id,
                    'nurse_name' => $load->nurse->first_name . ' ' . $load->nurse->last_name,
                    'employee_id' => $load->nurse->employee_id,
                    'acuity' => $load->acuity->value,
                    'score' => $load->score,
                    'description' => $load->description,
                ];
            });

        return response()->json(['nurses' => $loads], 200);
    }

    /**
     * Get all patients assigned to a specific nurse with patient ratio.
     */
    public function getNursePatients(Nurse $nurse)
    {
        // Verify head nurse has access to this nurse's station
        if ($nurse->station_id !== Auth::user()->nurse->station_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $loads = $nurse->patientLoads()
            ->with(['patient:id,first_name,last_name,patient_unique_id'])
            ->get()
            ->map(function ($load) {
                return [
                    'id' => $load->id,
                    'patient_id' => $load->patient_id,
                    'patient_name' => $load->patient->first_name . ' ' . $load->patient->last_name,
                    'patient_id_code' => $load->patient->patient_unique_id,
                    'acuity' => $load->acuity->value,
                    'score' => $load->score,
                    'description' => $load->description,
                ];
            });

        // Calculate patient ratio
        $patientCount = $loads->count();
        $ratio = $patientCount > 0 ? "1:{$patientCount}" : "0:0";

        return response()->json([
            'patients' => $loads,
            'ratio' => $ratio,
            'count' => $patientCount,
        ], 200);
    }
}
