<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\HospitalFee;
use App\Models\BillableItem;
use App\Models\Billing;
use App\Services\PatientMovementService;
use App\Services\BillableItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    protected $movementService;
    protected $billableItemService;

    public function __construct(PatientMovementService $movementService, BillableItemService $billableItemService)
    {
        $this->movementService = $movementService;
        $this->billableItemService = $billableItemService;
    }

    public function show($id)
    {
        $admission = Admission::with(['patient', 'billableItems', 'billingInfo'])->findOrFail($id);

        $roomTotal = $this->movementService->calculateTotalRoomCharges($admission);
        $movements = $this->movementService->getMovementHistory($admission);

        $rawItems = $admission->billableItems->where('status', 'Unpaid');

        $feesList = $rawItems->where('type', 'fee');

        $clinicalList = $rawItems->whereIn('type', ['medical', 'inventory'])
            ->groupBy('name')
            ->map(function ($group) {
                return (object) [
                    'name' => $group->first()->name,
                    'quantity' => $group->sum('quantity'),
                    'amount' => $group->first()->amount,
                    'total' => $group->sum('total'),
                    'type' => $group->first()->type,
                    'id' => null,
                ];
            });

        $billingTableData = $feesList->concat($clinicalList);

        $itemsTotal = $rawItems->sum('total');

        $fees = HospitalFee::where('is_active', true)->get();

        return view('accountant.billing.show', compact(
            'admission',
            'roomTotal',
            'itemsTotal',
            'fees',
            'movements',
            'billingTableData'
        ));
    }

    public function addFee(Request $request)
    {
        $fee = HospitalFee::findOrFail($request->fee_id);

        $this->billableItemService->create(
            $request->admission_id,
            $fee->name,
            (float) $fee->price,
            $request->quantity ?? 1,
            'fee'
        );

        return back()->with('success', 'Fee added to bill.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'admission_id' => 'required|exists:admissions,id',
            'cash_tendered' => 'required|numeric|min:0',
            'pf_fee' => 'nullable|numeric|min:0',
            'philhealth' => 'nullable|numeric|min:0',
            'hmo' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $admission = Admission::with('billableItems')->findOrFail($request->admission_id);

            $roomTotal = $this->movementService->calculateTotalRoomCharges($admission);
            $itemsTotal = $admission->billableItems->where('status', 'Unpaid')->sum('total');
            $pf = $request->pf_fee ?? 0;

            $subtotal = $roomTotal + $itemsTotal + $pf;
            $deductions = ($request->philhealth ?? 0) + ($request->hmo ?? 0);
            $finalAmount = max(0, $subtotal - $deductions);

            if ($request->cash_tendered < $finalAmount) {
                throw new \Exception("Insufficient cash. Need ₱" . number_format($finalAmount, 2));
            }

            $billing = Billing::create([
                'admission_id' => $admission->id,
                'processed_by' => Auth::id(),
                'receipt_number' => 'OR-' . date('Ymd') . '-' . str_pad($admission->id, 4, '0', STR_PAD_LEFT),

                'breakdown' => [
                    'room_total' => $roomTotal,
                    'items_total' => $itemsTotal,
                    'pf_fee' => $pf,
                    'deductions' => [
                        'philhealth' => $request->philhealth,
                        'hmo' => $request->hmo
                    ],
                    'items_list' => $admission->billableItems->where('status', 'Unpaid')->toArray()
                ],

                'gross_total' => $subtotal,
                'final_total' => $finalAmount,
                'amount_paid' => $request->cash_tendered,
                'change' => $request->cash_tendered - $finalAmount,
                'status' => 'Paid'
            ]);

            BillableItem::where('admission_id', $admission->id)
                ->where('status', 'Unpaid')
                ->update(['status' => 'Paid']);

            $admission->update(['status' => 'Cleared']);

            DB::commit();

            return redirect()->route('accountant.dashboard')
                ->with('success', 'Payment successful! Patient cleared for discharge.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function removeItem($id)
    {
        $item = BillableItem::findOrFail($id);

        if ($item->type !== 'fee') {
            return back()->with('error', 'You cannot remove medical or inventory items.');
        }

        $item->delete();

        return back()->with('success', 'Fee removed from bill.');
    }
}
