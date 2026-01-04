<?php

namespace App\Http\Controllers\Clinical;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\NursingCarePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarePlanController extends Controller
{
    public function edit($admission_id)
    {
        $admission = Admission::with('patient', 'bed.room.station')->findOrFail($admission_id);
        
        // Find existing plan OR create a blank instance
        $plan = NursingCarePlan::firstOrNew(['admission_id' => $admission_id]);

        return view('nurse.clinical.ward.care_plan', compact('admission', 'plan'));
    }

    public function update(Request $request, $admission_id)
    {
        $validated = $request->validate([
            'assessment' => 'required|string',
            'diagnosis' => 'required|string',
            'planning' => 'nullable|array',
            'interventions' => 'nullable|array',
            'rationale' => 'nullable|string',
            'evaluation' => 'nullable|string',
            'status' => 'required',
        ]);

        NursingCarePlan::updateOrCreate(
            ['admission_id' => $admission_id],
            [
                'nurse_id' => Auth::user()->nurse->id,
                'assessment' => $request->assessment,
                'diagnosis' => $request->diagnosis,
                'planning' => $request->planning ?? [],
                'interventions' => $request->interventions ?? [],
                'rationale' => $request->rationale,
                'evaluation' => $request->evaluation,
                'status' => $request->status,
            ]
        );

        return redirect()->route('nurse.clinical.ward.show', $admission_id)
            ->with('success', 'Nursing Care Plan updated.');
    }
}