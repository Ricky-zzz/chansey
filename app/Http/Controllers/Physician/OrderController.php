<?php

namespace App\Http\Controllers\Physician;

use App\Http\Controllers\Controller;
use App\Models\MedicalOrder;
use App\Models\Admission;
use App\Models\ClinicalLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'admission_id' => 'required|exists:admissions,id',
            'type' => 'required|string',

            'medicine_id' => 'required_if:type,Medication|nullable|exists:medicines,id',
            'quantity' => 'required_if:type,Medication|integer|min:1',
            'instruction' => 'nullable|string',
        ]);

        if ($request->type === 'Discharge') {
            $instructionText = "Patient good for discharge. Please process billing.";
            
            $admission = Admission::find($request->admission_id);
            $admission->update(['status' => 'Ready for Discharge']);
        } else {
            $instructionText = $request->instruction ?? 'No specific instructions.';
        }

        $finalFrequency = in_array($request->type, ['Medication', 'Monitoring'])
            ? $request->frequency
            : 'Once';

        $order = MedicalOrder::create([
            'admission_id' => $request->admission_id,
            'physician_id' => Auth::user()->physician->id,

            'type' => $request->type,
            'instruction' => $instructionText,

            'medicine_id' => $request->medicine_id,
            'quantity' => $request->quantity ?? 1,
            'frequency' => $finalFrequency,

            'status' => 'Pending',
        ]);

        if ($request->type === 'Discharge') {
            ClinicalLog::create([
                'admission_id' => $request->admission_id,
                'user_id' => Auth::id(),
                'medical_order_id' => $order->id,
                'type' => 'Discharge',
                'data' => ['note' => 'Physician cleared patient for discharge.']
            ]);
        }

        return back()->with('success', 'Order created successfully.');
    }

    public function discontinue($id)
    {
        $order = MedicalOrder::findOrFail($id);

        if ($order->physician_id !== Auth::user()->physician->id) {
            abort(403, 'You cannot discontinue another doctor\'s order.');
        }
        $order->update([
            'status' => 'Discontinued'
        ]);

        return back()->with('success', 'Order discontinued.');
    }

    public function destroy($id)
    {
        $order = MedicalOrder::findOrFail($id);

        if ($order->physician_id !== Auth::user()->physician->id) {
            abort(403);
        }

        if ($order->clinicalLogs()->exists()) {
            return back()->with('error', 'Cannot delete order. It has already been executed by a nurse. Use Discontinue instead.');
        }

        $order->delete();

        return back()->with('success', 'Order removed.');
    }
}
