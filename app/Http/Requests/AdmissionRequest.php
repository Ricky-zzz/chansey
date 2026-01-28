<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Bed;

class AdmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'admission_type' => 'required|string|in:Emergency,Outpatient,Inpatient,Transfer',
            'attending_physician_id' => 'required|exists:physicians,id',
            'case_type' => 'required|string',
            'mode_of_arrival' => 'required|string',
            'chief_complaint' => 'required|string',
            'initial_diagnosis' => 'nullable|string',
            
            // Vitals
            'temp' => 'nullable|numeric',
            'bp_systolic' => 'nullable|integer',
            'bp_diastolic' => 'nullable|integer',
            'pulse_rate' => 'nullable|integer',
            'respiratory_rate' => 'nullable|integer',
            'o2_sat' => 'nullable|integer',
            'known_allergies' => 'nullable|array',


            'doc_valid_id' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_loa' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_consent' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_privacy' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_mdr' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',

            
        ];

        if ($this->isMethod('post')) {
            $rules['station_id'] = 'required|exists:stations,id';
            
            $rules['bed_id'] = [
                Rule::requiredIf($this->input('admission_type') !== 'Outpatient'),
                Rule::exists('beds', 'id')->where('status', 'Available')
            ];
        }

        return $rules;
    }
}