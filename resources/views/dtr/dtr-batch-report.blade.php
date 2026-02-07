<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Batch DTR Report - {{ $dateFromFormattedShort }} to {{ $dateToFormattedShort }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; line-height: 1.4; margin: 0; padding: 0; }
        .page-break { page-break-after: always; }

        /* Cover Page */
        .cover { padding: 40px 20px; text-align: center; }
        .cover h1 { margin: 0; font-size: 22px; }
        .cover h2 { margin: 6px 0 0; font-size: 16px; color: #333; }
        .cover .subtitle { margin: 15px 0 5px; font-size: 12px; color: #666; }
        .cover .date-range { font-size: 14px; font-weight: bold; color: #4169e1; margin-bottom: 25px; }
        .cover .meta { font-size: 10px; color: #888; margin-top: 5px; }
        .roster-table { width: 80%; margin: 0 auto; border-collapse: collapse; }
        .roster-table th, .roster-table td { border: 1px solid #ccc; padding: 6px 10px; text-align: left; font-size: 10px; }
        .roster-table th { background: #e8e8e8; font-size: 9px; text-transform: uppercase; font-weight: bold; }
        .roster-table tr:nth-child(even) { background: #f9f9f9; }
        .cover-section-title { font-size: 12px; font-weight: bold; text-align: left; width: 80%; margin: 20px auto 8px; border-bottom: 1px solid #333; padding-bottom: 4px; }

        /* Individual Report Styles (same as single report) */
        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #333; padding-bottom: 8px; }
        .header h2 { margin: 3px 0; }
        .header p { margin: 2px 0; color: #666; }
        .header h3 { margin: 5px 0; font-size: 13px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 12px; }
        .info-item { display: flex; flex-direction: column; }
        .info-label { font-weight: bold; font-size: 9px; color: #666; text-transform: uppercase; margin-bottom: 2px; }
        .info-value { font-size: 10px; color: #333; }
        .section { margin: 12px 0; }
        .section-title { font-weight: bold; font-size: 12px; border-bottom: 1px solid #333; padding-bottom: 4px; margin-bottom: 8px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .table th, .table td { border: 1px solid #ccc; padding: 5px 8px; text-align: left; font-size: 10px; }
        .table th { background: #e8e8e8; font-weight: bold; font-size: 9px; text-transform: uppercase; }
        .table tr:nth-child(even) { background: #f9f9f9; }
        .status-present { color: #2e7d32; font-weight: bold; }
        .status-late { color: #e65100; font-weight: bold; }
        .status-incomplete { color: #c62828; font-weight: bold; }
        .status-ongoing { color: #1565c0; font-weight: bold; }
        .summary-grid { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 8px; margin: 12px 0; }
        .summary-box { padding: 8px; border: 1px solid #ddd; text-align: center; border-radius: 3px; }
        .summary-box .label { font-weight: bold; font-size: 8px; color: #666; text-transform: uppercase; }
        .summary-box .value { font-weight: bold; font-size: 14px; color: #333; margin-top: 3px; }
        .schedule-box { background: #f0f8ff; padding: 8px; border-left: 3px solid #4169e1; margin-bottom: 12px; }
        .schedule-label { font-weight: bold; color: #4169e1; font-size: 10px; margin-bottom: 3px; }
        .schedule-item { font-size: 9px; margin: 2px 0; }
        .overtime { color: #6a1b9a; font-weight: bold; }
        .deficit { color: #c62828; font-weight: bold; }
        .footer { margin-top: 15px; padding-top: 8px; border-top: 1px solid #ddd; font-size: 9px; color: #666; text-align: center; }
        .divider { height: 1px; background: #ccc; margin: 10px 0; }
        .signature-area { margin-top: 40px; display: grid; grid-template-columns: 1fr 1fr; gap: 60px; }
        .signature-line { border-top: 1px solid #333; padding-top: 5px; text-align: center; font-size: 9px; color: #555; }
        .nurse-number { position: absolute; top: 10px; right: 15px; font-size: 9px; color: #999; }
    </style>
</head>
<body>
    {{-- COVER / SUMMARY PAGE --}}
    <div class="cover">
        <h1>CHANSEY HOSPITAL</h1>
        <h2>Batch Daily Time Record Report</h2>
        <div class="subtitle">Reporting Period</div>
        <div class="date-range">{{ $dateFromFormatted }} — {{ $dateToFormatted }}</div>
        <div class="meta">Total Nurses: {{ count($allReports) }}</div>
        <div class="meta">Generated on: {{ now()->format('M d, Y \a\t H:i:s') }}</div>

        <div class="cover-section-title">NURSE ROSTER SUMMARY</div>
        <table class="roster-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 30%;">Name</th>
                    <th style="width: 15%;">Designation</th>
                    <th style="width: 12%;">Days Worked</th>
                    <th style="width: 12%;">Hours Worked</th>
                    <th style="width: 13%;">Expected Hours</th>
                    <th style="width: 13%;">Overtime / Deficit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allReports as $index => $report)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $report['nurse']->last_name }}, {{ $report['nurse']->first_name }}</td>
                    <td>{{ $report['nurse']->designation }}</td>
                    <td style="text-align: center;">{{ $report['summary']['totalRecords'] }}</td>
                    <td style="text-align: center;">{{ number_format($report['summary']['totalHours'], 2) }}h</td>
                    <td style="text-align: center;">{{ number_format($report['summary']['expectedHours'], 2) }}h</td>
                    <td style="text-align: center;">
                        @if($report['summary']['overtime'] > 0)
                            <span style="color: #6a1b9a; font-weight: bold;">+{{ number_format($report['summary']['overtime'], 2) }}h OT</span>
                        @elseif($report['summary']['deficit'] > 0)
                            <span style="color: #c62828; font-weight: bold;">-{{ number_format($report['summary']['deficit'], 2) }}h</span>
                        @else
                            <span style="color: #2e7d32;">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- INDIVIDUAL REPORTS --}}
    @foreach($allReports as $index => $report)
    @php
        $nurse = $report['nurse'];
        $shiftSchedule = $report['shiftSchedule'];
        $records = $report['records'];
        $summary = $report['summary'];
    @endphp

    <div class="page-break"></div>

    <div style="position: relative;">
        <div class="nurse-number">Nurse {{ $index + 1 }} of {{ count($allReports) }}</div>

        <div class="header">
            <h2>CHANSEY HOSPITAL</h2>
            <p>Daily Time Record Report</p>
            <h3>{{ $dateFromFormattedShort }} — {{ $dateToFormattedShort }}</h3>
        </div>

        <!-- EMPLOYEE INFORMATION -->
        <div class="section">
            <div class="section-title">EMPLOYEE INFORMATION</div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Employee Name</span>
                    <span class="info-value">{{ $nurse->last_name }}, {{ $nurse->first_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Employee / Badge ID</span>
                    <span class="info-value">{{ $nurse->employee_id }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Designation</span>
                    <span class="info-value">{{ $nurse->designation }} Nurse</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Station</span>
                    <span class="info-value">{{ $nurse->station?->station_name ?? 'Admitting' }}</span>
                </div>
            </div>

            @if($shiftSchedule)
            <div class="schedule-box">
                <div class="schedule-label">ASSIGNED SHIFT SCHEDULE</div>
                <div class="schedule-item"><strong>Shift:</strong> {{ $shiftSchedule->name }}</div>
                <div class="schedule-item"><strong>Time:</strong> {{ $shiftSchedule->formatted_time_range }}</div>
                <div class="schedule-item"><strong>Days:</strong> {{ $shiftSchedule->days_short }}</div>
                <div class="schedule-item"><strong>Expected Hours/Week:</strong> {{ $shiftSchedule->total_hours_per_week }} hrs</div>
            </div>
            @endif
        </div>

        <!-- SUMMARY -->
        <div class="section">
            <div class="section-title">ATTENDANCE SUMMARY</div>
            <div class="summary-grid">
                <div class="summary-box">
                    <div class="label">Total Days Worked</div>
                    <div class="value">{{ $summary['totalRecords'] }}</div>
                </div>
                <div class="summary-box">
                    <div class="label">On Time</div>
                    <div class="value" style="color: #2e7d32;">{{ $summary['presentDays'] }}</div>
                </div>
                <div class="summary-box">
                    <div class="label">Late</div>
                    <div class="value" style="color: #e65100;">{{ $summary['lateDays'] }}</div>
                </div>
                <div class="summary-box">
                    <div class="label">Incomplete</div>
                    <div class="value" style="color: #c62828;">{{ $summary['incompleteDays'] }}</div>
                </div>
            </div>
            <div class="summary-grid">
                <div class="summary-box">
                    <div class="label">Total Hours Worked</div>
                    <div class="value">{{ number_format($summary['totalHours'], 2) }}h</div>
                </div>
                <div class="summary-box">
                    <div class="label">Expected Hours</div>
                    <div class="value">{{ number_format($summary['expectedHours'], 2) }}h</div>
                </div>
                <div class="summary-box" style="{{ $summary['overtime'] > 0 ? 'border-color: #6a1b9a;' : '' }}">
                    <div class="label">Overtime</div>
                    <div class="value overtime">{{ $summary['overtime'] > 0 ? '+' . number_format($summary['overtime'], 2) . 'h' : '—' }}</div>
                </div>
                <div class="summary-box" style="{{ $summary['deficit'] > 0 ? 'border-color: #c62828;' : '' }}">
                    <div class="label">Deficit</div>
                    <div class="value deficit">{{ $summary['deficit'] > 0 ? '-' . number_format($summary['deficit'], 2) . 'h' : '—' }}</div>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- DETAILED RECORDS TABLE -->
        <div class="section">
            <div class="section-title">DAILY TIME RECORDS</div>
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 8%;">#</th>
                        <th style="width: 20%;">Date</th>
                        <th style="width: 12%;">Day</th>
                        <th style="width: 15%;">Time In</th>
                        <th style="width: 15%;">Time Out</th>
                        <th style="width: 14%;">Hours</th>
                        <th style="width: 16%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $rIndex => $record)
                    <tr>
                        <td>{{ $rIndex + 1 }}</td>
                        <td>{{ $record->formatted_date }}</td>
                        <td>{{ $record->formatted_day }}</td>
                        <td>{{ $record->formatted_time_in }}</td>
                        <td>{{ $record->formatted_time_out }}</td>
                        <td>{{ $record->formatted_hours }}</td>
                        <td>
                            @if($record->status === 'Present')
                                <span class="status-present">On Time</span>
                            @elseif($record->status === 'Late')
                                <span class="status-late">Late</span>
                            @elseif($record->status === 'Incomplete')
                                <span class="status-incomplete">Incomplete</span>
                            @elseif($record->status === 'Ongoing')
                                <span class="status-ongoing">Ongoing</span>
                            @else
                                {{ $record->status }}
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #999; padding: 15px;">No records found for this period.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- SIGNATURE AREA -->
        <div class="signature-area">
            <div>
                <div class="signature-line">Employee Signature</div>
                <div style="text-align: center; font-size: 9px; margin-top: 3px;">{{ $nurse->first_name }} {{ $nurse->last_name }}</div>
            </div>
            <div>
                <div class="signature-line">Head Nurse / Supervisor</div>
            </div>
        </div>

        <div class="footer">
            <p>This is an official payroll document. For inquiries, contact hospital administration.</p>
            <p>Report generated on {{ now()->format('M d, Y \a\t H:i:s') }}</p>
        </div>
    </div>
    @endforeach
</body>
</html>
