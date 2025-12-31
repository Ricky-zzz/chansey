<?php

namespace App\Http\Controllers\Physician;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\TreatmentPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreatmentPlanController extends Controller
{
    // SHOW THE FORM (Create or Edit)
    public function edit($admission_id)
    {
        $admission = Admission::with('patient')->findOrFail($admission_id);
        
        // Find existing plan OR create a blank instance (in memory only)
        $plan = TreatmentPlan::firstOrNew(['admission_id' => $admission_id]);

        return view('physician.treatment_plan.edit', compact('admission', 'plan'));
    }

    // SAVE THE DATA
    public function update(Request $request, $admission_id)
    {
        $validated = $request->validate([
            'main_problem' => 'required|string',
            'goals' => 'nullable|array',
            'goals.*' => 'string|distinct', // Ensure bullets are strings
            'interventions' => 'nullable|array',
            'interventions.*' => 'string|distinct',
            'expected_outcome' => 'nullable|string',
            'evaluation' => 'nullable|string',
            'status' => 'required|in:Active,Resolved,Revised',
        ]);

        // Smart Save: Create if new, Update if exists
        TreatmentPlan::updateOrCreate(
            ['admission_id' => $admission_id], // Search criteria
            [
                'physician_id' => Auth::user()->physician->id,
                'main_problem' => $request->main_problem,
                'goals' => $request->goals ?? [],
                'interventions' => $request->interventions ?? [],
                'expected_outcome' => $request->expected_outcome,
                'evaluation' => $request->evaluation,
                'status' => $request->status,
            ] // Data to update/insert
        );

        return redirect()->route('physician.patients.show', $admission_id)
            ->with('success', 'Treatment Plan updated successfully.');
    }
}