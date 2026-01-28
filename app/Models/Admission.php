<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use \Illuminate\Support\Str;

class Admission extends Model
{
    protected $fillable = [
        'patient_id',
        'admission_number',
        'station_id',
        'bed_id',
        'attending_physician_id',
        'admitting_clerk_id',
        'admission_date',
        'discharge_date',
        'admission_type',
        'case_type',
        'status',
        'chief_complaint',
        'initial_diagnosis',
        'mode_of_arrival',
        'initial_vitals',
        'known_allergies',
    ];

    protected $casts = [
        'admission_date' => 'datetime',
        'discharge_date' => 'datetime',
        'known_allergies' => 'array',
        'initial_vitals' => 'array',
    ];

    // --- RELATIONSHIPS ---

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function bed(): BelongsTo
    {
        return $this->belongsTo(Bed::class);
    }

    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }

    public function attendingPhysician(): BelongsTo
    {
        return $this->belongsTo(Physician::class, 'attending_physician_id');
    }

    public function admittingClerk(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admitting_clerk_id');
    }

    public function billingInfo(): HasOne
    {
        return $this->hasOne(AdmissionBillingInfo::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(PatientFile::class);
    }

    public function treatmentPlan(): HasOne
    {
        return $this->hasOne(TreatmentPlan::class);
    }

    public function medicalOrders(): HasMany
    {
        return $this->hasMany(MedicalOrder::class);
    }

    public function clinicalLogs(): HasMany
    {
        return $this->hasMany(ClinicalLog::class);
    }

    public function billableItems(): HasMany
    {
        return $this->hasMany(BillableItem::class);
    }

    public function nursingCarePlans(): HasOne
    {
        return $this->hasOne(NursingCarePlan::class);
    }

    public function transferRequests(): HasMany
    {
        return $this->hasMany(TransferRequest::class);
    }

    public function patientMovements(): HasMany
    {
        return $this->hasMany(PatientMovement::class);
    }

    public function billings(): HasMany
    {
        return $this->hasMany(Billing::class);
    }

    // --- ACCESSOR METHODS ---

    public function truncatedChiefComplaint(int $limit = 20): string
    {
        return Str::limit($this->chief_complaint, $limit, '...');
    }
    
    // BMI Calculation
    public function getBmiAttribute()
    {
        $vitals = $this->initial_vitals;

        if (empty($vitals['height']) || empty($vitals['weight'])) {
            return 'N/A';
        }

        $h_meters = $vitals['height'] / 100; 
        $w_kg = $vitals['weight'];

        if ($h_meters <= 0) return 'N/A';

        $bmi = $w_kg / ($h_meters * $h_meters);

        return number_format($bmi, 2);
    }

    public function getBmiCategoryAttribute()
    {
        $bmi = $this->bmi;
        if ($bmi === 'N/A') return '';

        if ($bmi < 18.5) return '(Underweight)';
        if ($bmi < 25) return '(Normal)';
        if ($bmi < 30) return '(Overweight)';
        return '(Obese)';
    }
}
