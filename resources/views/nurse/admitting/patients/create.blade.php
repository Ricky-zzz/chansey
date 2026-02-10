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

    <!-- HEADER & WIZARD STEPS -->
    <div class=" mb-8">
    <h2 class="text-xl font-bold text-slate-800 mb-4">New Patient Admission</h2>

    <ul class="steps w-full">
        <li class="step" :class="step >= 1 ? 'step-primary' : ''">Demographics</li>
        <li class="step" :class="step >= 2 ? 'step-primary' : ''">Clinical Admission</li>
        <li class="step" :class="step >= 3 ? 'step-primary' : ''">Documents</li>
        <li class="step" :class="step >= 4 ? 'step-primary' : ''">Review & Submit</li>
    </ul>
</div>

<!-- THE ONE GIANT FORM -->
<form action="{{ route('nurse.admitting.patients.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- STEP 1: PATIENT DEMOGRAPHICS -->
    <div x-show="step === 1" class="animate-fade-in w-full max-w-5xl mx-auto">
        <div class="card-enterprise">
            <div class="p-8">

                <!-- ERROR MESSAGE DISPLAY -->
                <div x-show="step === 1 && errorMessage" class="flex items-center gap-3 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-red-600 shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-text="errorMessage"></span>
                </div>

                <!-- CENTERED MAIN TITLE -->
                <h3 class="text-2xl font-bold text-slate-800 text-center mb-8 border-b border-slate-200 pb-4">
                    Patient Information
                </h3>

                <!-- 1. BASIC INFORMATION -->
                <fieldset class="mb-8">
                    <!-- CENTERED LEGEND -->
                    <legend class="text-lg font-bold text-center w-full text-slate-500 uppercase tracking-wide mb-6">
                        Basic Information
                    </legend>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <label class="floating-label w-full">
                            <span>First Name</span>
                            <input type="text" name="first_name" data-step="1" class="input input-md w-full @error('first_name') input-error @enderror" placeholder="First Name" value="{{ old('first_name', $prefillData['first_name'] ?? '') }}" required>
                            @error('first_name')
                                <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                            @enderror
                        </label>
                        <label class="floating-label w-full">
                            <span>Last Name</span>
                            <input type="text" name="last_name" data-step="1" class="input input-md w-full @error('last_name') input-error @enderror" placeholder="Last Name" value="{{ old('last_name', $prefillData['last_name'] ?? '') }}" required>
                            @error('last_name')
                                <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                            @enderror
                        </label>
                        <label class="floating-label w-full">
                            <span>Middle Name</span>
                            <input type="text" name="middle_name" data-step="1" class="input input-md w-full @error('middle_name') input-error @enderror" placeholder="Middle Name" required>
                            @error('middle_name')
                                <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                            @enderror
                        </label>
                        <label class="floating-label w-full">
                            <span>Date of Birth</span>
                            <input type="date" name="date_of_birth" data-step="1" class="input input-md w-full @error('date_of_birth') input-error @enderror" required>
                            @error('date_of_birth')
                                <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                            @enderror
                        </label>
                        <label class="floating-label w-full">
                            <span>Sex</span>
                            <select name="sex" data-step="1" class="select-enterprise w-full @error('sex') select-error @enderror" required>
                                <option value="" disabled selected>Select Sex</option>
                                <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('sex')
                                <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                            @enderror
                        </label>
                        <label class="floating-label w-full">
                            <span>Civil Status</span>
                            <select name="civil_status" data-step="1" class="select-enterprise w-full @error('civil_status') select-error @enderror" required>
                                <option value="" disabled selected>Select Status</option>
                                <option value="Single" {{ old('civil_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ old('civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Widowed" {{ old('civil_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                <option value="Separated" {{ old('civil_status') == 'Separated' ? 'selected' : '' }}>Separated</option>
                            </select>
                            @error('civil_status')
                                <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                            @enderror
                        </label>
                    </div>
                </fieldset>

                <!-- 2. PERSONAL DETAILS -->
                <fieldset class="mb-8">
                    <legend class="text-lg font-bold text-center w-full text-slate-500 uppercase tracking-wide mb-6">
                        Personal Details
                    </legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <label class="floating-label w-full">
                            <span>Nationality</span>
                            <input type="text" name="nationality" data-step="1" class="input input-md w-full @error('nationality') input-error @enderror" placeholder="Nationality e.g. Filipino" required>
                            @error('nationality')
                                <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                            @enderror
                        </label>
                        <label class="floating-label w-full">
                            <span>Religion</span>
                            <input type="text" name="religion" data-step="1" class="input input-md w-full @error('religion') input-error @enderror" placeholder="Religion e.g. catholic" required>
                            @error('religion')
                                <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                            @enderror
                        </label>
                    </div>
                </fieldset>

                <!-- 3. CONTACT INFORMATION -->
                <fieldset class="mb-8">
                    <legend class="text-lg font-bold text-center w-full text-slate-500 uppercase tracking-wide mb-6">
                        Contact Information
                    </legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <label class="floating-label w-full">
                            <span>Permanent Address</span>
                            <input type="text" name="address_permanent" data-step="1" class="input input-md w-full" placeholder="Permanent Address" required>
                        </label>
                        <label class="floating-label w-full">
                            <span>Present Address</span>
                            <input type="text" name="address_present" data-step="1" class="input input-md w-full" placeholder="Present Address" required>
                        </label>
                        <label class="floating-label w-full">
                            <span>Contact Number</span>
                            <input type="text" name="contact_number" data-step="1" class="input input-md w-full" placeholder="Contact Number" value="{{ old('contact_number', $prefillData['contact_number'] ?? '') }}" required>
                        </label>
                        <label class="floating-label w-full">
                            <span>Email Address</span>
                            <input type="text" name="email" data-step="1" class="input input-md w-full" placeholder="email@example.com" value="{{ old('email', $prefillData['email'] ?? '') }}" required>
                        </label>
                    </div>
                </fieldset>

                <!-- 4. EMERGENCY CONTACT -->
                <fieldset class="mb-8">
                    <legend class="text-lg font-bold text-center w-full text-slate-500 uppercase tracking-wide mb-6">
                        Emergency Contact
                    </legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <label class="floating-label w-full">
                            <span>Contact Name</span>
                            <input type="text" name="emergency_contact_name" data-step="1" class="input input-md w-full" placeholder="Contact Full Name" required>
                        </label>
                        <label class="floating-label w-full">
                            <span>Relationship</span>
                            <input type="text" name="emergency_contact_relationship" data-step="1" class="input input-md w-full" placeholder="Relationship e.g. Spouse, Parent" required>
                        </label>
                        <label class="floating-label w-full md:col-span-2">
                            <span>Emergency Contact Number</span>
                            <input type="text" name="emergency_contact_number" data-step="1" class="input input-md w-full" placeholder="Emergency Contact Number" required>
                        </label>
                    </div>
                </fieldset>

                <!-- 5. INSURANCE & ID -->
                <fieldset class="mb-2">
                    <legend class="text-lg font-bold text-center w-full text-slate-500 uppercase tracking-wide mb-6">
                        Insurance & Identification
                    </legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <label class="floating-label w-full">
                            <span>PhilHealth Number</span>
                            <input type="text" name="philhealth_number" class="input input-md w-full" placeholder="PhilHealth Number">
                        </label>
                        <label class="floating-label w-full">
                            <span>Senior Citizen ID</span>
                            <input type="text" name="senior_citizen_id" class="input input-md w-full" placeholder="Senior Citizen ID: Optional">
                        </label>
                    </div>
                </fieldset>

            </div>
        </div>
    </div>

    <!-- STEP 2: ADMISSION DETAILS -->
    <div x-show="step === 2" class="animate-fade-in w-full max-w-5xl mx-auto" style="display: none;">
        <div class="card-enterprise">
            <div class="p-8">

                <!-- ERROR MESSAGE DISPLAY -->
                <div x-show="step === 2 && errorMessage" class="flex items-center gap-3 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-red-600 shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-text="errorMessage"></span>
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
                            <select name="station_id" data-step="2" x-model="selectedStation" class="select-enterprise w-full @error('station_id') select-error @enderror" required>
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
                            <select name="bed_id" data-step="2" class="select-enterprise w-full @error('bed_id') select-error @enderror" :disabled="!selectedStation">
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
                                <select name="admission_type" data-step="2" x-model="admissionType" class="select-enterprise w-full @error('admission_type') select-error @enderror" required>
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
                                <select name="attending_physician_id" data-step="2" class="select-enterprise w-full @error('attending_physician_id') select-error @enderror" required>
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
                                <select name="case_type" data-step="2" class="select-enterprise w-full @error('case_type') select-error @enderror" required>
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
                                <select name="mode_of_arrival" data-step="2" class="select-enterprise w-full @error('mode_of_arrival') select-error @enderror" required>
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
                            <textarea name="chief_complaint" data-step="2" class="textarea-enterprise w-full @error('chief_complaint') textarea-error @enderror"
                                placeholder="Describe the patient's primary complaint" required>{{ old('chief_complaint') }}</textarea>
                            @error('chief_complaint')
                                <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                            @enderror
                        </label>

                        <label class="floating-label w-full">
                            <span>Initial Diagnosis</span>
                            <textarea name="initial_diagnosis" class="textarea-enterprise w-full @error('initial_diagnosis') textarea-error @enderror"
                                placeholder="Initial medical impression or suspected diagnosis">{{ old('initial_diagnosis') }}</textarea>
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

            </div>
        </div>
    </div>


    <!-- STEP 3: DOCUMENTS -->
    <div x-show="step === 3" style="display: none;">
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
                    <!-- 4. philhealth memebr -->
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">PhilHealth Member Data Record (MDR)</span></label>
                        <input type="file" name="doc_mdr" class="file-input file-input-bordered w-full" accept=".jpg,.png,.pdf" />
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- STEP 4: REVIEW -->
    <div x-show="step === 4" style="display: none;">
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
            x-show="step < 4"
            @click="validateAndNext()">
            Next Step
        </button>

        <!-- Final Submit Button -->
        <button type="submit" class="btn-enterprise-danger"
            x-show="step === 4">
            Confirm & Admit Patient
        </button>
    </div>

</form>
</div>
@endsection
