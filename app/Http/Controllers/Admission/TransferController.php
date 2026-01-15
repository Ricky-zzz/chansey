<?php

namespace App\Http\Controllers\Admission;

use App\Http\Controllers\Controller;
use App\Models\TransferRequest;
use App\Models\ClinicalLog;
use App\Services\PatientMovementService; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransferController extends Controller
{
    protected $movementService;

    public function __construct(PatientMovementService $movementService)
    {
        $this->movementService = $movementService;
    }

    public function index()
    {
        $requests = TransferRequest::with([
                'admission.patient',
                'admission.bed.room.station',
                'targetBed.room.station',     
                'requestor'                   
            ])
            ->where('status', 'Pending')
            ->latest()
            ->paginate(10);

        return view('nurse.admitting.transfers.index', compact('requests'));
    }

    public function approve($id)
    {
        $user = Auth::user();

        $request = TransferRequest::with('admission', 'targetBed')->findOrFail($id);

        if ($request->targetBed->status !== 'Available') {
            return back()->with('error', 'Target bed is no longer available! Please decline and ask for a new request.');
        }

        try {
            DB::beginTransaction();

            $this->movementService->transferPatient($request->admission, $request->targetBed, 'Transfer Request #' . $request->id);

            $request->update([
                'status' => 'Approved',
            ]);

            $request->medicalOrder->update(['status' => 'Done']);

            $logdata['from_bed'] = $request->admission->bed->bed_code;
            $logdata['to_bed'] = $request->targetBed->bed_code;
            $logdata['remarks'] = $request->targetBed->remarks;

            ClinicalLog::create([
                'admission_id' => $request->admission->id,
                'user_id' => $user->id,
                'medical_order_id' => $request->medical_order_id,
                'type' => 'Transfer',
                'data' => $logdata,
            ]);

            DB::commit();
            return back()->with('success', 'Transfer approved and executed.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error processing transfer: ' . $e->getMessage());
        }
    }

    public function decline(Request $request, $id)
    {
        $transferRequest = TransferRequest::findOrFail($id);

        $transferRequest->update([
            'status' => 'Declined',
        ]);
        $transferRequest->medicalOrder->update(['status' => 'Active']);

        return back()->with('success', 'Transfer request declined.');
    }
}