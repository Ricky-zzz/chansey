<?php

namespace App\Http\Controllers\Clinical;

use App\Http\Controllers\Controller;
use App\Models\ClinicalLog;
use App\Models\MedicalOrder;
use App\Models\Medicine;
use App\Models\BillableItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClinicalLogController extends Controller
{
    public function store(Request $request, $admission)
    {
        $user = Auth::user();
        $order = null;
        $type = $request->type;

        if ($request->medical_order_id) {
            $order = MedicalOrder::findOrFail($request->medical_order_id);
            $type = match ($order->type) {
                'Medication' => 'Medication',
                'Monitoring' => 'Vitals',
                'Dietary'    => 'Dietary',
                default      => 'Note'
            };
        }

        try {
            DB::beginTransaction();

            $vitalsData = [
                'bp_systolic' => $request->bp_systolic,
                'bp_diastolic' => $request->bp_diastolic,
                'temp' => $request->temp,
                'hr' => $request->heart_rate,
                'rr' => $request->respiratory_rate,
                'o2' => $request->o2_sat,
            ];

            $logData = array_filter($vitalsData, fn($value) => !is_null($value) && $value !== '');

            if ($request->observation) $logData['observation'] = $request->observation;

            if ($type === 'Medication' && $order) {
                $logData['medicine'] = $order->medicine->brand_name ?? 'Unknown';
                $logData['dosage'] = $order->quantity;
                $logData['remarks'] = $request->note; 
            }

            ClinicalLog::create([
                'admission_id' => $admission,
                'user_id' => $user->id,
                'medical_order_id' => $order?->id,
                'type' => $type,
                'data' => $logData,
            ]);

            if ($order) {
                if ($order->type === 'Medication') {
                    if ($order->medicine->stock_on_hand < $order->quantity) {
                        throw new \Exception("Not enough stock for {$order->medicine->name}!");
                    }

                    $order->medicine->decrement('stock_on_hand', $order->quantity);

                    // Add to Bill
                    BillableItem::create([
                        'admission_id' => $order->admission_id,
                        'name' => $order->medicine->name . ' (' . $order->quantity . ')',
                        'amount' => $order->medicine->price,
                        'quantity' => $order->quantity,
                        'total' => $order->medicine->price * $order->quantity,
                        'status' => 'Unpaid'
                    ]);
                }

                if ($order->frequency === 'Once' || $order->type === 'Utility') {
                    $order->update(['status' => 'Done']);
                }
                else {
                    if ($order->status === 'Pending') {
                        $order->update(['status' => 'Active']);
                    }
                }
            }

            DB::commit();
            return back()->with('success', 'Log saved successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
