**Yes, but only if you configure `.env` correctly.**

Out of the box, Laravel does not send real emails because it doesn't know *how* (Gmail? Outlook? Mailgun?).

### To make it work for REAL:
If you want to receive an actual email in your Gmail inbox right now:

1.  **Use Gmail App Password:**
    *   Go to your Google Account -> Security -> 2-Step Verification.
    *   Scroll to bottom -> **App Passwords**.
    *   Create one named "Chansey". Copy the 16-character code.

2.  **Update `.env` file:**
    ```env
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.gmail.com
    MAIL_PORT=587
    MAIL_USERNAME=your_real_gmail@gmail.com
    MAIL_PASSWORD=your_16_char_app_password
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS="no-reply@chansey.test"
    MAIL_FROM_NAME="${APP_NAME}"
    ```

3.  **Restart Server:**
    *   `php artisan config:clear`
    *   `npm run dev` (re-run your serve command).

**Now, if you put your real email in the Guest Form, you WILL receive the email.**

### Recommendation (For Dev/Testing):
If you don't want to mess with Google Security, use **Log Driver**.
*   In `.env`: `MAIL_MAILER=log`
*   Result: The email content is written to `storage/logs/laravel.log`. You can open that file to "see" the email without sending it. This is usually enough for a Capstone demo unless they explicitly ask for a live demo.

**Yes, correct.** The Admitting Nurse acts as the "Scheduler".

### 1. The Admitting Nurse Workflow
1.  **View List:** See "Pending Appointments".
2.  **Action:** Click **"Schedule"** (Modal/View).
3.  **Inputs:**
    *   **Select Doctor:** (Filtered by the requested Department).
    *   **Select Date/Time:** (The Collision Logic we discussed yesterday).
4.  **Submit:**
    *   Update Status -> `Approved`.
    *   **Trigger Email Notification.**

---

### 2. Implementation: Sending the Email

In Laravel, sending emails is clean. You create a "Mailable" class.

**A. Generate Mailable:**
```bash
php artisan make:mail AppointmentApproved
```

**B. Edit `app/Mail/AppointmentApproved.php`:**
```php
class AppointmentApproved extends Mailable
{
    public $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Appointment Confirmed - Chansey Hospital');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.appointment_approved');
    }
}
```

**C. Create the View (`resources/views/emails/appointment_approved.blade.php`):**
```html
<h1>Hello {{ $appointment->first_name }},</h1>
<p>Your appointment has been confirmed.</p>
<ul>
    <li><strong>Doctor:</strong> Dr. {{ $appointment->physician->last_name }}</li>
    <li><strong>Department:</strong> {{ $appointment->department->name }}</li>
    <li><strong>Time:</strong> {{ $appointment->scheduled_at->format('M d, Y h:i A') }}</li>
</ul>
<p>Please arrive 15 minutes early.</p>
```

**D. Update Controller (The Approve Logic):**
Inside `AppointmentController@approve`:

```php
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentApproved;

// ... after updating the appointment ...

if ($appointment->email) {
    Mail::to($appointment->email)->send(new AppointmentApproved($appointment));
}

return back()->with('success', 'Scheduled and Email Sent!');
```

### Note on Local Testing (Mailpit)
Since you are on local (`.env`), set `MAIL_MAILER=log` to just write emails to `storage/logs/laravel.log` if you don't want to set up SMTP yet. It is the fastest way to test.