<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdmissionRequest;
use App\Models\Admission;
use App\Models\PatientFile;
use App\Models\Physician;
use App\Models\Bed;
use App\Models\Patient;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\AdmissionBillingInfo;


class AdmissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Admission::with(['patient', 'bed.room', 'attendingPhysician']);
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('admission_number', 'like', "%{$search}%")
                    ->orWhereHas('patient', function ($subQ) use ($search) {
                        $subQ->where('last_name', 'like', "{$search}%")
                            ->orWhere('first_name', 'like', "{$search}%")
                            ->orWhere('patient_unique_id', 'like', "%{$search}%");
                    });
            });
        }

        $admissions = $query->latest('admission_date')
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('nurse.admitting.admissions.index', compact('admissions'));
    }


    public function show($id)
    {
        $admission = Admission::with([
            'patient',
            'attendingPhysician',
            'admittingClerk',
            'bed.room.station',
            'billingInfo',
            'files.uploader'
        ])->findOrFail($id);

        return view('nurse.admitting.admissions.show', compact('admission'));
    }

    public function create($patient_id)
    {
        $patient = Patient::findOrFail($patient_id);

        $activeAdmission = $patient->admissions()->where('status', 'Admitted')->first();
        if ($activeAdmission) {
            return redirect()->route('nurse.admitting.patients.show', $patient->id)
                ->with('error', 'Patient is currently admitted! Cannot create new admission.');
        }

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

        return view('nurse.admitting.admissions.create', compact('patient', 'physicians', 'stations', 'rawBeds'));
    }

    public function store(AdmissionRequest $request, $patient_id)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $dateStr = date('Ymd');
            $admCount = Admission::whereDate('created_at', today())->count() + 1;
            $admNumber = "ADM-{$dateStr}-" . str_pad($admCount, 3, '0', STR_PAD_LEFT);

            $admission = Admission::create([
                'patient_id' => $patient_id,
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
                'initial_diagnosis' => $data['initial_diagnosis'] ?? null,

                // Vitals
                'temp' => $data['temp'] ?? null,
                'bp_systolic' => $data['bp_systolic'] ?? null,
                'bp_diastolic' => $data['bp_diastolic'] ?? null,
                'pulse_rate' => $data['pulse_rate'] ?? null,
                'respiratory_rate' => $data['respiratory_rate'] ?? null,
                'o2_sat' => $data['o2_sat'] ?? null,

                'known_allergies' => $data['known_allergies'] ?? [],
            ]);

            $bed = Bed::findOrFail($data['bed_id']);
            $bed->update(['status' => 'Occupied']);

            
            AdmissionBillingInfo::create([
                'admission_id' => $admission->id,
                'payment_type' => $data['payment_type'] ?? null,
                'primary_insurance_provider' => $data['primary_insurance_provider'] ?? null,
                'policy_number' => $data['policy_number'] ?? null,
                'approval_code' => $data['approval_code'] ?? null,
                'guarantor_name' => $data['guarantor_name'] ?? null,
                'guarantor_relationship' => $data['guarantor_relationship'] ?? null,
                'guarantor_contact' => $data['guarantor_contact'] ?? null,
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
                        "patient_records/{$patient_id}/{$admission->id}",
                        $file->getClientOriginalName()
                    );

                    PatientFile::create([
                        'patient_id' => $patient_id,
                        'admission_id' => $admission->id,
                        'uploaded_by_id' => Auth::id(),
                        'document_type' => $docType,
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('nurse.admitting.admissions.show', $admission->id)
                ->with('success', 'Admission created successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors(['error' => 'Error creating admission: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $physicians = Physician::select('id', 'first_name', 'last_name', 'specialization')->get();
        $beds = Bed::where('status', 'Available')->get();
        $admission = Admission::with([
            'patient',
            'attendingPhysician',
            'admittingClerk',
            'bed.room.station',
            'billingInfo',
            'files.uploader'
        ])->findOrFail($id);
        return view('nurse.admitting.admissions.edit', compact('admission', 'physicians', 'beds'));
    }

    public function update(AdmissionRequest $request, $id)
    {
        $admission = Admission::with('billingInfo')->findOrFail($id);

        $data = $request->validated();

        try {
            DB::beginTransaction();

            $admission->update([
                'admission_type' => $data['admission_type'],
                'attending_physician_id' => $data['attending_physician_id'],
                'case_type' => $data['case_type'],
                'mode_of_arrival' => $data['mode_of_arrival'],
                'chief_complaint' => $data['chief_complaint'] ?? null,
                'initial_diagnosis' => $data['initial_diagnosis'] ?? null,
                'temp' => $data['temp'] ?? null,
                'bp_systolic' => $data['bp_systolic'] ?? null,
                'bp_diastolic' => $data['bp_diastolic'] ?? null,
                'pulse_rate' => $data['pulse_rate'] ?? null,
                'respiratory_rate' => $data['respiratory_rate'] ?? null,
                'o2_sat' => $data['o2_sat'] ?? null,
                'known_allergies' => $data['known_allergies'] ?? [],
            ]);

            if ($admission->billingInfo) {
                $admission->billingInfo->update([
                    'payment_type' => $data['payment_type'],
                    'primary_insurance_provider' => $data['primary_insurance_provider'] ?? null,
                    'policy_number' => $data['policy_number'] ?? null,
                    'approval_code' => $data['approval_code'] ?? null,
                    'guarantor_name' => $data['guarantor_name'] ?? null,
                    'guarantor_relationship' => $data['guarantor_relationship'] ?? null,
                    'guarantor_contact' => $data['guarantor_contact'] ?? null,
                ]);
            }

            $fileMap = [
                'doc_valid_id' => 'Valid ID',
                'doc_loa' => 'Insurance LOA',
                'doc_consent' => 'General Consent',
                'doc_privacy' => 'Privacy Notice',
                'doc_mdr' => 'PhilHealth MDR',
            ];

            foreach ($fileMap as $inputName => $docType) {
                if ($request->hasFile($inputName)) {
                    $oldFile = PatientFile::where('admission_id', $admission->id)
                        ->where('document_type', $docType)
                        ->first();

                    if ($oldFile && Storage::exists($oldFile->file_path)) {
                        Storage::delete($oldFile->file_path);
                    }

                    $file = $request->file($inputName);
                    $storagePath = 'private/patient_records/' . $admission->patient_id . '/' . $admission->id;
                    $path = $file->storeAs($storagePath, $file->getClientOriginalName(), 'private');

                    if ($oldFile) {
                        $oldFile->update([
                            'file_name' => $file->getClientOriginalName(),
                            'file_path' => $storagePath . '/' . $file->getClientOriginalName(),
                            'document_type' => $docType,
                            'uploaded_by_id' => Auth::id(),
                        ]);
                    } else {
                        PatientFile::create([
                            'admission_id' => $admission->id,
                            'patient_id' => $admission->patient_id,
                            'file_name' => $file->getClientOriginalName(),
                            'file_path' => $storagePath . '/' . $file->getClientOriginalName(),
                            'document_type' => $docType,
                            'uploaded_by_id' => Auth::id(),
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('nurse.admitting.admissions.show', $admission->id)
                ->with('success', 'Admission updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors(['error' => 'Error updating admission: ' . $e->getMessage()]);
        }
    }
}
