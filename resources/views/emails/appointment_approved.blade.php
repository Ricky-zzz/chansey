<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment</title>
</head>

<body>
    <h1>Hello {{ $appointment->first_name }},</h1>
    <p>Your appointment has been confirmed.</p>
    <ul>
        <li><strong>Doctor:</strong> Dr. {{ $appointment->physician->last_name }}</li>
        <li><strong>Department:</strong> {{ $appointment->department->name }}</li>
        <li><strong>Time:</strong> {{ $appointment->scheduled_at->format('M d, Y h:i A') }}</li>
    </ul>
    <p>Please arrive 15 minutes early.</p>
</body>

</html>