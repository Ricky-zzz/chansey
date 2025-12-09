<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Physician;
use App\Models\Bed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admission;
use App\Models\AdmissionBillingInfo;
use App\Models\PatientFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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

        $beds = Bed::where('status', 'Available')->get();

        return view('nurse.admitting.patients.create', compact('physicians', 'beds'))->with('success', "YAHALOOOOOOOOOOO");
    }

    public function store(Request $request)
    {
        // We validate everything at once to prevent partial saves
        $validated = $request->validate([
            // A. Patient Demographics
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'date_of_birth' => 'required|date',
            'sex' => 'required|in:Male,Female',
            'civil_status' => 'required',
            'nationality' => 'required|string',
            'religion' => 'nullable|string',
            'address_permanent' => 'required|string',
            'address_present' => 'nullable|string',
            'contact_number' => 'required|string',
            'email' => 'nullable|email',

            // B. Emergency Contact
            'emergency_contact_name' => 'required|string',
            'emergency_contact_relationship' => 'required|string',
            'emergency_contact_number' => 'required|string',

            // C. IDs
            'philhealth_number' => 'nullable|string',
            'senior_citizen_id' => 'nullable|string',

            // D. Admission Details
            'admission_type' => 'required',
            'bed_id' => 'exists:beds,id,status,Available',
            'attending_physician_id' => 'required|exists:physicians,id',
            'case_type' => 'required',
            'mode_of_arrival' => 'required',
            'chief_complaint' => 'required|string',
            'initial_diagnosis' => 'nullable|string',

            // E. Vitals 
            'temp' => 'nullable|numeric',
            'bp_systolic' => 'nullable|integer',
            'bp_diastolic' => 'nullable|integer',
            'pulse_rate' => 'nullable|integer',
            'respiratory_rate' => 'nullable|integer',
            'o2_sat' => 'nullable|integer',
            'known_allergies' => 'nullable|array',

            // G. Financials
            'payment_type' => 'required',
            'primary_insurance_provider' => 'nullable|string',
            'policy_number' => 'nullable|string',
            'approval_code' => 'nullable|string',
            'guarantor_name' => 'nullable|string',
            'guarantor_relationship' => 'nullable|string',
            'guarantor_contact' => 'nullable|string',

            // H. Files (Validate format/size)
            'doc_valid_id' => 'nullable|file|mimes:jpg,png,pdf|max:5120',
            'doc_loa' => 'nullable|file|mimes:jpg,png,pdf|max:5120',
            'doc_consent' => 'nullable|file|mimes:jpg,png,pdf|max:5120',
            'doc_privacy' => 'nullable|file|mimes:jpg,png,pdf|max:5120',
            'doc_mdr' => 'nullable|file|mimes:jpg,png,pdf|max:5120',
        ]);

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
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'middle_name' => $request->middle_name,
                'date_of_birth' => $request->date_of_birth,
                'sex' => $request->sex,
                'civil_status' => $request->civil_status,
                'nationality' => $request->nationality,
                'religion' => $request->religion,
                'address_permanent' => $request->address_permanent,
                'address_present' => $request->address_present,
                'contact_number' => $request->contact_number,
                'email' => $request->email,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_relationship' => $request->emergency_contact_relationship,
                'emergency_contact_number' => $request->emergency_contact_number,
                'philhealth_number' => $request->philhealth_number,
                'senior_citizen_id' => $request->senior_citizen_id,
            ]);

            // --- 3. CREATE ADMISSION ---
            $dateStr = date('Ymd');
            $admCount = Admission::whereDate('created_at', today())->count() + 1;
            $admNumber = "ADM-{$dateStr}-" . str_pad($admCount, 3, '0', STR_PAD_LEFT);

            $admission = Admission::create([
                'patient_id' => $patient->id,
                'admission_number' => $admNumber,
                'admission_date' => now(),
                'status' => 'Admitted',
                'admitting_clerk_id' => Auth::id(),
                'attending_physician_id' => $request->attending_physician_id,

                // Details
                'admission_type' => $request->admission_type,
                'bed_id' => $request->bed_id,
                'case_type' => $request->case_type,
                'mode_of_arrival' => $request->mode_of_arrival,
                'chief_complaint' => $request->chief_complaint,
                'initial_diagnosis' => $request->initial_diagnosis,

                // Vitals
                'temp' => $request->temp,
                'bp_systolic' => $request->bp_systolic,
                'bp_diastolic' => $request->bp_diastolic,
                'pulse_rate' => $request->pulse_rate,
                'respiratory_rate' => $request->respiratory_rate,
                'o2_sat' => $request->o2_sat,

                'known_allergies' => $request->known_allergies ?? [],
            ]);

            $bed = Bed::findOrFail($request->bed_id);
            $bed->update([
                'status' => 'Occupied'
            ]);

            // --- 4. CREATE BILLING INFO ---
            AdmissionBillingInfo::create([
                'admission_id' => $admission->id,
                'payment_type' => $request->payment_type,
                'primary_insurance_provider' => $request->primary_insurance_provider,
                'policy_number' => $request->policy_number,
                'approval_code' => $request->approval_code,
                'guarantor_name' => $request->guarantor_name,
                'guarantor_relationship' => $request->guarantor_relationship,
                'guarantor_contact' => $request->guarantor_contact,
            ]);

            $fileMap = [
                'doc_valid_id' => 'Valid ID',
                'doc_loa' => 'Insurance LOA',
                'doc_consent' => 'General Consent',
                'doc_privacy' => 'Privacy Notice',
                'doc_mdr' => 'PhilHealth MDR',
            ];

            foreach ($fileMap as $inputName => $docType) {
                if ($request->hasFile($inputName)) {
                    $file = $request->file($inputName);
                    $path = $file->storeAs(
                        "patient_records/{$patient->id}/{$admission->id}",
                        $file->getClientOriginalName()
                    );

                    PatientFile::create([
                        'patient_id' => $patient->id,
                        'admission_id' => $admission->id,
                        'uploaded_by_id' => Auth::id(),
                        'document_type' => $docType,
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('nurse.admitting.patients.create')
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
        $patient = Patient::with(['admissions' => function ($query) {
            $query->latest('admission_date')->with('attendingPhysician');
        }])->findOrFail($id);

        return view('nurse.admitting.patients.show', compact('patient'));
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
