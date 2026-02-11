<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Patient Report - {{ $admission->patient->last_name }}, {{ $admission->patient->first_name }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 5px 0; }
        .header p { margin: 2px 0; color: #666; }
        .section { margin: 15px 0; }
        .section-title { font-weight: bold; font-size: 13px; border-bottom: 1px solid #333; padding-bottom: 5px; margin-bottom: 10px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px; }
        .info-item { display: flex; flex-direction: column; }
        .info-label { font-weight: bold; font-size: 10px; color: #666; text-transform: uppercase; margin-bottom: 3px; }
        .info-value { font-size: 11px; color: #333; }
        .allergies-box { background: #ffe6e6; padding: 8px; border-left: 3px solid #cc0000; margin: 10px 0; }
        .allergies-label { font-weight: bold; color: #cc0000; font-size: 11px; margin-bottom: 3px; }
        .allergies-value { font-size: 11px; color: #333; }
        .admission-card { border: 1px solid #999; padding: 10px; margin-bottom: 10px; background: #fafafa; }
        .admission-card.active { background: #e8f5e9; border-left: 4px solid #4caf50; }
        .status-badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-weight: bold; font-size: 9px; margin-bottom: 8px; }
        .status-active { background: #4caf50; color: white; }
        .status-discharged { background: #999; color: white; }
        .admission-details { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 11px; }
        .detail { display: flex; flex-direction: column; }
        .detail-label { font-weight: bold; color: #555; margin-bottom: 2px; }
        .detail-value { color: #333; }
        .no-data { color: #999; font-style: italic; padding: 10px; text-align: center; background: #f9f9f9; border: 1px dashed #ddd; }
        .footer { margin-top: 20px; padding-top: 10px; border-top: 1px solid #ddd; font-size: 10px; color: #666; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>GOLDEN GATE ACADEMY</h2>
        <p>Patient Medical Report</p>
        <p>Generated: {{ now()->format('M d, Y H:i') }}</p>
    </div>

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
            <div class="allergies-label">⚠️ KNOWN ALLERGIES</div>
            <div class="allergies-value">{{ implode(', ', $admission->known_allergies) }}</div>
        </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">ADMISSION HISTORY</div>
        @if($allAdmissions->count() > 0)
            @foreach($allAdmissions as $adm)
            <div class="admission-card {{ $adm->status === 'Admitted' ? 'active' : '' }}">
                <div style="margin-bottom: 8px;">
                    <span class="status-badge {{ $adm->status === 'Admitted' ? 'status-active' : 'status-discharged' }}">
                        {{ $adm->status === 'Admitted' ? '● ACTIVE' : 'DISCHARGED' }}
                    </span>
                </div>
                <div class="admission-details">
                    <div class="detail">
                        <span class="detail-label">Admission Date</span>
                        <span class="detail-value">{{ $adm->formatted_admission_date }}</span>
                    </div>
                    <div class="detail">
                        <span class="detail-label">Discharge Date</span>
                        <span class="detail-value">
                            @if($adm->status === 'Admitted')
                            Ongoing
                            @else
                            {{ $adm->formatted_discharge_date }}
                            @endif
                        </span>
                    </div>
                    <div class="detail">
                        <span class="detail-label">Admission Type</span>
                        <span class="detail-value">{{ $adm->admission_type }}</span>
                    </div>
                    <div class="detail">
                        <span class="detail-label">Room/Bed</span>
                        <span class="detail-value">{{ $adm->bed?->bed_code ?? 'Outpatient' }}</span>
                    </div>
                    <div class="detail">
                        <span class="detail-label">Initial Diagnosis</span>
                        <span class="detail-value">{{ $adm->initial_diagnosis ?? 'Pending' }}</span>
                    </div>
                    <div class="detail">
                        <span class="detail-label">Chief Complaint</span>
                        <span class="detail-value">{{ $adm->chief_complaint ?? 'N/A' }}</span>
                    </div>
                    <div class="detail">
                        <span class="detail-label">Attending Physician</span>
                        <span class="detail-value">{{ $adm->physician?->user?->name ?? 'N/A' }}</span>
                    </div>
                    <div class="detail">
                        <span class="detail-label">Status</span>
                        <span class="detail-value">{{ $adm->status }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        @else
        <div class="no-data">No admission history found.</div>
        @endif
    </div>

    <div class="footer">
        <p>This is an official medical report. For inquiries, contact hospital administration.</p>
        <p>Report generated on {{ now()->format('M d, Y \a\t H:i:s') }}</p>
    </div>
</body>
</html>
