<?php

namespace App\Http\Requests;

use App\Models\Bed;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // A. Patient Demographics
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'date_of_birth' => 'required|date',
            'sex' => 'required|in:Male,Female',
            'civil_status' => 'required',
            'nationality' => 'required|string',
            'religion' => 'nullable|string',
            'address_permanent' => 'required|string',
            'address_present' => 'nullable|string',
            'contact_number' => 'required|string',
            'email' => 'nullable|email',

            // B. Emergency Contact
            'emergency_contact_name' => 'required|string',
            'emergency_contact_relationship' => 'required|string',
            'emergency_contact_number' => 'required|string',

            // C. IDs
            'philhealth_number' => 'nullable|string',
            'senior_citizen_id' => 'nullable|string',

            // D. Admission Details
            'admission_type' => 'required',
            'station_id' => 'required|exists:stations,id',

            // BED_ID: ONLY REQUIRED IF NOT OUTPATIENT
            'bed_id' => [
                Rule::requiredIf($this->input('admission_type') !== 'Outpatient'),
                Rule::exists(Bed::class, 'id')->where('status', 'Available')
            ],

            'attending_physician_id' => 'required|exists:physicians,id',
            'case_type' => 'required',
            'mode_of_arrival' => 'required',
            'chief_complaint' => 'required|string',
            'initial_diagnosis' => 'nullable|string',

            // E. Vitals 
            'temp' => 'nullable|numeric',
            'bp_systolic' => 'nullable|integer',
            'bp_diastolic' => 'nullable|integer',
            'pulse_rate' => 'nullable|integer',
            'respiratory_rate' => 'nullable|integer',
            'o2_sat' => 'nullable|integer',
            'known_allergies' => 'nullable|array',

            // G. Financials
            'payment_type' => 'required|string',
            'primary_insurance_provider' => 'nullable|string',
            'policy_number' => 'nullable|string',
            'approval_code' => 'nullable|string',
            'guarantor_name' => 'nullable|string',
            'guarantor_relationship' => 'nullable|string',
            'guarantor_contact' => 'nullable|string',

            // H. Files
            'doc_valid_id' => 'nullable|file|mimes:jpg,png,pdf|max:5120',
            'doc_loa' => 'nullable|file|mimes:jpg,png,pdf|max:5120',
            'doc_consent' => 'nullable|file|mimes:jpg,png,pdf|max:5120',
            'doc_privacy' => 'nullable|file|mimes:jpg,png,pdf|max:5120',
            'doc_mdr' => 'nullable|file|mimes:jpg,png,pdf|max:5120',
        ];
    }
}
