@extends('layouts.layout')

@section('content')
<div class="max-w-5xl mx-auto" x-data='{
    step: 1,
    errorMessage: "",

    validateAndNext() {
        this.errorMessage = "";
        const form = document.querySelector("form");
        const requiredFields = form.querySelectorAll("[data-step=\"" + this.step + "\"][required]");

        let firstInvalidField = null;

        requiredFields.forEach(field => {
            const value = field.value?.trim();
            const isEmpty = !value || value === "";

            if (isEmpty) {
                if (!firstInvalidField) {
                    firstInvalidField = field;
                }
                field.classList.add("input-error", "select-error");
            } else {
                field.classList.remove("input-error", "select-error");
            }
        });

        if (this.step === 2) {
            const allergiesContainer = document.querySelector("[x-data*=\"allergies\"]");
            if (allergiesContainer && allergiesContainer.__x_data && allergiesContainer.__x_data.allergies.length === 0) {
                this.errorMessage = "Please add at least one allergy record (even if patient has no known allergies, select None Known)";
                firstInvalidField = allergiesContainer;
            }
        }

        if (firstInvalidField) {
            this.errorMessage = this.errorMessage || "Please fill in all required fields before proceeding.";
            firstInvalidField.scrollIntoView({ behavior: "smooth", block: "center" });
            firstInvalidField.focus();
            return;
        }

        this.step++;
    }
}'">
    <!-- DISPLAY ALL VALIDATION ERRORS -->
    @if ($errors->any())
    <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-lg mb-6" x-data="{ show: true }" x-show="show" @click.outside="show = false">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-red-600 shrink-0 h-5 w-5 mt-0.5" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="flex-1">
            <h3 class="font-bold text-sm text-red-800">Validation Errors:</h3>
            <ul class="list-disc list-inside text-sm mt-1 text-red-700">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button @click="show = false" class="text-red-600 hover:text-red-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif

    @if (session('error'))
    <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 rounded-lg mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-red-600 shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-sm text-red-800">{{ session('error') }}</span>
    </div>
    @endif
    <div class=" flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <div>
        <div class="flex items-center gap-1.5 text-sm text-slate-500">
                <a href="{{ route('nurse.admitting.dashboard') }}" class="hover:text-emerald-600">Dashboard</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('nurse.admitting.patients.index') }}" class="hover:text-emerald-600">Patients</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('nurse.admitting.patients.show',$patient ) }}" class="text-emerald-600 font-medium">{{ $patient->last_name }}, {{ $patient->first_name }}</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-slate-800 font-semibold">New Admission</span>
        </div>
    </div>
</div>

<!-- HEADER & WIZARD STEPS -->
<div class=" mb-8">
    <ul class="steps w-full">
        <li class="step" :class="step >= 1 ? 'step-primary' : ''">Clinical Admission</li>
        <li class="step" :class="step >= 2 ? 'step-primary' : ''">Documents</li>
        <li class="step" :class="step >= 3 ? 'step-primary' : ''">Review & Submit</li>
    </ul>
</div>

