<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class StoreIncidentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->nurse !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'admission_id' => 'nullable|exists:admissions,id',
            'time_of_incident' => 'required|date',
            'location_details' => 'nullable|string|max:255',
            'incident_category' => 'required|string|in:medication_error,patient_fall,equipment_malfunction,near_miss,wrong_documentation,other',
            'severity_level' => 'required|string|in:Low,Moderate,High,Severe',
            'narrative' => 'nullable|string|max:1000',
            'what_happened' => 'nullable|string|max:1000',
            'how_discovered' => 'nullable|string|max:1000',
            'action_taken' => 'nullable|string|max:1000',
            'injury' => 'nullable|boolean',
            'injury_type' => 'nullable|string|max:255',
            'vitals' => 'nullable|array',
            'vitals.temperature' => 'nullable|numeric',
            'vitals.bp' => 'nullable|string',
            'vitals.hr' => 'nullable|numeric',
            'vitals.pr' => 'nullable|numeric',
            'vitals.rr' => 'nullable|numeric',
            'vitals.o2' => 'nullable|numeric',
            'doctor_notified' => 'nullable|boolean',
            'family_notified' => 'nullable|boolean',
            'root_cause' => 'nullable|string|in:human_error,system_issue,equipment_failure,staffing_issue,other',
            'follow_up_actions' => 'nullable|string|max:255',
            'follow_up_instructions' => 'nullable|string|max:1000',
            'involved_staff' => 'nullable|array',
            'involved_staff.*' => 'exists:users,id',
            'witnesses' => 'nullable|array',
            'witnesses.*' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'time_of_incident.required' => 'Please specify when the incident occurred.',
            'time_of_incident.date_time' => 'Please provide a valid date and time.',
            'incident_category.required' => 'Please select an incident category.',
            'severity_level.required' => 'Please select a severity level.',
            'vitals.temperature.numeric' => 'Temperature must be a valid number.',
            'vitals.o2.numeric' => 'O2 saturation must be a valid number.',
        ];
    }
}
