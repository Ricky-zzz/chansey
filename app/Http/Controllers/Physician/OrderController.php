<?php

namespace App\Http\Controllers\Physician;

use App\Http\Controllers\Controller;
use App\Models\MedicalOrder;
use App\Models\Admission;
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
            $instructionText = "Patient cleared for discharge. Please process billing.";
        } else {
            $instructionText = $request->instruction ?? 'No specific instructions.';
        }

        $finalFrequency = in_array($request->type, ['Medication', 'Monitoring'])
            ? $request->frequency
            : 'Once';

        MedicalOrder::create([
            'admission_id' => $request->admission_id,
            'physician_id' => Auth::user()->physician->id,

            'type' => $request->type,
            'instruction' => $instructionText, // Uses our calculated variable

            'medicine_id' => $request->medicine_id,
            'quantity' => $request->quantity ?? 1,
            'frequency' => $finalFrequency,

            'status' => 'Pending',
        ]);

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
