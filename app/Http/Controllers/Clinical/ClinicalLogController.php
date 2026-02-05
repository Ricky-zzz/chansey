<?php

namespace App\Http\Controllers\Clinical;

use App\Http\Controllers\Controller;
use App\Models\ClinicalLog;
use App\Models\MedicalOrder;
use App\Models\Medicine;
use App\Models\BillableItem;
use App\Services\BillableItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClinicalLogController extends Controller
{
    protected $billableItemService;

    public function __construct(BillableItemService $billableItemService)
    {
        $this->billableItemService = $billableItemService;
    }
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
                'bp' => $request->bp,
                'temp' => $request->temp,
                'hr' => $request->hr,
                'pr' => $request->pr,
                'rr' => $request->rr,
                'o2' => $request->o2,
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
                    // Validate medicine is dispensed by pharmacy first
                    if (!$order->dispensed) {
                        throw new \Exception("Medicine not yet dispensed by pharmacy! Contact pharmacy.");
                    }

                    // Stock already decremented at dispense time - just bill the item
                    $this->billableItemService->create(
                        $order->admission_id,
                        $order->medicine->brand_name,
                        $order->medicine->price,
                        $order->quantity,
                        'medical'
                    );

                    // Reset dispensed for recurring meds (nurse administered = ready for next dose)
                    if ($order->frequency !== 'Once') {
                        $order->update(['dispensed' => false]);
                    }
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
