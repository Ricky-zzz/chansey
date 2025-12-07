@extends('layouts.layout')

@section('content')
<div class="max-w-5xl mx-auto" x-data="{ step: 1 }">

    <!-- HEADER & WIZARD STEPS -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold mb-4">New Patient Admission</h2>
        <ul class="steps w-full">
            <li class="step" :class="step >= 1 ? 'step-primary' : ''">Demographics</li>
            <li class="step" :class="step >= 2 ? 'step-primary' : ''">Clinical Admission</li>
            <li class="step" :class="step >= 3 ? 'step-primary' : ''">Billing</li>
            <li class="step" :class="step >= 4 ? 'step-primary' : ''">Documents</li>
            <li class="step" :class="step >= 5 ? 'step-primary' : ''">Review & Submit</li>
        </ul>
    </div>

    <!-- THE ONE GIANT FORM -->
    <form action="{{ route('nurse.admitting.patients.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- STEP 1: PATIENT DEMOGRAPHICS -->
        <div x-show="step === 1" class="animate-fade-in w-full max-w-5xl mx-auto">
            <div class="card bg-base-100 shadow-xl border border-base-200">
                <div class="card-body p-8">

                    <!-- CENTERED MAIN TITLE -->
                    <h3 class="card-title text-3xl font-bold text-primary justify-center mb-8 border-b pb-4">
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
                                <input type="text" name="first_name" class="input input-md w-full" placeholder="First Name" required>
                            </label>
                            <label class="floating-label w-full">
                                <span>Last Name</span>
                                <input type="text" name="last_name" class="input input-md w-full" placeholder="Last Name" required>
                            </label>
                            <label class="floating-label w-full">
                                <span>Middle Name</span>
                                <input type="text" name="middle_name" class="input input-md w-full" placeholder="Middle Name" required>
                            </label>
                            <label class="floating-label w-full">
                                <span>Date of Birth</span>
                                <input type="date" name="date_of_birth" class="input input-md w-full" required>
                            </label>
                            <label class="floating-label w-full">
                                <span>Sex</span>
                                <select name="sex" class="select select-bordered w-full" required>
                                    <option value="" disabled selected>Select Sex</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </label>
                            <label class="floating-label w-full">
                                <span>Civil Status</span>
                                <select name="civil_status" class="select select-bordered w-full" required>
                                    <option value="" disabled selected>Select Status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widowed">Widowed</option>
                                    <option value="Separated">Separated</option>
                                </select>
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
                                <input type="text" name="nationality" class="input input-md w-full" placeholder="Nationality e.g. Filipino" required>
                            </label>
                            <label class="floating-label w-full">
                                <span>Religion</span>
                                <input type="text" name="religion" class="input input-md w-full" placeholder="Religion e.g. catholic" required>
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
                                <input type="text" name="address_permanent" class="input input-md w-full" placeholder="Permanent Address" required>
                            </label>
                            <label class="floating-label w-full">
                                <span>Present Address</span>
                                <input type="text" name="address_present" class="input input-md w-full" placeholder="Present Address" required>
                            </label>
                            <label class="floating-label w-full">
                                <span>Contact Number</span>
                                <input type="text" name="contact_number" class="input input-md w-full" placeholder="Contact Number" required>
                            </label>
                            <label class="floating-label w-full">
                                <span>Email Address</span>
                                <input type="email" name="email" class="input input-md w-full" placeholder="email@example.com" required>
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
                                <input type="text" name="emergency_contact_name" class="input input-md w-full" placeholder="Contact Full Name" required>
                            </label>
                            <label class="floating-label w-full">
                                <span>Relationship</span>
                                <input type="text" name="emergency_contact_relationship" class="input input-md w-full" placeholder="Relationship e.g. Spouse, Parent" required>
                            </label>
                            <label class="floating-label w-full md:col-span-2">
                                <span>Emergency Contact Number</span>
                                <input type="text" name="emergency_contact_number" class="input input-md w-full" placeholder="Emergency Contact Number" required>
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
            <div class="card bg-base-100 shadow-xl border border-base-200">
                <div class="card-body p-8">

                    <!-- CENTERED MAIN TITLE -->
                    <h3 class="card-title text-3xl font-bold text-primary justify-center mb-8 border-b pb-4">
                        Admission Details
                    </h3>

                    <!-- 1. ADMISSION INFO -->
                    <fieldset class="mb-8">
                        <legend class="text-lg font-bold text-center w-full text-slate-500 uppercase tracking-wide mb-6">
                            Admission Information
                        </legend>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <label class="floating-label w-full">
                                <span>Admission Type</span>
                                <select name="admission_type" class="select select-bordered w-full" required>
                                    <option value="" disabled selected>Select Admission Type</option>
                                    <option value="Emergency">Emergency</option>
                                    <option value="Outpatient">Outpatient</option>
                                    <option value="Inpatient">Inpatient</option>
                                    <option value="Transfer">Transfer</option>
                                </select>
                            </label>
                            <label class="floating-label w-full">
                                <span>Room and Bed Assignment</span>
                                <select name="bed_id" class="select select-bordered w-full" required>
                                    <option value="" disabled selected>Available Beds</option>
                                    @foreach($beds as $bed)
                                    <option value="{{ $bed->id }}">{{ $bed->bed_code }}</option>
                                    @endforeach
                                </select>
                            </label>

                            <label class="floating-label w-full">
                                <span>Attending Physician</span>
                                <select name="attending_physician_id" class="select select-bordered w-full" required>
                                    <option value="">Select an Attending Physician</option>
                                    @foreach($physicians as $doc)
                                    <option value="{{ $doc->id }}">Dr. {{ $doc->last_name }}, {{ $doc->first_name }} ({{ $doc->specialization }})</option>
                                    @endforeach
                                </select>
                            </label>

                            <label class="floating-label w-full">
                                <span>Case Type</span>
                                <select name="case_type" class="select select-bordered w-full" required>
                                    <option value="" disabled selected>Select Case Type</option>
                                    <option value="New Case">New Case</option>
                                    <option value="Returning">Returning</option>
                                    <option value="Follow-up">Follow-up</option>
                                </select>
                            </label>

                            <label class="floating-label w-full">
                                <span>Mode of Arrival</span>
                                <select name="mode_of_arrival" class="select select-bordered w-full" required>
                                    <option value="" disabled selected>Select Mode of Arrival</option>
                                    <option value="Walk-in">Walk-in</option>
                                    <option value="Ambulance">Ambulance</option>
                                    <option value="Wheelchair">Wheelchair</option>
                                    <option value="Stretcher">Stretcher</option>
                                </select>
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
                                <textarea name="chief_complaint" class="textarea textarea-bordered w-full"
                                    placeholder="Describe the patient's primary complaint"></textarea>
                            </label>

                            <label class="floating-label w-full">
                                <span>Initial Diagnosis</span>
                                <textarea name="initial_diagnosis" class="textarea textarea-bordered w-full"
                                    placeholder="Initial medical impression or suspected diagnosis"></textarea>
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
                                <span>Systolic Blood Pressure</span>
                                <input type="number" name="bp_systolic" class="input input-md w-full" placeholder="Systolic Blood Pressure e.g. 120">
                            </label>

                            <label class="floating-label w-full">
                                <span>Diastolic Blood Pressure</span>
                                <input type="number" name="bp_diastolic" class="input input-md w-full" placeholder="Diastolic Blood Pressure e.g. 80">
                            </label>

                            <label class="floating-label w-full">
                                <span>Pulse Rate (bpm)</span>
                                <input type="number" name="pulse_rate" class="input input-md w-full" placeholder="Pulse Rate (bpm)">
                            </label>

                            <label class="floating-label w-full">
                                <span>Respiratory Rate (breaths/min)</span>
                                <input type="number" name="respiratory_rate" class="input input-md w-full" placeholder="Respiratory Rate">
                            </label>

                            <label class="floating-label w-full">
                                <span>Oxygen Saturation (%)</span>
                                <input type="number" name="o2_sat" class="input input-md w-full" placeholder="Oxygen Saturation">
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
                            <div class="join w-full mb-4">
                                <input
                                    type="text"
                                    x-model="currentInput"
                                    @keydown.enter.prevent="addAllergy()"
                                    class="input input-bordered join-item w-full focus:outline-none"
                                    placeholder="Type allergy (e.g. Penicillin, Peanuts)..." />
                                <button
                                    type="button"
                                    @click="addAllergy()"
                                    class="btn btn-primary join-item text-white">
                                    Add +
                                </button>
                            </div>

                            <!-- The List of Added Allergies -->
                            <div class="flex flex-wrap gap-2 min-h-12 p-4 bg-base-200 rounded-lg border border-base-300">
                                <!-- Empty State -->
                                <div x-show="allergies.length === 0" class="text-gray-400 text-sm italic w-full text-center py-2">
                                    No allergies recorded.
                                </div>

                                <!-- Allergy Tags -->
                                <template x-for="(allergy, index) in allergies" :key="index">
                                    <div class="badge badge-error text-white gap-2 p-3 font-bold shadow-sm">
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


        <!-- STEP 3: BILLING -->
        <div x-show="step === 3" class="animate-fade-in w-full max-w-5xl mx-auto" style="display: none;">
            <div class="card bg-base-100 shadow-xl border border-base-200">
                <div class="card-body p-8">

                    <!-- MAIN TITLE -->
                    <h3 class="card-title text-3xl font-bold text-primary justify-center mb-8 border-b pb-4">
                        Financial Information
                    </h3>

                    <!-- 1. PAYMENT DETAILS -->
                    <fieldset class="mb-8">
                        <legend class="text-lg font-bold text-center w-full text-slate-500 uppercase tracking-wide mb-6">
                            Payment Details
                        </legend>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Payment Type -->
                            <label class="floating-label w-full">
                                <span>Payment Type</span>
                                <select name="payment_type" class="select select-bordered w-full" required>
                                    <option value="" disabled selected>Select Payment Type</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Insurance">Insurance</option>
                                    <option value="HMO">HMO</option>
                                    <option value="Company">Company</option>
                                </select>
                            </label>

                            <!-- Insurance Provider -->
                            <label class="floating-label w-full">
                                <span>Insurance Provider</span>
                                <input type="text" name="primary_insurance_provider"
                                    class="input input-md w-full"
                                    placeholder="Insurance Provider(If applicable)">
                            </label>

                            <!-- Policy Number -->
                            <label class="floating-label w-full">
                                <span>Policy Number</span>
                                <input type="text" name="policy_number"
                                    class="input input-md w-full"
                                    placeholder="Insurance Policy Number">
                            </label>

                            <!-- Approval Code -->
                            <label class="floating-label w-full">
                                <span>Approval Code</span>
                                <input type="text" name="approval_code"
                                    class="input input-md w-full"
                                    placeholder="Authorization / Approval Code">
                            </label>
                        </div>
                    </fieldset>

                    <!-- 2. GUARANTOR DETAILS -->
                    <fieldset class="mb-2">
                        <legend class="text-lg font-bold text-center w-full text-slate-500 uppercase tracking-wide mb-6">
                            Guarantor Information
                        </legend>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Guarantor Name -->
                            <label class="floating-label w-full">
                                <span>Guarantor Name</span>
                                <input type="text" name="guarantor_name"
                                    class="input input-md w-full"
                                    placeholder="Full Name of Guarantor">
                            </label>

                            <!-- Guarantor Relationship -->
                            <label class="floating-label w-full">
                                <span>Guarantor Relationship</span>
                                <input type="text" name="guarantor_relationship"
                                    class="input input-md w-full"
                                    placeholder="Relation e.g. Parent, Spouse, Guardian">
                            </label>

                            <!-- Contact -->
                            <label class="floating-label w-full md:col-span-2">
                                <span>Guarantor Contact Number</span>
                                <input type="text" name="guarantor_contact"
                                    class="input input-md w-full"
                                    placeholder="Contact Number of Guarantor">
                            </label>

                        </div>
                    </fieldset>

                </div>
            </div>
        </div>

        <!-- STEP 4: DOCUMENTS -->
        <div x-show="step === 4" style="display: none;">
            <div class="card bg-base-100 shadow border">
                <div class="card-body">
                    <h3 class="card-title text-primary">Required Documents</h3>
                    <div class="alert alert-warning text-sm mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
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


        <!-- STEP 5: REVIEW -->
        <div x-show="step === 5" style="display: none;">
            <div class="card bg-base-100 shadow border">
                <div class="card-body">
                    <div class="alert alert-warning">
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
            <button type="button" class="btn btn-neutral"
                x-show="step > 1"
                @click="step--">
                Previous
            </button>
            <div x-show="step === 1"></div> 

            <!-- Next Button -->
            <button type="button" class="btn btn-primary"
                x-show="step < 5"
                @click="step++">
                Next Step
            </button>

            <!-- Final Submit Button -->
            <button type="submit" class="btn btn-error"
                x-show="step === 5">
                Confirm & Admit Patient
            </button>
        </div>

    </form>
</div>
@endsection