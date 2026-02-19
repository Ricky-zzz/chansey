@extends('layouts.clinic')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="card-enterprise p-5 mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('nurse.incidents.index') }}" class="btn btn-circle btn-ghost btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $title }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">Report a safety incident that occurred in your station</p>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('nurse.incidents.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- INCIDENT DETAILS SECTION --}}
        <div class="card-enterprise p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Incident Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Date and Time of Incident --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Date & Time of Incident *</label>
                    <input type="datetime-local" name="time_of_incident" required class="input-enterprise w-full"
                           value="{{ old('time_of_incident') }}">
                    @error('time_of_incident')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Incident Category --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Category *</label>
                    <select name="incident_category" required class="select-enterprise w-full">
                        <option value="">-- Select category --</option>
                        <option value="medication_error" @selected(old('incident_category') === 'medication_error')>Medication Error</option>
                        <option value="patient_fall" @selected(old('incident_category') === 'patient_fall')>Patient Fall</option>
                        <option value="equipment_malfunction" @selected(old('incident_category') === 'equipment_malfunction')>Equipment Malfunction</option>
                        <option value="near_miss" @selected(old('incident_category') === 'near_miss')>Near Miss</option>
                        <option value="wrong_documentation" @selected(old('incident_category') === 'wrong_documentation')>Wrong Documentation</option>
                        <option value="other" @selected(old('incident_category') === 'other')>Other</option>
                    </select>
                    @error('incident_category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Severity Level --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Severity Level *</label>
                    <select name="severity_level" required class="select-enterprise w-full">
                        <option value="">-- Select severity --</option>
                        <option value="Low" @selected(old('severity_level') === 'Low')>Low</option>
                        <option value="Moderate" @selected(old('severity_level') === 'Moderate')>Moderate</option>
                        <option value="High" @selected(old('severity_level') === 'High')>High</option>
                        <option value="Severe" @selected(old('severity_level') === 'Severe')>Severe</option>
                    </select>
                    @error('severity_level')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Patient (Admission) - Optional --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Patient (Optional)</label>
                    <select name="admission_id" class="select-enterprise w-full">
                        <option value="">-- No patient involved --</option>
                        @foreach($admissions as $admission)
                            <option value="{{ $admission->id }}" @selected(old('admission_id') == $admission->id)>
                                {{ $admission->patient->first_name }} {{ $admission->patient->last_name }} ({{ $admission->admission_number }})
                            </option>
                        @endforeach
                    </select>
                    @error('admission_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Location Details --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Location Details</label>
                    <input type="text" name="location_details" class="input-enterprise w-full"
                           placeholder="e.g., Room 101, Bed 2" value="{{ old('location_details') }}">
                    @error('location_details')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- NARRATIVE SECTION --}}
        <div class="card-enterprise p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Incident Description</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">What Happened? *</label>
                    <textarea name="what_happened" class="textarea-enterprise w-full h-20"
                              placeholder="Describe what actually happened...">{{ old('what_happened') }}</textarea>
                    @error('what_happened')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">How Was It Discovered?</label>
                    <textarea name="how_discovered" class="textarea-enterprise w-full h-16"
                              placeholder="How did you discover this incident?...">{{ old('how_discovered') }}</textarea>
                    @error('how_discovered')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Actions Taken Immediately</label>
                    <textarea name="action_taken" class="textarea-enterprise w-full h-16"
                              placeholder="What actions did you take after discovering the incident?...">{{ old('action_taken') }}</textarea>
                    @error('action_taken')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Overall Narrative</label>
                    <textarea name="narrative" class="textarea-enterprise w-full h-16"
                              placeholder="Provide overall summary of the incident...">{{ old('narrative') }}</textarea>
                    @error('narrative')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- PATIENT OUTCOME SECTION --}}
        <div class="card-enterprise p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Patient Outcome</h3>

            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="injury" value="1" class="checkbox-enterprise"
                               @checked(old('injury'))>
                        <span class="text-sm font-semibold text-slate-700">Patient was injured</span>
                    </label>
                </div>

                <div id="injury-details" style="display: {{ old('injury') ? 'block' : 'none' }};">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Injury Type/Description</label>
                    <input type="text" name="injury_type" class="input-enterprise w-full"
                           placeholder="Describe the injury..." value="{{ old('injury_type') }}">
                    @error('injury_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    <label class="block text-sm font-semibold text-slate-700 mb-2 mt-4">Vitals After Incident</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        <div>
                            <label class="label text-xs">BP</label>
                            <input type="text" name="vitals[bp]" class="input-enterprise input-sm" placeholder="120/80" value="{{ old('vitals.bp') }}">
                        </div>
                        <div>
                            <label class="label text-xs">Temp (°C)</label>
                            <input type="number" step="0.1" name="vitals[temperature]" class="input-enterprise input-sm" placeholder="37.5" value="{{ old('vitals.temperature') }}">
                        </div>
                        <div>
                            <label class="label text-xs">HR</label>
                            <input type="number" name="vitals[hr]" class="input-enterprise input-sm" placeholder="72" value="{{ old('vitals.hr') }}">
                        </div>
                        <div>
                            <label class="label text-xs">PR</label>
                            <input type="number" name="vitals[pr]" class="input-enterprise input-sm" placeholder="72" value="{{ old('vitals.pr') }}">
                        </div>
                        <div>
                            <label class="label text-xs">RR</label>
                            <input type="number" name="vitals[rr]" class="input-enterprise input-sm" placeholder="16" value="{{ old('vitals.rr') }}">
                        </div>
                        <div>
                            <label class="label text-xs">O2 Sat %</label>
                            <input type="number" name="vitals[o2]" class="input-enterprise input-sm" placeholder="98" value="{{ old('vitals.o2') }}">
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 mt-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="doctor_notified" value="1" class="checkbox-enterprise"
                               @checked(old('doctor_notified'))>
                        <span class="text-sm font-semibold text-slate-700">Doctor was notified</span>
                    </label>
                </div>

                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="family_notified" value="1" class="checkbox-enterprise"
                               @checked(old('family_notified'))>
                        <span class="text-sm font-semibold text-slate-700">Family was notified</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- STAFF INVOLVED SECTION --}}
        <div class="card-enterprise p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Staff Involved</h3>

            <label class="block text-sm font-semibold text-slate-700 mb-3">Select all staff involved in or witnessing this incident:</label>
            <div class="space-y-2 max-h-48 overflow-y-auto border border-slate-200 rounded-lg p-3">
                @foreach($staffInStation as $staff)
                    <label class="flex items-center gap-2 cursor-pointer hover:bg-slate-50 p-2 rounded">
                        <input type="checkbox" name="involved_staff[]" value="{{ $staff->id }}" class="checkbox-enterprise"
                               @checked(in_array($staff->id, old('involved_staff', [])))>
                        <span class="text-sm text-slate-700">{{ $staff->name }} ({{ $staff->nurse->designation ?? 'Staff' }})</span>
                    </label>
                @endforeach
            </div>
            @error('involved_staff')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ROOT CAUSE & FOLLOW-UP SECTION --}}
        <div class="card-enterprise p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Root Cause & Follow-up</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Root Cause</label>
                    <select name="root_cause" class="select-enterprise w-full">
                        <option value="">-- Select root cause --</option>
                        <option value="human_error" @selected(old('root_cause') === 'human_error')>Human Error</option>
                        <option value="system_issue" @selected(old('root_cause') === 'system_issue')>System Issue</option>
                        <option value="equipment_failure" @selected(old('root_cause') === 'equipment_failure')>Equipment Failure</option>
                        <option value="staffing_issue" @selected(old('root_cause') === 'staffing_issue')>Staffing Issue</option>
                        <option value="other" @selected(old('root_cause') === 'other')>Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Recommended Follow-up Actions</label>
                    <input type="text" name="follow_up_actions" class="input-enterprise w-full"
                           placeholder="e.g., monitoring, referral, corrective action" value="{{ old('follow_up_actions') }}">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Follow-up Instructions</label>
                    <textarea name="follow_up_instructions" class="textarea-enterprise w-full h-16"
                              placeholder="Describe detailed follow-up instructions...">{{ old('follow_up_instructions') }}</textarea>
                </div>
            </div>
        </div>

        {{-- BUTTONS --}}
        <div class="flex gap-3 justify-between">
            <a href="{{ route('nurse.incidents.index') }}" class="btn-enterprise-secondary">
                Cancel
            </a>
            <button type="submit" class="btn-enterprise-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Report Incident
            </button>
        </div>
    </form>

</div>

<script>
    document.querySelector('input[name="injury"]').addEventListener('change', function() {
        document.getElementById('injury-details').style.display = this.checked ? 'block' : 'none';
    });
</script>
@endsection
