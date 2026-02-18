@extends('layouts.clinic')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="card-enterprise p-5 mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('nurse.clinical.endorsments.index') }}" class="btn btn-circle btn-ghost btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $title }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">
                    <strong>{{ $admission->patient->first_name }} {{ $admission->patient->last_name }}</strong>
                    - Admission #{{ $admission->admission_number }}
                </p>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('nurse.clinical.endorsments.store') }}" method="POST" class="space-y-6">
        @csrf

        <input type="hidden" name="admission_id" value="{{ $admission->id }}">

        {{-- SITUATION SECTION --}}
        <div class="card-enterprise p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-8 h-8 bg-emerald-100 text-emerald-700 rounded-full text-sm font-bold">S</span>
                Situation
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Incoming Nurse --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Incoming Nurse *</label>
                    <select name="incoming_nurse_id" required class="select-enterprise w-full">
                        <option value="">-- Select incoming nurse --</option>
                        @foreach($incomingNurses as $nurse)
                        <option value="{{ $nurse->id }}">{{ $nurse->user->name }}</option>
                        @endforeach
                    </select>
                    @error('incoming_nurse_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Diagnosis --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Chief Complaint / Diagnosis</label>
                    <input type="text" name="diagnosis" class="input-enterprise w-full"
                           placeholder="e.g., Pneumonia, Post-operative monitoring" value="{{ old('diagnosis', $prefillData['diagnosis'] ?? '') }}">
                    @error('diagnosis')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Current Condition --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Current Condition</label>
                    <textarea name="current_condition" class="textarea-enterprise w-full h-20"
                              placeholder="Describe the patient's current state...">{{ old('current_condition') }}</textarea>
                    @error('current_condition')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Code Status --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Code Status *</label>
                    <select name="code_status" required class="select-enterprise w-full">
                        <option value="">-- Select code status --</option>
                        <option value="Low" @selected(old('code_status') === 'Low')>Low</option>
                        <option value="Moderate" @selected(old('code_status') === 'Moderate')>Moderate</option>
                        <option value="High" @selected(old('code_status') === 'High')>High</option>
                        <option value="Severe" @selected(old('code_status') === 'Severe')>Severe</option>
                    </select>
                    @error('code_status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- BACKGROUND SECTION --}}
        <div class="card-enterprise p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-8 h-8 bg-sky-100 text-sky-700 rounded-full text-sm font-bold">B</span>
                Background
            </h3>

            {{-- Allergies --}}
            <div class="mb-5">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Known Allergies</label>
                <div id="allergies-container" class="space-y-2" data-field="known_allergies">
                    @forelse($prefillData['known_allergies'] as $allergy)
                        <div class="flex gap-2">
                            <input type="text" name="known_allergies[]" class="input-enterprise flex-1" value="{{ $allergy }}">
                            <button type="button" class="btn-enterprise-secondary" onclick="this.parentElement.remove()">Remove</button>
                        </div>
                    @empty
                        <div class="flex gap-2">
                            <input type="text" name="known_allergies[]" class="input-enterprise flex-1" placeholder="e.g., Penicillin">
                            <button type="button" class="btn-enterprise-secondary" onclick="this.parentElement.remove()">Remove</button>
                        </div>
                    @endforelse
                </div>
                <button type="button" class="btn-enterprise-secondary mt-2" onclick="addField('allergies-container')">+ Add Allergy</button>
            </div>

            {{-- Medication History --}}
            <div class="mb-5">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Medication History</label>
                <div id="med-history-container" class="space-y-2" data-field="medication_history">
                    @forelse($prefillData['medication_history'] as $med)
                        <div class="flex gap-2">
                            <input type="text" name="medication_history[]" class="input-enterprise flex-1" value="{{ $med }}">
                            <button type="button" class="btn-enterprise-secondary" onclick="this.parentElement.remove()">Remove</button>
                        </div>
                    @empty
                        <div class="flex gap-2">
                            <input type="text" name="medication_history[]" class="input-enterprise flex-1" placeholder="e.g., Metoprolol 25mg BID">
                            <button type="button" class="btn-enterprise-secondary" onclick="this.parentElement.remove()">Remove</button>
                        </div>
                    @endforelse
                </div>
                <button type="button" class="btn-enterprise-secondary mt-2" onclick="addField('med-history-container')">+ Add Medication</button>
            </div>

            {{-- Past Medical History --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Past Medical History</label>
                <div id="past-med-container" class="space-y-2" data-field="past_medical_history">
                    @forelse($prefillData['past_medical_history'] as $history)
                        <div class="flex gap-2">
                            <input type="text" name="past_medical_history[]" class="input-enterprise flex-1" value="{{ $history }}">
                            <button type="button" class="btn-enterprise-secondary" onclick="this.parentElement.remove()">Remove</button>
                        </div>
                    @empty
                        <div class="flex gap-2">
                            <input type="text" name="past_medical_history[]" class="input-enterprise flex-1" placeholder="e.g., Hypertension, Diabetes">
                            <button type="button" class="btn-enterprise-secondary" onclick="this.parentElement.remove()">Remove</button>
                        </div>
                    @endforelse
                </div>
                <button type="button" class="btn-enterprise-secondary mt-2" onclick="addField('past-med-container')">+ Add History Item</button>
            </div>
        </div>

        {{-- ASSESSMENT SECTION --}}
        <div class="card-enterprise p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-8 h-8 bg-amber-100 text-amber-700 rounded-full text-sm font-bold">A</span>
                Assessment
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Blood Pressure --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Blood Pressure</label>
                    <input type="text" name="latest_vitals[bp]" class="input-enterprise w-full" placeholder="e.g., 120/80">
                </div>
                {{-- Temperature --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Temperature (°C)</label>
                    <input type="number" step="0.1" name="latest_vitals[temperature]" class="input-enterprise w-full" placeholder="37.5">
                </div>
                {{-- Heart Rate --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Heart Rate (bpm)</label>
                    <input type="number" name="latest_vitals[hr]" class="input-enterprise w-full" placeholder="72">
                </div>
                {{-- Pulse Rate --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Pulse Rate (bpm)</label>
                    <input type="number" name="latest_vitals[pr]" class="input-enterprise w-full" placeholder="72">
                </div>
                {{-- Respiratory Rate --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Respiratory Rate (breaths/min)</label>
                    <input type="number" name="latest_vitals[rr]" class="input-enterprise w-full" placeholder="16">
                </div>
                {{-- O2 Saturation --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">O2 Saturation (%)</label>
                    <input type="number" name="latest_vitals[o2]" class="input-enterprise w-full" placeholder="98">
                </div>
            </div>

            {{-- Pain Scale --}}
            <div class="mt-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Pain Scale (0-10)</label>
                <input type="text" name="pain_scale" class="input-enterprise w-full" placeholder="e.g., 3/10">
            </div>

            {{-- IV Lines --}}
            <div class="mt-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">IV Lines</label>
                <div id="iv-container" class="space-y-2" data-field="iv_lines">
                    <div class="flex gap-2">
                        <input type="text" name="iv_lines[]" class="input-enterprise flex-1" placeholder="e.g., D5LR via R forearm">
                        <button type="button" class="btn-enterprise-secondary" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                </div>
                <button type="button" class="btn-enterprise-secondary mt-2" onclick="addField('iv-container')">+ Add IV Line</button>
            </div>

            {{-- Wounds --}}
            <div class="mt-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Wounds / Incisions</label>
                <div id="wounds-container" class="space-y-2" data-field="wounds">
                    <div class="flex gap-2">
                        <input type="text" name="wounds[]" class="input-enterprise flex-1" placeholder="e.g., Post-op abdominal incision, clean, no discharge">
                        <button type="button" class="btn-enterprise-secondary" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                </div>
                <button type="button" class="btn-enterprise-secondary mt-2" onclick="addField('wounds-container')">+ Add Wound</button>
            </div>

            {{-- Labs Pending --}}
            <div class="mt-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Pending Labs</label>
                <div id="labs-pending-container" class="space-y-2" data-field="labs_pending">
                    <div class="flex gap-2">
                        <input type="text" name="labs_pending[]" class="input-enterprise flex-1" placeholder="e.g., CBC, Urinalysis (ordered 2 hrs ago)">
                        <button type="button" class="btn-enterprise-secondary" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                </div>
                <button type="button" class="btn-enterprise-secondary mt-2" onclick="addField('labs-pending-container')">+ Add Pending Lab</button>
            </div>

            {{-- Abnormal Findings --}}
            <div class="mt-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Abnormal Findings</label>
                <div id="abnormal-container" class="space-y-2" data-field="abnormal_findings">
                    <div class="flex gap-2">
                        <input type="text" name="abnormal_findings[]" class="input-enterprise flex-1" placeholder="e.g., Mild dyspnea on exertion">
                        <button type="button" class="btn-enterprise-secondary" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                </div>
                <button type="button" class="btn-enterprise-secondary mt-2" onclick="addField('abnormal-container')">+ Add Finding</button>
            </div>
        </div>

        {{-- RECOMMENDATIONS SECTION --}}
        <div class="card-enterprise p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-8 h-8 bg-pink-100 text-pink-700 rounded-full text-sm font-bold">R</span>
                Recommendations
            </h3>

            {{-- Upcoming Medications --}}
            <div class="mb-5">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Upcoming Medications to Give</label>
                <div id="upcoming-meds-container" class="space-y-2" data-field="upcoming_medications">
                    <div class="flex gap-2">
                        <input type="text" name="upcoming_medications[]" class="input-enterprise flex-1" placeholder="e.g., Paracetamol 500mg before 6 PM">
                        <button type="button" class="btn-enterprise-secondary" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                </div>
                <button type="button" class="btn-enterprise-secondary mt-2" onclick="addField('upcoming-meds-container')">+ Add Medication</button>
            </div>

            {{-- Labs Follow-up --}}
            <div class="mb-5">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Lab Follow-ups</label>
                <div id="labs-followup-container" class="space-y-2" data-field="labs_follow_up">
                    <div class="flex gap-2">
                        <input type="text" name="labs_follow_up[]" class="input-enterprise flex-1" placeholder="e.g., Repeat CBC if temp spikes above 38.5°C">
                        <button type="button" class="btn-enterprise-secondary" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                </div>
                <button type="button" class="btn-enterprise-secondary mt-2" onclick="addField('labs-followup-container')">+ Add Follow-up</button>
            </div>

            {{-- Monitor Instructions --}}
            <div class="mb-5">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Monitoring Instructions</label>
                <div id="monitor-container" class="space-y-2" data-field="monitor_instructions">
                    <div class="flex gap-2">
                        <input type="text" name="monitor_instructions[]" class="input-enterprise flex-1" placeholder="e.g., Monitor I&O every 4 hours">
                        <button type="button" class="btn-enterprise-secondary" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                </div>
                <button type="button" class="btn-enterprise-secondary mt-2" onclick="addField('monitor-container')">+ Add Instruction</button>
            </div>

            {{-- Special Precautions --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Special Precautions</label>
                <div id="precautions-container" class="space-y-2" data-field="special_precautions">
                    <div class="flex gap-2">
                        <input type="text" name="special_precautions[]" class="input-enterprise flex-1" placeholder="e.g., Fall risk - assist all transfers">
                        <button type="button" class="btn-enterprise-secondary" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                </div>
                <button type="button" class="btn-enterprise-secondary mt-2" onclick="addField('precautions-container')">+ Add Precaution</button>
            </div>
        </div>

        {{-- WARD LEVEL (optional) --}}
        <div class="card-enterprise p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Ward/Station Status</h3>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Bed Occupancy</label>
                <input type="text" name="bed_occupancy" class="input-enterprise w-full" placeholder="e.g., 8 of 12 beds occupied">
            </div>

            {{-- Equipment Issues --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Equipment Issues</label>
                <div id="equipment-container" class="space-y-2" data-field="equipment_issues">
                    <div class="flex gap-2">
                        <input type="text" name="equipment_issues[]" class="input-enterprise flex-1" placeholder="e.g., Monitor in Room 3 needs battery">
                        <button type="button" class="btn-enterprise-secondary" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                </div>
                <button type="button" class="btn-enterprise-secondary mt-2" onclick="addField('equipment-container')">+ Add Issue</button>
            </div>

            {{-- Pending Admissions --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Pending Admissions</label>
                <div id="pending-admissions-container" class="space-y-2" data-field="pending_admissions">
                    <div class="flex gap-2">
                        <input type="text" name="pending_admissions[]" class="input-enterprise flex-1" placeholder="e.g., Bed 5 prepared for incoming admitted patient">
                        <button type="button" class="btn-enterprise-secondary" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                </div>
                <button type="button" class="btn-enterprise-secondary mt-2" onclick="addField('pending-admissions-container')">+ Add</button>
            </div>

            {{-- Station Issues --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Station Issues</label>
                <div id="station-issues-container" class="space-y-2" data-field="station_issues">
                    <div class="flex gap-2">
                        <input type="text" name="station_issues[]" class="input-enterprise flex-1" placeholder="e.g., Supply cart needs restocking">
                        <button type="button" class="btn-enterprise-secondary" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                </div>
                <button type="button" class="btn-enterprise-secondary mt-2" onclick="addField('station-issues-container')">+ Add Issue</button>
            </div>

            {{-- Critical Incidents --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Critical Incidents</label>
                <div id="incidents-container" class="space-y-2" data-field="critical_incidents">
                    <div class="flex gap-2">
                        <input type="text" name="critical_incidents[]" class="input-enterprise flex-1" placeholder="e.g., Patient fall at 2 PM, incident report filed">
                        <button type="button" class="btn-enterprise-secondary" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                </div>
                <button type="button" class="btn-enterprise-secondary mt-2" onclick="addField('incidents-container')">+ Add Incident</button>
            </div>
        </div>

        {{-- Submit Buttons --}}
        <div class="flex gap-3 justify-between">
            <a href="{{ route('nurse.clinical.endorsments.index') }}" class="btn-enterprise-secondary">
                Cancel
            </a>
            <div class="flex gap-3">
                <button type="submit" name="action" value="draft" class="btn-enterprise-secondary">
                Save as Draft
                </button>
                <button type="submit" name="auto_submit" value="1" class="btn-enterprise-primary">
                Submit Now
                </button>
            </div>
        </div>
    </form>

</div>

<script>
function addField(containerId) {
    const container = document.getElementById(containerId);
    const fieldName = container.getAttribute('data-field');
    const newDiv = document.createElement('div');
    newDiv.className = 'flex gap-2';
    newDiv.innerHTML = `
        <input type="text" name="${fieldName}[]" class="input-enterprise flex-1" placeholder="">
        <button type="button" class="btn-enterprise-secondary" onclick="this.parentElement.remove()">Remove</button>
    `;
    container.appendChild(newDiv);
}
</script>

@endsection
