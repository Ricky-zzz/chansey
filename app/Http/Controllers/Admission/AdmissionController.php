<?php

namespace App\Http\Controllers\Admission;

use App\Http\Requests\AdmissionRequest;
use App\Models\Admission;
use App\Models\PatientFile;
use App\Models\Physician;
use App\Models\Bed;
use App\Models\Patient;
use App\Models\Station;
use App\Models\PatientMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\AdmissionBillingInfo;
use App\Http\Controllers\Controller;
use App\Services\AdmissionNumberGenerator;
use App\Services\PatientFileService;
use App\Services\PatientMovementService;


class AdmissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Admission::with(['patient', 'bed.room', 'attendingPhysician'])
        ->whereIn('admissions.status', ['Admitted', 'Ready for Discharge']);

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

        $physicians = Physician::with('department')->select('id', 'first_name', 'last_name', 'department_id')->get();
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

            // Generate unique admission number using the service
            $admissionNumberGenerator = app(AdmissionNumberGenerator::class);
            $admNumber = $admissionNumberGenerator->generate();

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
                'bed_id' => $data['admission_type'] === 'Outpatient' ? null : ($data['bed_id'] ?? null),
                'case_type' => $data['case_type'],
                'mode_of_arrival' => $data['mode_of_arrival'],
                'chief_complaint' => $data['chief_complaint'],
                'initial_diagnosis' => $data['initial_diagnosis'] ?? null,

                // Vitals - stored as JSON
                'initial_vitals' => [
                    'bp' => $data['bp'] ?? null,
                    'temp' => $data['temp'] ?? null,
                    'hr' => $data['hr'] ?? null,
                    'pr' => $data['pr'] ?? null,
                    'o2' => $data['o2'] ?? null,
                    'height' => $data['height'] ?? null,
                    'weight' => $data['weight'] ?? null,
                ],

                'known_allergies' => $data['known_allergies'] ?? [],
            ]);

            if ($admission->bed_id) {
                $bed = Bed::with('room')->findOrFail($admission->bed_id);
                $bed->update(['status' => 'Occupied']);

                $movementService = app(PatientMovementService::class);
                $movementService->createInitialMovement($admission, $bed);
            }

            AdmissionBillingInfo::create([
                'admission_id' => $admission->id,
                'payment_type' => 'Cash',
            ]);

            DB::commit();

            // Handle file uploads after transaction commits using the service
            $patientFileService = app(PatientFileService::class);
            $patientFileService->uploadFromRequest($request, $patient_id, $admission->id);

            return redirect()->route('nurse.admitting.admissions.show', $admission->id)
                ->with('success', 'Admission created successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Admission Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error creating admission: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $physicians = Physician::with('department')->select('id', 'first_name', 'last_name', 'department_id')->get();
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
                'initial_vitals' => [
                    'bp' => $data['bp'] ?? null,
                    'temp' => $data['temp'] ?? null,
                    'hr' => $data['hr'] ?? null,
                    'pr' => $data['pr'] ?? null,
                    'o2' => $data['o2'] ?? null,
                    'height' => $data['height'] ?? null,
                    'weight' => $data['weight'] ?? null,
                ],
                'known_allergies' => $data['known_allergies'] ?? [],
            ]);


            $patientFileService = app(PatientFileService::class);
            $patientFileService->updateFromRequest($request, $admission->patient_id, $admission->id);

            DB::commit();
            return redirect()->route('nurse.admitting.admissions.show', $admission->id)
                ->with('success', 'Admission updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors(['error' => 'Error updating admission: ' . $e->getMessage()]);
        }
    }

    public function clearanceList(Request $request)
    {
        $query = Admission::with(['patient', 'bed.room', 'attendingPhysician'])
            ->whereIn('status', ['Cleared', 'Discharged'])
            ->orderByRaw("CASE WHEN status = 'Cleared' THEN 0 WHEN status = 'Discharged' THEN 1 END")
            ->latest('updated_at');

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

        $admissions = $query->paginate(10)->appends(['search' => $search]);

        return view('nurse.admitting.admissions.clearance', compact('admissions'));
    }

    public function discharge($id, PatientMovementService $movementService)
    {
        try {
            DB::beginTransaction();

            $admission = Admission::with('bed')->findOrFail($id);

            if ($admission->status !== 'Cleared') {
                abort(403, 'Patient has not settled their bill.');
            }

            if ($admission->bed) {
                $admission->bed->update(['status' => 'Cleaning']);
            }

            $movementService->endAllMovements($admission);

            $admission->update([
                'status' => 'Discharged',
                'discharge_date' => now()
            ]);

            $admission->medicalOrders()
                ->whereIn('status', ['Pending', 'Active', 'In Progress'])
                ->update(['status' => 'Discontinued']);

            DB::commit();
            return back()->with('success', 'Patient Discharged. Bed marked for cleaning.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Discharge Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Error during discharge: ' . $e->getMessage()]);
        }
    }
}
