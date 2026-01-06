<?php

namespace App\Http\Controllers\Clinical;

use App\Http\Controllers\Controller;
use App\Models\MedicalOrder;
use App\Models\PatientFile;
use App\Models\ClinicalLog;
use App\Models\BillableItem;
use App\Models\TransferRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderExecutionController extends Controller
{
    public function uploadLabResult(Request $request)
    {
        $request->validate([
            'medical_order_id' => 'required|exists:medical_orders,id',
            'findings' => 'required|string',
            'result_file' => 'required|file|mimes:pdf,jpg,png,jpeg|max:10240',
        ]);

        $order = MedicalOrder::with('admission')->findOrFail($request->medical_order_id);

        try {
            DB::beginTransaction();

            $file = $request->file('result_file');
            $filename = 'Lab_' . $order->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Store in private storage (not publicly accessible)
            $path = $file->storeAs(
                "patient_records/{$order->admission->patient_id}/{$order->admission_id}",
                $filename,
                'private'
            );

            PatientFile::create([
                'patient_id' => $order->admission->patient_id,
                'admission_id' => $order->admission->id,
                'medical_order_id' => $order->id,
                'document_type' => 'Lab Result',
                'file_name' => $filename,
                'file_path' => $path,
                'description' => $request->findings,
                'uploaded_by_id' => Auth::id(),
            ]);

            $order->update([
                'status' => 'Done',
                'fulfilled_by_user_id' => Auth::id(),
                'fulfilled_at' => now()
            ]);

            ClinicalLog::create([
                'admission_id' => $order->admission_id,
                'user_id' => Auth::id(),
                'medical_order_id' => $order->id,
                'type' => 'Laboratory',
                'data' => [
                    'note' => "Lab Result Uploaded: {$order->instruction}",
                    'finding' => $request->findings
                ]
            ]);


            BillableItem::create([
                'admission_id' => $order->admission_id,
                'name' => $order->instruction,
                'amount' => 500.00,
                'quantity' => 1,
                'total' => 500.00,
                'status' => 'Unpaid'
            ]);


            DB::commit();
            return back()->with('success', 'Lab result uploaded and order completed.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function requestTransfer(Request $request)
    {
        $request->validate([
            'admission_id' => 'required|exists:admissions,id',
            'medical_order_id' => 'required|exists:medical_orders,id',
            'target_bed_id' => 'required|exists:beds,id',
            'target_station_id' => 'required|exists:stations,id',
        ]);

        try {
            DB::beginTransaction();

            TransferRequest::create([
                'admission_id' => $request->admission_id,
                'medical_order_id' => $request->medical_order_id,
                'requested_by_user_id' => Auth::id(),
                'target_station_id' => $request->target_station_id,
                'target_bed_id' => $request->target_bed_id,
                'remarks' => $request->remarks,
                'status' => 'Pending'
            ]);

            MedicalOrder::where('id', $request->medical_order_id)
                ->update(['status' => 'Active']);

            DB::commit();
            return back()->with('success', 'Transfer request sent to Admissions Office.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
