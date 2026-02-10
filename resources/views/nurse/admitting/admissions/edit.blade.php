@extends('layouts.layout')

@section('content')
<!-- ALpine Data for Allergies matches your logic -->
<div class="max-w-5xl mx-auto"
    x-data="{
        allergies: {{ Js::from($admission->known_allergies ?? []) }},
        currentInput: '',
        addAllergy() {
            if (this.currentInput.trim() !== '' && !this.allergies.includes(this.currentInput.trim())) {
                this.allergies.push(this.currentInput.trim());
                this.currentInput = '';
            }
        },
        removeAllergy(index) {
            this.allergies.splice(index, 1);
        }
     }">

    <!-- HEADER & ACTIONS -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Edit Admission</h2>
            <div class="flex items-center gap-2 text-sm text-slate-500 mt-1">
                <a href="{{ route('nurse.admitting.dashboard') }}" class="hover:text-emerald-600">Dashboard</a>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('nurse.admitting.patients.index') }}" class="hover:text-emerald-600">Patients</a>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('nurse.admitting.patients.show',$admission->patient->id ) }}" class="text-emerald-600 font-medium">{{ $admission->patient->last_name }}, {{ $admission->patient->first_name }}</a>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('nurse.admitting.admissions.index') }}" class="hover:text-emerald-600">Admissions</a>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('nurse.admitting.admissions.show',$admission->id ) }}" class="text-emerald-600 font-medium">{{ $admission->admission_number }}</a>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-slate-700 font-medium">Edit</span>
            </div>
        </div>
    </div>

    <!-- MAIN FORM -->
    <form action="{{ route('nurse.admitting.admissions.update', $admission->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- ACCORDION 1: LOGISTICS -->
        <div class="collapse collapse-arrow card-enterprise mb-4">
            <input type="checkbox" name="accordion-1" checked />
            <div class="collapse-title text-lg font-bold text-slate-800">
                Admission Logistics
            </div>
            <div class="collapse-content">
                <div class="pt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Type -->
                    <label class="floating-label w-full">
                        <span>Admission Type</span>
                        <select name="admission_type" class="select-enterprise w-full">
                            @foreach(['Emergency', 'Outpatient', 'Inpatient', 'Transfer'] as $type)
                            <option value="{{ $type }}" {{ old('admission_type', $admission->admission_type) == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                            @endforeach
                        </select>
                    </label>

                    <!-- Physician -->
                    <label class="floating-label w-full">
                        <span>Attending Physician</span>
                        <select name="attending_physician_id" class="select-enterprise w-full">
                            @foreach($physicians as $doc)
                            <option value="{{ $doc->id }}" {{ old('attending_physician_id', $admission->attending_physician_id) == $doc->id ? 'selected' : '' }}>
                                Dr. {{ $doc->getFullNameAttribute() }}
                            </option>
                            @endforeach
                        </select>
                    </label>

                    <!-- Case Type -->
                    <label class="floating-label w-full">
                        <span>Case Type</span>
                        <select name="case_type" class="select-enterprise w-full">
                            @foreach(['New Case', 'Returning', 'Follow-up'] as $ctype)
                            <option value="{{ $ctype }}" {{ old('case_type', $admission->case_type) == $ctype ? 'selected' : '' }}>
                                {{ $ctype }}
                            </option>
                            @endforeach
                        </select>
                    </label>

                    <!-- Mode of Arrival -->
                    <label class="floating-label w-full">
                        <span>Mode of Arrival</span>
                        <select name="mode_of_arrival" class="select-enterprise w-full">
                            @foreach(['Walk-in', 'Ambulance', 'Wheelchair', 'Stretcher'] as $mode)
                            <option value="{{ $mode }}" {{ old('mode_of_arrival', $admission->mode_of_arrival) == $mode ? 'selected' : '' }}>
                                {{ $mode }}
                            </option>
                            @endforeach
                        </select>
                    </label>
                </div>
            </div>
        </div>

        <!-- ACCORDION 2: CLINICAL DATA -->
        <div class="collapse collapse-arrow card-enterprise mb-4">
            <input type="checkbox" name="accordion-2" />
            <div class="collapse-title text-lg font-bold text-slate-800">
                Clinical Information
            </div>
            <div class="collapse-content">
                <div class="pt-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="label"><span class="label-text font-bold">Chief Complaint</span></label>
                            <textarea name="chief_complaint" class="textarea-enterprise h-24 w-full" placeholder="Describe the patient's chief complaint">{{ old('chief_complaint', $admission->chief_complaint) }}</textarea>
                        </div>

                        <div>
                            <label class="label"><span class="label-text font-bold">Initial Diagnosis</span></label>
                            <textarea name="initial_diagnosis" class="textarea-enterprise h-24 w-full" placeholder="Enter initial diagnosis">{{ old('initial_diagnosis', $admission->initial_diagnosis) }}</textarea>
                        </div>
                    </div>

                    <!-- Vitals -->
                    <h4 class="font-bold text-slate-500 uppercase text-xs mb-4 border-b pb-2">Updated Vitals</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <label class="floating-label w-full">
                            <span>Temp (°C)</span>
                            <input type="number" step="0.1" name="temp" value="{{ old('temp', $admission->initial_vitals['temp'] ?? '') }}" class="input-enterprise w-full" placeholder="Temperature" />
                        </label>
                        <label class="floating-label w-full">
                            <span>Blood Pressure (e.g. 120/80)</span>
                            <input type="text" name="bp" value="{{ old('bp', $admission->initial_vitals['bp'] ?? '') }}" class="input-enterprise w-full" placeholder="e.g. 120/80" />
                        </label>
                        <label class="floating-label w-full">
                            <span>Heart Rate (bpm)</span>
                            <input type="number" name="hr" value="{{ old('hr', $admission->initial_vitals['hr'] ?? '') }}" class="input-enterprise w-full" placeholder="Heart Rate" />
                        </label>
                        <label class="floating-label w-full">
                            <span>Pulse Rate</span>
                            <input type="number" name="pr" value="{{ old('pr', $admission->initial_vitals['pr'] ?? '') }}" class="input-enterprise w-full" placeholder="Pulse Rate" />
                        </label>
                        <label class="floating-label w-full">
                            <span>Respiratory Rate (breaths/min)</span>
                            <input type="number" name="rr" value="{{ old('rr', $admission->initial_vitals['rr'] ?? '') }}" class="input-enterprise w-full" placeholder="Respiratory Rate" />
                        </label>
                        <label class="floating-label w-full">
                            <span>O2 Saturation (%)</span>
                            <input type="number" name="o2" value="{{ old('o2', $admission->initial_vitals['o2'] ?? '') }}" class="input-enterprise w-full" placeholder="Oxygen Saturation" />
                        </label>
                        <label class="floating-label w-full">
                            <span>Height (cm)</span>
                            <input type="number" step="0.1" name="height" value="{{ old('height', $admission->initial_vitals['height'] ?? '') }}" class="input-enterprise w-full" placeholder="Height in cm" />
                        </label>
                        <label class="floating-label w-full">
                            <span>Weight (kg)</span>
                            <input type="number" step="0.1" name="weight" value="{{ old('weight', $admission->initial_vitals['weight'] ?? '') }}" class="input-enterprise w-full" placeholder="Weight in kg" />
                        </label>
                    </div>

                    <!-- Allergies -->
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Known Allergies</span></label>
                        <div class="flex w-full mb-2">
                            <input type="text" x-model="currentInput" @keydown.enter.prevent="addAllergy()" class="input-enterprise rounded-r-none w-full" placeholder="Add allergy..." />
                            <button type="button" @click="addAllergy()" class="btn-enterprise-primary rounded-l-none">Add</button>
                        </div>
                        <div class="flex flex-wrap gap-2 min-h-12 p-4 bg-slate-50 rounded-lg border border-slate-200">
                            <div x-show="allergies.length === 0" class="text-gray-400 text-sm italic w-full text-center py-2">
                                No allergies recorded.
                            </div>
                            <template x-for="(allergy, index) in allergies" :key="index">
                                <div class="badge-enterprise bg-red-50 text-red-700 border border-red-100 gap-2 px-3 py-2 font-bold">
                                    <span x-text="allergy"></span>
                                    <button type="button" @click="removeAllergy(index)" class="hover:text-black transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-4 h-4 stroke-current">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                    <input type="hidden" name="known_allergies[]" :value="allergy">
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ACCORDION 3: DOCUMENTS -->
        <div class="collapse collapse-arrow card-enterprise mb-8">
            <input type="checkbox" name="accordion-4" />
            <div class="collapse-title text-lg font-bold text-slate-800">
                Manage Documents
            </div>
            <div class="collapse-content">
                <div class="pt-4">
                    <div class="flex items-center gap-3 p-3 bg-sky-50 border border-sky-200 rounded-lg text-sm text-sky-800 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-sky-600 shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Only upload if you wish to REPLACE the existing document.</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach(['doc_valid_id' => 'Valid ID', 'doc_loa' => 'Insurance LOA', 'doc_consent' => 'General Consent', 'doc_privacy' => 'Privacy Notice', 'doc_mdr' => 'PhilHealth MDR'] as $inputName => $label)
                        <div class="w-full">
                            <label class="label"><span class="label-text font-bold">{{ $label }}</span></label>

                            @php $file = $admission->files->where('document_type', $label)->first(); @endphp
                            @if($file)
                            <div class="flex items-center gap-2 mb-2 p-2 bg-green-50 border border-green-200 rounded text-xs">
                                <span class="text-green-700 font-bold">Current:</span> {{ $file->file_name }}
                                <a href="{{ route('document.view', $file->id) }}" target="_blank" class="link link-neutral ml-auto text-xs">View</a>
                            </div>
                            @endif
                            <input type="file" name="{{ $inputName }}" class="file-input file-input-bordered file-input-md w-full" accept=".jpg,.png,.pdf" />
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- STICKY ACTION BAR (Optional but recommended for long forms) -->
        <div class="sticky bottom-4 z-50 flex justify-end gap-4 p-4 bg-white/80 backdrop-blur border border-slate-200 rounded-lg shadow-lg">
            <a href="{{ route('nurse.admitting.admissions.show', $admission->id) }}" class="btn-enterprise-danger">Cancel</a>
            <button type="submit" class="btn-enterprise-primary">
                Save Changes
            </button>
        </div>

    </form>
</div>
@endsection
