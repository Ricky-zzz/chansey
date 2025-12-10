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

        // 1. Validate (Files are nullable on update!)
        $validated = $request->validate([
            'admission_type' => 'required',
            'chief_complaint' => 'required',
            // ... (other text fields)

            // Files are OPTIONAL on update
            'doc_valid_id' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            // ...
        ]);

        try {
            DB::beginTransaction();

            // 2. Update Admission & Billing
            $admission->update([
                'admission_type' => $request->admission_type,
                'chief_complaint' => $request->chief_complaint,
                // ...
            ]);

            $admission->billingInfo->update([
                'payment_type' => $request->payment_type,
                // ...
            ]);

            // 3. HANDLE FILE REPLACEMENTS
            $fileMap = [
                'doc_valid_id' => 'Valid ID',
                'doc_loa' => 'Insurance LOA',
                // ...
            ];

            foreach ($fileMap as $inputName => $docType) {
                // ONLY if the user uploaded a NEW file
                if ($request->hasFile($inputName)) {

                    // A. Find Old Record
                    $oldFile = PatientFile::where('admission_id', $admission->id)
                        ->where('document_type', $docType)
                        ->first();

                    // B. Delete Old File from Disk
                    if ($oldFile && Storage::exists($oldFile->file_path)) {
                        Storage::delete($oldFile->file_path);
                    }

                    // C. Upload New File
                    $file = $request->file($inputName);
                    $path = $file->storeAs(
                        "patient_records/{$admission->patient_id}/{$admission->id}",
                        $file->getClientOriginalName()
                    );

                    // D. Update or Create DB Record
                    if ($oldFile) {
                        $oldFile->update([
                            'file_name' => $file->getClientOriginalName(),
                            'file_path' => $path,
                            'uploaded_by_id' => Auth::id()
                        ]);
                    } else {
                        // Create if it didn't exist before
                        PatientFile::create([ /* ... */]);
                    }
                }
            }

            DB::commit();
            return back()->with('success', 'Admission details updated.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
