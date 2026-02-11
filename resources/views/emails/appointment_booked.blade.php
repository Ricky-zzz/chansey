<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmed</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #334155;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }
        .container {
            background: white;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .header {
            text-align: center;
            margin-bottom: 24px;
        }
        .logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #10b981, #14b8a6);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 12px;
        }
        h1 {
            color: #0f172a;
            margin: 0 0 8px 0;
            font-size: 24px;
        }
        .success-badge {
            display: inline-block;
            background: #dcfce7;
            color: #166534;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        .details-card {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 20px;
            margin: 24px 0;
        }
        .details-card h3 {
            margin: 0 0 16px 0;
            color: #166534;
            font-size: 16px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #dcfce7;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            color: #64748b;
            font-size: 14px;
        }
        .detail-value {
            font-weight: 600;
            color: #0f172a;
            font-size: 14px;
        }
        .doctor-info {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
            text-align: center;
        }
        .doctor-info .name {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
        }
        .doctor-info .dept {
            color: #64748b;
            font-size: 14px;
        }
        .reminder {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 12px 16px;
            margin: 24px 0;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #e2e8f0;
            color: #94a3b8;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">C</div>
            <h1>Golden Gate Academy</h1>
            <span class="success-badge">✓ Appointment Confirmed</span>
        </div>

        <p>Hello <strong>{{ $appointment->first_name }}</strong>,</p>

        <p>Great news! Your appointment has been successfully booked. Here are your appointment details:</p>

        <div class="doctor-info">
            <div class="name">Dr. {{ $appointment->appointmentSlot->physician->first_name }} {{ $appointment->appointmentSlot->physician->last_name }}</div>
            <div class="dept">{{ $appointment->appointmentSlot->physician->department->name }}</div>
        </div>

        <div class="details-card">
            <h3>📅 Appointment Details</h3>
            <div class="detail-row">
                <span class="detail-label">Date</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($appointment->appointmentSlot->date)->format('l, F d, Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Time</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($appointment->appointmentSlot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($appointment->appointmentSlot->end_time)->format('h:i A') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Reference #</span>
                <span class="detail-value">APT-{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>

        <div class="reminder">
            <strong>📌 Important Reminders:</strong>
            <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                <li>Please arrive 15 minutes before your scheduled time</li>
                <li>Bring a valid ID and any relevant medical records</li>
                <li>If you need to cancel, please do so at least 24 hours in advance</li>
            </ul>
        </div>

        <p>If you have any questions, feel free to contact us at <strong>(555) 123-4567</strong> or reply to this email.</p>

        <p>We look forward to seeing you!</p>

        <p>
            Best regards,<br>
            <strong>Golden Gate Academy Team</strong>
        </p>

        <div class="footer">
            <p>Golden Gate Academy | 123 Health Street | contact@goldengateacademy.com</p>
            <p>© {{ date('Y') }} Golden Gate Academy. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
