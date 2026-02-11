<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admission Report - {{ $admission->admission_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #333; padding-bottom: 8px; }
        .header h2 { margin: 3px 0; }
        .header p { margin: 2px 0; color: #666; }
        .section { margin: 12px 0; }
        .section-title { font-weight: bold; font-size: 12px; border-bottom: 1px solid #333; padding-bottom: 4px; margin-bottom: 8px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 8px; }
        .info-item { display: flex; flex-direction: column; }
        .info-label { font-weight: bold; font-size: 9px; color: #666; text-transform: uppercase; margin-bottom: 2px; }
        .info-value { font-size: 10px; color: #333; }
        .vitals-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 6px; }
        .vital-item { padding: 6px; background: #f5f5f5; border: 1px solid #ddd; text-align: center; border-radius: 3px; }
        .vital-label { font-weight: bold; font-size: 8px; color: #666; }
        .vital-value { font-weight: bold; font-size: 11px; color: #333; margin-top: 2px; }
        .allergies-box { background: #ffe6e6; padding: 6px; border-left: 3px solid #cc0000; margin: 8px 0; }
        .allergies-label { font-weight: bold; color: #cc0000; font-size: 10px; margin-bottom: 2px; }
        .allergies-value { font-size: 10px; color: #333; }
        .detail-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 6px; }
        .detail-item { display: flex; flex-direction: column; }
        .detail-label { font-weight: bold; color: #555; font-size: 9px; margin-bottom: 2px; }
        .detail-value { font-size: 10px; color: #333; }
        .billing-box { background: #f0f8ff; padding: 8px; border-left: 3px solid #4169e1; margin: 8px 0; }
        .billing-label { font-weight: bold; color: #4169e1; font-size: 10px; margin-bottom: 3px; }
        .billing-item { font-size: 9px; margin: 2px 0; }
        .status-badge { display: inline-block; padding: 2px 6px; border-radius: 2px; font-weight: bold; font-size: 8px; margin-top: 2px; }
        .status-admitted { background: #4caf50; color: white; }
        .status-discharged { background: #999; color: white; }
        .footer { margin-top: 12px; padding-top: 8px; border-top: 1px solid #ddd; font-size: 9px; color: #666; text-align: center; }
        .divider { height: 1px; background: #ccc; margin: 8px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h2>GOLDEN GATE ACADEMY</h2>
        <p>Detailed Admission Report</p>
        <p>Generated: {{ now()->format('M d, Y H:i') }}</p>
    </div>

    <!-- PATIENT INFORMATION SECTION -->
    <div class="section">
        <div class="section-title">PATIENT INFORMATION</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Patient Name</span>
                <span class="info-value">{{ $admission->patient->last_name }}, {{ $admission->patient->first_name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Patient ID</span>
                <span class="info-value">{{ $admission->patient->patient_id ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Age / Sex</span>
                <span class="info-value">{{ $admission->patient->age }} years / {{ $admission->patient->sex }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Date of Birth</span>
                <span class="info-value">{{ $admission->patient->formatted_date_of_birth }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Contact</span>
                <span class="info-value">{{ $admission->patient->contact_number ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Address</span>
                <span class="info-value">{{ $admission->patient->address ?? 'N/A' }}</span>
            </div>
        </div>

        @if(!empty($admission->known_allergies))
        <div class="allergies-box">
            <div class="allergies-label"> KNOWN ALLERGIES</div>
            <div class="allergies-value">{{ implode(', ', $admission->known_allergies) }}</div>
        </div>
        @endif
    </div>

    <div class="divider"></div>

    <!-- ADMISSION DETAILS SECTION -->
    <div class="section">
        <div class="section-title">ADMISSION DETAILS</div>
        <div class="detail-row">
            <div class="detail-item">
                <span class="detail-label">Admission Number</span>
                <span class="detail-value">{{ $admission->admission_number }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Status</span>
                <span class="status-badge {{ $admission->status === 'Admitted' ? 'status-admitted' : 'status-discharged' }}">
                    {{ $admission->status }}
                </span>
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-item">
                <span class="detail-label">Admission Date & Time</span>
                <span class="detail-value">{{ $admission->formatted_admission_date }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Admission Type</span>
                <span class="detail-value">{{ $admission->admission_type }}</span>
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-item">
                <span class="detail-label">Mode of Arrival</span>
                <span class="detail-value">{{ $admission->mode_of_arrival ?? 'N/A' }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Case Type</span>
                <span class="detail-value">{{ $admission->case_type ?? 'N/A' }}</span>
            </div>
        </div>

        @if($admission->status !== 'Admitted')
        <div class="detail-row">
            <div class="detail-item">
                <span class="detail-label">Discharge Date & Time</span>
                <span class="detail-value">{{ $admission->formatted_discharge_date }}</span>
            </div>
        </div>
        @endif
    </div>

    <!-- CLINICAL INFORMATION SECTION -->
    <div class="section">
        <div class="section-title">CLINICAL INFORMATION</div>

        <div style="margin-bottom: 8px;">
            <span class="detail-label">Chief Complaint</span>
            <span class="detail-value" style="display: block; padding: 4px; background: #f9f9f9; margin-top: 2px; border-left: 2px solid #4169e1;">{{ $admission->chief_complaint ?? 'N/A' }}</span>
        </div>

        <div style="margin-bottom: 8px;">
            <span class="detail-label">Initial Diagnosis</span>
            <span class="detail-value" style="display: block; padding: 4px; background: #f9f9f9; margin-top: 2px; border-left: 2px solid #4169e1;">{{ $admission->initial_diagnosis ?? 'Pending' }}</span>
        </div>
    </div>

    <!-- VITAL SIGNS SECTION -->
    @if(!empty($admission->initial_vitals))
    <div class="section">
        <div class="section-title">INITIAL VITAL SIGNS</div>
        <div class="vitals-grid">
            <div class="vital-item">
                <div class="vital-label">Temperature</div>
                <div class="vital-value">{{ $admission->initial_vitals['temp'] ?? '--' }}°C</div>
            </div>
            <div class="vital-item">
                <div class="vital-label">Blood Pressure</div>
                <div class="vital-value">{{ $admission->initial_vitals['bp'] ?? '--' }}</div>
            </div>
            <div class="vital-item">
                <div class="vital-label">Heart Rate</div>
                <div class="vital-value">{{ $admission->initial_vitals['hr'] ?? '--' }} bpm</div>
            </div>
            <div class="vital-item">
                <div class="vital-label">Pulse Rate</div>
                <div class="vital-value">{{ $admission->initial_vitals['pr'] ?? '--' }} bpm</div>
            </div>
            <div class="vital-item">
                <div class="vital-label">Respiratory Rate</div>
                <div class="vital-value">{{ $admission->initial_vitals['rr'] ?? '--' }} breaths/min</div>
            </div>
            <div class="vital-item">
                <div class="vital-label">O2 Saturation</div>
                <div class="vital-value">{{ $admission->initial_vitals['o2'] ?? '--' }}%</div>
            </div>
        </div>
    </div>
    @endif

    <div class="divider"></div>

    <!-- LOCATION & PERSONNEL SECTION -->
    <div class="section">
        <div class="section-title">LOCATION & MEDICAL PERSONNEL</div>

        <div class="detail-row">
            <div class="detail-item">
                <span class="detail-label">Station</span>
                <span class="detail-value">{{ $admission->station->station_name ?? 'N/A' }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Room / Bed</span>
                <span class="detail-value">{{ $admission->bed?->bed_code ?? 'Outpatient / Waiting' }}</span>
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-item">
                <span class="detail-label">Attending Physician</span>
                <span class="detail-value">
                    Dr. {{ $admission->attendingPhysician->last_name ?? 'N/A' }}, {{ $admission->attendingPhysician->first_name ?? 'N/A' }}
                    @if($admission->attendingPhysician->specialization)
                    <br><span style="font-size: 9px; color: #666;">{{ $admission->attendingPhysician->specialization }}</span>
                    @endif
                </span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Admitting Clerk</span>
                <span class="detail-value">{{ $admission->admittingClerk->name ?? 'N/A' }}</span>
            </div>
        </div>
    </div>

    <!-- BILLING INFORMATION SECTION -->
    @if($admission->billingInfo)
    <div class="section">
        <div class="billing-box">
            <div class="billing-label">BILLING INFORMATION</div>
            <div class="billing-item"><strong>Payment Method:</strong> {{ $admission->billingInfo->payment_type }}</div>
            @if($admission->billingInfo->primary_insurance_provider)
            <div class="billing-item"><strong>Insurance Provider:</strong> {{ $admission->billingInfo->primary_insurance_provider }}</div>
            <div class="billing-item"><strong>Policy Number:</strong> {{ $admission->billingInfo->policy_number }}</div>
            <div class="billing-item"><strong>Approval Code:</strong> {{ $admission->billingInfo->approval_code ?? 'Pending' }}</div>
            @endif
            @if($admission->billingInfo->guarantor_name)
            <div class="billing-item"><strong>Guarantor:</strong> {{ $admission->billingInfo->guarantor_name }} ({{ $admission->billingInfo->guarantor_relationship ?? 'Relation' }})</div>
            <div class="billing-item"><strong>Guarantor Contact:</strong> {{ $admission->billingInfo->guarantor_contact ?? 'N/A' }}</div>
            @endif
        </div>
    </div>
    @endif

    <div class="divider"></div>

    <!-- AUDIT TRAIL SECTION -->
    <div class="section">
        <div class="section-title">AUDIT INFORMATION</div>
        <div class="detail-row">
            <div class="detail-item">
                <span class="detail-label">Admitted By</span>
                <span class="detail-value">{{ $admission->admittingClerk->name ?? 'Unknown' }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Admission Date</span>
                <span class="detail-value">{{ $admission->formatted_created_at }}</span>
            </div>
        </div>
        @if($admission->updated_at !== $admission->created_at)
        <div class="detail-row">
            <div class="detail-item">
                <span class="detail-label">Last Updated</span>
                <span class="detail-value">{{ $admission->formatted_updated_at }}</span>
            </div>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>This is an official medical record. For inquiries, contact hospital administration.</p>
        <p>Report generated on {{ now()->format('M d, Y \a\t H:i:s') }}</p>
    </div>
</body>
</html>
