<?php

namespace App\Http\Controllers\Admission;

use App\Models\Station;
use App\Models\Patient;
use App\Models\Physician;
use App\Models\Bed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admission;
use App\Models\AdmissionBillingInfo;
use App\Models\PatientFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreAdmissionRequest;
use App\Http\Controllers\Controller;
use App\Services\AdmissionNumberGenerator;
use App\Services\PatientFileService;
use App\Services\PatientMovementService;


class PatientController extends Controller
{

    public function index(Request $request)
    {
        // 1. Start the Query
        $query = Patient::query();
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('patient_unique_id', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "{$search}%")
                    ->orWhere('first_name', 'like', "{$search}%");
            });
        }

        $patients = $query->latest()
            ->paginate(15)
            ->appends(['search' => $search]);

        return view('nurse.admitting.patients.index', compact('patients'));
    }

    public function create()
    {
        $physicians = Physician::select('id', 'first_name', 'last_name', 'specialization')->get();
        $stations = Station::select('id', 'station_name')->get();

        $rawBeds = Bed::with('room.station')
            ->where('status', 'Available')
            ->get()
            ->map(function ($bed) {
                return [
                    'id' => $bed->id,
                    'bed_code' => $bed->bed_code,
                    'station_id' => $bed->room->station_id, 
                    'room_number' => $bed->room->room_number
                ];
            });

        return view('nurse.admitting.patients.create', compact('physicians', 'stations', 'rawBeds'));
    }

    public function store(StoreAdmissionRequest $request)
    {

        $data = $request->validated();

        try {
            DB::beginTransaction();

            // --- 1. GENERATE P-ID ---
            $year = date('Y');
            $lastPatient = Patient::where('patient_unique_id', 'like', "P-{$year}-%")->latest('id')->first();
            $nextNum = $lastPatient ? intval(explode('-', $lastPatient->patient_unique_id)[2]) + 1 : 1;
            $pid = "P-{$year}-" . str_pad($nextNum, 5, '0', STR_PAD_LEFT);

            // --- 2. CREATE PATIENT ---
            $patient = Patient::create([
                'patient_unique_id' => $pid,
                'created_by_user_id' => Auth::id(),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'middle_name' => $data['middle_name'],
                'date_of_birth' => $data['date_of_birth'],
                'sex' => $data['sex'],
                'civil_status' => $data['civil_status'],
                'nationality' => $data['nationality'],
                'religion' => $data['religion'],
                'address_permanent' => $data['address_permanent'],
                'address_present' => $data['address_present'],
                'contact_number' => $data['contact_number'],
                'email' => $data['email'],
                'emergency_contact_name' => $data['emergency_contact_name'],
                'emergency_contact_relationship' => $data['emergency_contact_relationship'],
                'emergency_contact_number' => $data['emergency_contact_number'],
                'philhealth_number' => $data['philhealth_number'],
                'senior_citizen_id' => $data['senior_citizen_id'],
            ]);

            // --- 3. CREATE ADMISSION ---
            $admissionNumberGenerator = app(AdmissionNumberGenerator::class);
            $admNumber = $admissionNumberGenerator->generate();

            $admission = Admission::create([
                'patient_id' => $patient->id,
                'admission_number' => $admNumber,
                'admission_date' => now(),
                'status' => 'Admitted',
                'admitting_clerk_id' => Auth::id(),
                'attending_physician_id' => $data['attending_physician_id'],

                // Details
                'admission_type' => $data['admission_type'],
                'station_id' => $data['station_id'],
                'bed_id' => $data['bed_id'],
                'case_type' => $data['case_type'],
                'mode_of_arrival' => $data['mode_of_arrival'],
                'chief_complaint' => $data['chief_complaint'],
                'initial_diagnosis' => $data['initial_diagnosis'],

                // Vitals
                'temp' => $data['temp'],
                'bp_systolic' => $data['bp_systolic'],
                'bp_diastolic' => $data['bp_diastolic'],
                'pulse_rate' => $data['pulse_rate'],
                'respiratory_rate' => $data['respiratory_rate'],
                'o2_sat' => $data['o2_sat'],

                // Arrays are handled automatically if validation passed
                'known_allergies' => $data['known_allergies'] ?? [],
            ]);

            $bed = Bed::findOrFail($data['bed_id']);
            $bed->update(['status' => 'Occupied']);

            // Create initial patient movement using the service
            $bed->load('room');
            $movementService = app(PatientMovementService::class);
            $movementService->createInitialMovement($admission, $bed);

            // --- 4. CREATE BILLING INFO ---
            AdmissionBillingInfo::create([
                'admission_id' => $admission->id,
                'payment_type' => $data['payment_type'] ?? null,
                'primary_insurance_provider' => $data['primary_insurance_provider'],
                'policy_number' => $data['policy_number'],
                'approval_code' => $data['approval_code'],
                'guarantor_name' => $data['guarantor_name'],
                'guarantor_relationship' => $data['guarantor_relationship'],
                'guarantor_contact' => $data['guarantor_contact'],
            ]);

            // Handle file uploads using the service
            $patientFileService = app(PatientFileService::class);
            $patientFileService->uploadFromRequest($request, $patient->id, $admission->id);

            DB::commit();

            return redirect()->route('nurse.admitting.patients.show', $patient->id)
                ->with('success', "Patient Successfully Admitted! (PID: {$pid})");
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Admission Error: ' . $e->getMessage());

            return back()
                ->withErrors(['error' => 'System Error: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function show($id)
    {
        $patient = Patient::findOrFail($id);
        
        $admissions = $patient->admissions()
            ->latest('admission_date')
            ->with('attendingPhysician')
            ->paginate(5);

        return view('nurse.admitting.patients.show', compact('patient', 'admissions'));
    }

    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        return view('nurse.admitting.patients.edit', compact('patient'));
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'date_of_birth' => 'required|date',
            'sex' => 'required|in:Male,Female',
            'civil_status' => 'required',
            'nationality' => 'required',
            'religion' => 'nullable',
            'address_permanent' => 'required',
            'address_present' => 'nullable',
            'contact_number' => 'required',
            'email' => 'nullable|email',
            'emergency_contact_name' => 'required',
            'emergency_contact_relationship' => 'required',
            'emergency_contact_number' => 'required',
            'philhealth_number' => 'nullable',
            'senior_citizen_id' => 'nullable',
        ]);

        $patient->update($validated);

        return redirect()->route('nurse.admitting.patients.show', $patient->id)
            ->with('success', 'Patient profile updated successfully.');
    }
}