<!-- THE ONE GIANT FORM -->
<form action="{{ route('nurse.admitting.admissions.store', $patient) }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- STEP 1: ADMISSION DETAILS -->
    <div x-show="step === 1" class="animate-fade-in w-full max-w-5xl mx-auto">
        <div class="card-enterprise">
            <div class="p-8">

                <!-- ERROR MESSAGE DISPLAY -->
                <div x-show="step === 1 && errorMessage" class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 rounded-lg mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-red-600 shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm text-red-800" x-text="errorMessage"></span>
                </div>

                <!-- CENTERED MAIN TITLE -->
                <h3 class="text-2xl font-bold text-slate-800 text-center mb-8 border-b border-slate-200 pb-4">
                    Admission Details
                </h3>

                <!-- 1. ADMISSION INFO -->
                <fieldset class="mb-8"
                    x-data="{
                                    stations: {{ Js::from($stations) }},
                                    allBeds: {{ Js::from($rawBeds) }},
                                    selectedStation: '',
                                    admissionType: '',

                                    get filteredBeds() {
                                        if (!this.selectedStation) return [];
                                        return this.allBeds.filter(bed => bed.station_id == this.selectedStation);
                                    }
                                }">

                    <legend class="text-lg font-bold text-center w-full text-slate-500 uppercase tracking-wide mb-6">
                        Admission Information
                    </legend>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- 1. SELECT STATION (The Filter Trigger) -->
                        <label class="floating-label w-full">
                            <span>Nursing Station / Ward</span>
                            <select name="station_id" data-step="1" x-model="selectedStation" class="select-enterprise w-full @error('station_id') select-error @enderror" required>
                                <option value="" disabled selected>Select Station First</option>
                                <template x-for="station in stations" :key="station.id">
                                    <option :value="station.id" :selected="'{{ old('station_id') }}' == station.id" x-text="station.station_name"></option>
                                </template>
                            </select>
                            @error('station_id')
                                <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                            @enderror
                        </label>

                        <!-- 2. SELECT BED (Filtered Results) -->
                        <label class="floating-label w-full" :hidden="admissionType === 'Outpatient'">
                            <span>Room and Bed Assignment</span>
                            <select name="bed_id" data-step="1" class="select-enterprise w-full @error('bed_id') select-error @enderror" :disabled="!selectedStation">
                                <option value="" disabled selected x-text="selectedStation ? 'Select Available Bed' : 'Please Select Station First'"></option>

                                <template x-for="bed in filteredBeds" :key="bed.id">
                                    <option :value="bed.id" :selected="'{{ old('bed_id') }}' == bed.id" x-text="bed.bed_code + ' (Room ' + bed.room_number + ')'"></option>
                                </template>

                                <option disabled x-show="selectedStation && filteredBeds.length === 0">
                                    No beds available in this station
                                </option>
                            </select>
                            @error('bed_id')
                                <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                            @enderror
                        </label>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:col-span-2">
                            <label class="floating-label w-full">
                                <span>Admission Type</span>
                                <select name="admission_type" data-step="1" x-model="admissionType" class="select-enterprise w-full @error('admission_type') select-error @enderror" required>
                                    <option value="" disabled selected>Select Admission Type</option>
                                    <option value="Emergency" {{ old('admission_type') == 'Emergency' ? 'selected' : '' }}>Emergency Encounter</option>
                                    <option value="Outpatient" {{ old('admission_type') == 'Outpatient' ? 'selected' : '' }}>Outpatient Consultation</option>
                                    <option value="Inpatient" {{ old('admission_type') == 'Inpatient' ? 'selected' : '' }}>Inpatient Admission</option>
                                    <option value="Transfer" {{ old('admission_type') == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                                </select>
                                @error('admission_type')
                                    <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                                @enderror
                            </label>

                            <label class="floating-label w-full">
                                <span>Attending Physician</span>
                                <select name="attending_physician_id" data-step="1" class="select-enterprise w-full @error('attending_physician_id') select-error @enderror" required>
                                    <option value="">Select an Attending Physician</option>
                                    @foreach($physicians as $doc)
                                    <option value="{{ $doc->id }}" {{ old('attending_physician_id') == $doc->id ? 'selected' : '' }}>Dr. {{ $doc->getFullNameAttribute() }}</option>
                                    @endforeach
                                </select>
                                @error('attending_physician_id')
                                    <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                                @enderror
                            </label>

                            <label class="floating-label w-full">
                                <span>Case Type</span>
                                <select name="case_type" data-step="1" class="select-enterprise w-full @error('case_type') select-error @enderror" required>
                                    <option value="" disabled selected>Select Case Type</option>
                                    <option value="New Case" {{ old('case_type') == 'New Case' ? 'selected' : '' }}>New Case</option>
                                    <option value="Returning" {{ old('case_type') == 'Returning' ? 'selected' : '' }}>Returning</option>
                                    <option value="Follow-up" {{ old('case_type') == 'Follow-up' ? 'selected' : '' }}>Follow-up</option>
                                </select>
                                @error('case_type')
                                    <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                                @enderror
                            </label>

                            <label class="floating-label w-full">
                                <span>Mode of Arrival</span>
                                <select name="mode_of_arrival" data-step="1" class="select-enterprise w-full @error('mode_of_arrival') select-error @enderror" required>
                                    <option value="" disabled selected>Select Mode of Arrival</option>
                                    <option value="Walk-in" {{ old('mode_of_arrival') == 'Walk-in' ? 'selected' : '' }}>Walk-in</option>
                                    <option value="Ambulance" {{ old('mode_of_arrival') == 'Ambulance' ? 'selected' : '' }}>Ambulance</option>
                                    <option value="Wheelchair" {{ old('mode_of_arrival') == 'Wheelchair' ? 'selected' : '' }}>Wheelchair</option>
                                    <option value="Stretcher" {{ old('mode_of_arrival') == 'Stretcher' ? 'selected' : '' }}>Stretcher</option>
                                </select>
                                @error('mode_of_arrival')
                                    <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                                @enderror
                            </label>
                        </div>
                </fieldset>

                <!-- 2. CLINICAL INFO -->
                <fieldset class="mb-8">
                    <legend class="text-lg font-bold text-center w-full text-slate-500 uppercase tracking-wide mb-6">
                        Clinical Information
                    </legend>

                    <div class="grid grid-cols-1 gap-6">
                        <label class="floating-label w-full">
                            <span>Chief Complaint</span>
                            <textarea name="chief_complaint" data-step="1" class="textarea-enterprise w-full @error('chief_complaint') textarea-error @enderror" placeholder="Describe the patient's primary complaint" required>{{ old('chief_complaint') }}</textarea>
                            @error('chief_complaint')
                                <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                            @enderror
                        </label>

                        <label class="floating-label w-full">
                            <span>Initial Diagnosis</span>
                            <textarea name="initial_diagnosis" class="textarea-enterprise w-full @error('initial_diagnosis') textarea-error @enderror" placeholder="Initial medical impression or suspected diagnosis">{{ old('initial_diagnosis') }}</textarea>
                            @error('initial_diagnosis')
                                <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                            @enderror
                        </label>
                    </div>
                </fieldset>

                <!-- 3. VITAL SIGNS -->
                <fieldset class="mb-8">
                    <legend class="text-lg font-bold text-center w-full text-slate-500 uppercase tracking-wide mb-6">
                        Vital Signs
                    </legend>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <label class="floating-label w-full">
                            <span>Temperature (°C)</span>
                            <input type="number" step="0.1" name="temp" class="input input-md w-full" placeholder="Temperature">
                        </label>

                        <label class="floating-label w-full">
                            <span>Blood Pressure (e.g. 120/80)</span>
                            <input type="text" name="bp" class="input input-md w-full" placeholder="e.g. 120/80">
                        </label>

                        <label class="floating-label w-full">
                            <span>Heart Rate (bpm)</span>
                            <input type="number" name="hr" class="input input-md w-full" placeholder="Heart Rate (bpm)">
                        </label>

                        <label class="floating-label w-full">
                            <span>Pulse Rate (bpm)</span>
                            <input type="number" name="pr" class="input input-md w-full" placeholder="Pulse Rate">
                        </label>

                        <label class="floating-label w-full">
                            <span>Respiratory Rate (breaths/min)</span>
                            <input type="number" name="rr" class="input input-md w-full" placeholder="Respiratory Rate">
                        </label>

                        <label class="floating-label w-full">
                            <span>Oxygen Saturation (%)</span>
                            <input type="number" name="o2" class="input input-md w-full" placeholder="Oxygen Saturation">
                        </label>

                        <label class="floating-label w-full">
                            <span>Height (cm)</span>
                            <input type="number" step="0.1" name="height" class="input input-md w-full" placeholder="Height in cm">
                        </label>

                        <label class="floating-label w-full">
                            <span>Weight (kg)</span>
                            <input type="number" step="0.1" name="weight" class="input input-md w-full" placeholder="Weight in kg">
                        </label>
                    </div>
                </fieldset>

                <!-- 4. ALLERGIES -->
                <fieldset class="mb-2"
                    x-data="{
                                  allergies: [],
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
                    <legend class="text-lg font-bold text-center w-full text-slate-500 uppercase tracking-wide mb-6">
                        Known Allergies
                    </legend>

                    <div class="form-control w-full">
                        <!-- Input Group -->
                        <label class="label">
                            <span class="label-text font-bold">Add Allergy</span>
                        </label>
                        <div class="flex w-full mb-4">
                            <input
                                type="text"
                                x-model="currentInput"
                                @keydown.enter.prevent="addAllergy()"
                                class="input-enterprise rounded-r-none w-full"
                                placeholder="Type allergy (e.g. Penicillin, Peanuts)..." />
                            <button
                                type="button"
                                @click="addAllergy()"
                                class="btn-enterprise-primary rounded-l-none">
                                Add +
                            </button>
                        </div>

                        <!-- The List of Added Allergies -->
                        <div class="flex flex-wrap gap-2 min-h-12 p-4 bg-slate-50 rounded-lg border border-slate-200">
                            <!-- Empty State -->
                            <div x-show="allergies.length === 0" class="text-gray-400 text-sm italic w-full text-center py-2">
                                No allergies recorded.
                            </div>

                            <!-- Allergy Tags -->
                            <template x-for="(allergy, index) in allergies" :key="index">
                                <div class="badge-enterprise bg-red-50 text-red-700 border border-red-100 gap-2 px-3 py-2 font-bold">
                                    <span x-text="allergy"></span>
                                    <button type="button" @click="removeAllergy(index)" class="hover:text-black transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-4 h-4 stroke-current">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>

                                    <!-- HIDDEN INPUT: This sends the array to Laravel -->
                                    <input type="hidden" name="known_allergies[]" :value="allergy">
                                </div>
                            </template>
                        </div>

                        <label class="label">
                            <span class="label-text-alt text-gray-500">Press Enter or Click Add to save an allergy.</span>
                        </label>
                    </div>
                </fieldset>

                <!-- 5. MEDICATION HISTORY -->
                <fieldset class="mb-8"
                    x-data="{
                                  medications: [],
                                  currentInput: '',
                                  addMedication() {
                                      if (this.currentInput.trim() !== '' && !this.medications.includes(this.currentInput.trim())) {
                                          this.medications.push(this.currentInput.trim());
                                          this.currentInput = '';
                                      }
                                  },
                                  removeMedication(index) {
                                      this.medications.splice(index, 1);
                                  }
                                }">
                    <legend class="text-lg font-bold text-center w-full text-slate-500 uppercase tracking-wide mb-6">
                        Medication History
                    </legend>

                    <div class="form-control w-full">
                        <!-- Input Group -->
                        <label class="label">
                            <span class="label-text font-bold">Add Medication</span>
                        </label>
                        <div class="flex w-full mb-4">
                            <input
                                type="text"
                                x-model="currentInput"
                                @keydown.enter.prevent="addMedication()"
                                class="input-enterprise rounded-r-none w-full"
                                placeholder="Type medication (e.g. Paracetamol 500mg, Ibuprofen)..." />
                            <button
                                type="button"
                                @click="addMedication()"
                                class="btn-enterprise-primary rounded-l-none">
                                Add +
                            </button>
                        </div>

                        <!-- The List of Added Medications -->
                        <div class="flex flex-wrap gap-2 min-h-12 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <!-- Empty State -->
                            <div x-show="medications.length === 0" class="text-gray-400 text-sm italic w-full text-center py-2">
                                No medications recorded.
                            </div>

                            <!-- Medication Tags -->
                            <template x-for="(medication, index) in medications" :key="index">
                                <div class="badge-enterprise bg-blue-50 text-blue-700 border border-blue-100 gap-2 px-3 py-2 font-bold">
                                    <span x-text="medication"></span>
                                    <button type="button" @click="removeMedication(index)" class="hover:text-black transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-4 h-4 stroke-current">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>

                                    <!-- HIDDEN INPUT: This sends the array to Laravel -->
                                    <input type="hidden" name="medication_history[]" :value="medication">
                                </div>
                            </template>
                        </div>

                        <label class="label">
                            <span class="label-text-alt text-gray-500">Press Enter or Click Add to save a medication.</span>
                        </label>
                    </div>
                </fieldset>

                <!-- 6. PAST MEDICAL HISTORY -->
                <fieldset class="mb-2"
                    x-data="{
                                  medicalHistory: [],
                                  currentForm: {
                                    type: '',
                                    description: '',
                                    date: ''
                                  },

                                  addMedicalRecord() {
                                    if (this.currentForm.type.trim() && this.currentForm.description.trim()) {
                                        this.medicalHistory.push({
                                            type: this.currentForm.type,
                                            description: this.currentForm.description,
                                            date: this.currentForm.date || 'Unknown'
                                        });
                                        this.resetForm();
                                    }
                                  },

                                  removeMedicalRecord(index) {
                                    this.medicalHistory.splice(index, 1);
                                  },

                                  resetForm() {
                                    this.currentForm = {
                                        type: '',
                                        description: '',
                                        date: ''
                                    };
                                  }
                                }">
                    <legend class="text-lg font-bold text-center w-full text-slate-500 uppercase tracking-wide mb-6">
                        Past Medical History
                    </legend>

                    <div class="form-control w-full mb-6">
                        <label class="label">
                            <span class="label-text font-bold">Condition Type</span>
                        </label>
                        <select x-model="currentForm.type" class="select-enterprise w-full mb-4">
                            <option value="" disabled selected>Select condition type</option>
                            <option value="Existing Illness">Existing Illness</option>
                            <option value="Past Hospitalization">Past Hospitalization</option>
                            <option value="Past Surgery">Past Surgery</option>
                            <option value="Hereditary Condition">Hereditary Condition</option>
                            <option value="Other">Other</option>
                        </select>

                        <label class="label">
                            <span class="label-text font-bold">Description / Details</span>
                        </label>
                        <textarea x-model="currentForm.description" class="textarea-enterprise w-full mb-4"
                            placeholder="Describe the condition, surgery, hospitalization, or hereditary disease..."></textarea>

                        <label class="label">
                            <span class="label-text font-bold">Date (Optional)</span>
                        </label>
                        <input type="date" x-model="currentForm.date" class="input-enterprise w-full mb-4" />

                        <button type="button" @click="addMedicalRecord()" class="btn-enterprise-primary w-full">
                            Add Medical Record
                        </button>
                    </div>

                    <!-- Table of Medical History Records -->
                    <div x-show="medicalHistory.length > 0" class="overflow-x-auto">
                        <table class="table table-sm table-zebra w-full bg-white border border-slate-200 rounded-lg">
                            <thead class="bg-slate-100 border-b border-slate-200">
                                <tr>
                                    <th class="text-left text-xs font-bold text-slate-700">Type</th>
                                    <th class="text-left text-xs font-bold text-slate-700">Description</th>
                                    <th class="text-left text-xs font-bold text-slate-700">Date</th>
                                    <th class="text-center text-xs font-bold text-slate-700">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(record, index) in medicalHistory" :key="index">
                                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                                        <td class="text-sm font-semibold text-slate-700" x-text="record.type"></td>
                                        <td class="text-sm text-slate-600" x-text="record.description"></td>
                                        <td class="text-sm text-slate-600" x-text="record.date"></td>
                                        <td class="text-center">
                                            <button type="button" @click="removeMedicalRecord(index)"
                                                class="btn btn-xs btn-error btn-outline">
                                                Remove
                                            </button>
                                        </td>

                                        <!-- HIDDEN INPUTS: This sends the data to Laravel -->
                                        <input type="hidden" name="past_medical_history_type[]" :value="record.type">
                                        <input type="hidden" name="past_medical_history_description[]" :value="record.description">
                                        <input type="hidden" name="past_medical_history_date[]" :value="record.date">
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty State -->
                    <div x-show="medicalHistory.length === 0" class="text-center p-6 bg-slate-50 rounded-lg border border-slate-200">
                        <p class="text-gray-400 text-sm italic">No medical history records added yet.</p>
                    </div>
                </fieldset>

            </div>
        </div>
    </div>


    <!-- STEP 2: DOCUMENTS -->
    <div x-show="step === 2" style="display: none;">
        <div class="card-enterprise">
            <div class="p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Required Documents</h3>
                <div class="flex items-center gap-3 p-3 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-800 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-amber-600 shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Please ensure scanned documents are clear. Allowed formats: JPG, PNG, PDF.</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- 1. Valid ID -->
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Valid Government ID</span></label>
                        <input type="file" name="doc_valid_id" class="file-input file-input-bordered w-full" accept=".jpg,.png,.pdf" />
                    </div>

                    <!-- 2. Insurance LOA -->
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Insurance/HMO LOA</span></label>
                        <input type="file" name="doc_loa" class="file-input file-input-bordered w-full" accept=".jpg,.png,.pdf" />
                    </div>

                    <!-- 3. Signed Consent Form -->
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Signed Admission Consent</span></label>
                        <input type="file" name="doc_consent" class="file-input file-input-bordered w-full" accept=".jpg,.png,.pdf" />
                    </div>
                    <!-- 3. Privacy Notice -->
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Signed Privacy Consent</span></label>
                        <input type="file" name="doc_privacy" class="file-input file-input-bordered w-full" accept=".jpg,.png,.pdf" />
                    </div>
                    <!-- 3. Privacy Notice -->
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">PhilHealth Member Data Record (MDR)</span></label>
                        <input type="file" name="doc_mdr" class="file-input file-input-bordered w-full" accept=".jpg,.png,.pdf" />
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- STEP 3: REVIEW -->
    <div x-show="step === 3" style="display: none;">
        <div class="card-enterprise">
            <div class="p-6">
                <div class="flex items-center gap-3 p-4 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Please review and ensure all information before submitting are CORRECTS. This will create the Patient Record, Admission Log, and Billing Account.</span>
                </div>
            </div>

        </div>
    </div>

    <div class="flex justify-between mt-6">
        <!-- Back Button -->
        <button type="button" class="btn-enterprise-secondary"
            x-show="step > 1"
            @click="step--; errorMessage = ''">
            Previous
        </button>
        <div x-show="step === 1"></div>

        <!-- Next Button -->
        <button type="button" class="btn-enterprise-primary"
            x-show="step < 3"
            @click="validateAndNext()">
            Next Step
        </button>

        <!-- Final Submit Button -->
        <button type="submit" class="btn-enterprise-danger"
            x-show="step === 3">
            Confirm & Admit Patient
        </button>
    </div>

</form>
</div>
@endsection
