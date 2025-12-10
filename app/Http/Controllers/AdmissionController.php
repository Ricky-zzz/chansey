<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\PatientFile;
use App\Models\Physician;
use App\Models\Bed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'bed.room',
            'billingInfo',
            'files.uploader'
        ])->findOrFail($id);

        return view('nurse.admitting.admissions.show', compact('admission'));
    }

    public function edit($id)
    {
        $physicians = Physician::select('id', 'first_name', 'last_name', 'specialization')->get();
        $beds = Bed::where('status', 'Available')->get();
        $admission = Admission::with([
            'patient',
            'attendingPhysician',
            'admittingClerk',
            'bed.room',
            'billingInfo',
            'files.uploader'
        ])->findOrFail($id);
        return view('nurse.admitting.admissions.edit', compact('admission','physicians','beds'));
    }

    public function update(Request $request, $id)
    {
        $admission = Admission::with('billingInfo')->findOrFail($id);

        $validated = $request->validate([
            'admission_type' => 'required|string|in:Emergency,Outpatient,Inpatient,Transfer',
            'attending_physician_id' => 'required|exists:physicians,id',
            'case_type' => 'required|string|in:New Case,Returning,Follow-up',
            'mode_of_arrival' => 'required|string|in:Walk-in,Ambulance,Wheelchair,Stretcher',

            'chief_complaint' => 'required|string|min:5',
            'initial_diagnosis' => 'required|string|min:5',
            'temp' => 'nullable|numeric|between:35,43',
            'bp_systolic' => 'nullable|numeric|between:60,300',
            'bp_diastolic' => 'nullable|numeric|between:30,200',
            'pulse_rate' => 'nullable|numeric|between:40,200',
            'respiratory_rate' => 'nullable|numeric|between:8,40',
            'o2_sat' => 'nullable|numeric|between:50,100',
            'known_allergies' => 'nullable|array',

            'payment_type' => 'required|string|in:Cash,Insurance,HMO,Company',
            'primary_insurance_provider ' => 'nullable|string|max:255',
            'policy_number' => 'nullable|string|max:100',
            'approval_code' => 'nullable|string|max:100',
            'guarantor_name' => 'nullable|string|max:255',
            'guarantor_relationship' => 'nullable|string|max:100',
            'guarantor_contact' => 'nullable|string|max:20',

            'doc_valid_id' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_loa' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_consent' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_privacy' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_mdr' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        try {
            DB::beginTransaction();

            $admission->update([
                'admission_type' => $request->admission_type,
                'attending_physician_id' => $request->attending_physician_id,
                'case_type' => $request->case_type,
                'mode_of_arrival' => $request->mode_of_arrival,
                'chief_complaint' => $request->chief_complaint,
                'initial_diagnosis' => $request->initial_diagnosis,
                'temp' => $request->temp,
                'bp_systolic' => $request->bp_systolic,
                'bp_diastolic' => $request->bp_diastolic,
                'pulse_rate' => $request->pulse_rate,
                'respiratory_rate' => $request->respiratory_rate,
                'o2_sat' => $request->o2_sat,
                'known_allergies' => $request->known_allergies ?? [],
            ]);

            if ($admission->billingInfo) {
                $admission->billingInfo->update([
                    'payment_type' => $request->payment_type,
                    'primary_insurance_provider ' => $request->primary_insurance_provider ,
                    'policy_number' => $request->policy_number,
                    'approval_code' => $request->approval_code,
                    'guarantor_name' => $request->guarantor_name,
                    'guarantor_relationship' => $request->guarantor_relationship,
                    'guarantor_contact' => $request->guarantor_contact,
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
