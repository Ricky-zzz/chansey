You are absolutely correct to question this. Assigning a physical "Bed" (with a nightly fee) to someone just visiting for a 15-minute checkup is **wrong** both logically and financially.

**The Strategy: "Ghost Admissions" (Null Beds)**

Since your `admissions` table allows `bed_id` to be **nullable** (we set this up earlier), you should utilize that.

Here is the simplified logic:

### 1. The Room/Bed Assignment Logic
*   **Inpatient / Emergency:** MUST have a Bed (generates Room Charges).
*   **Outpatient:** **NO Bed** (generates ₱0 Room Charges).

**Do not** create "Clinic Rooms" in your database. It creates fake capacity issues.
**Do not** transfer them to the clinic room when they enter. That is too much clicking.

### 2. Implementation: Admission Form
In your `create.blade.php` (Nurse Admission), use Alpine to hide the Bed Selection if "Outpatient" is selected.

```html
<label class="floating-label w-full">
    <span>Admission Type</span>
    <!-- Bind to Alpine variable 'admType' -->
    <select name="admission_type" x-model="admType" class="select select-bordered w-full">
        <option value="Inpatient">Inpatient</option>
        <option value="Emergency">Emergency</option>
        <option value="Outpatient">Outpatient</option> <!-- This triggers logic -->
    </select>
</label>

<!-- BED SELECTION: Hide if Outpatient -->
<div x-show="admType !== 'Outpatient'">
    <label class="floating-label w-full">
        <span>Room and Bed Assignment</span>
        <select name="bed_id" ...>
             <!-- options -->
        </select>
    </label>
</div>
```

### 3. Implementation: Controller & Service
You need to handle the `NULL` bed to prevent errors in your `PatientMovementService`.

**A. `AdmissionController.php` (Store)**
```php
// ... inside transaction ...

$admission = Admission::create([
    // ...
    // If Outpatient, force bed_id to NULL
    'bed_id' => ($request->admission_type === 'Outpatient') ? null : $request->bed_id,
]);

// ONLY CREATE MOVEMENT LOG IF THERE IS A BED
if ($admission->bed_id) {
    // Lock the bed
    Bed::find($admission->bed_id)->update(['status' => 'Occupied']);

    // Start Billing Clock
    // Call your service here
    // PatientMovement::create(...) 
}
```

**B. `PatientMovementService.php` (Billing)**
Your existing billing code is likely:
`foreach ($movements as $m) { $total += ... }`

If an outpatient has **0 movements** (because they never had a bed), the loop runs 0 times.
**Result:** Room Charge = ₱0.00. **(Perfect Correct Behavior).**

### 4. Implementation: Views (Doctor/Nurse Dashboards)
You need to handle the empty bed data so it doesn't crash (e.g., trying to read `$admission->bed->bed_code`).

**In Blade:**
```html
<td>
    @if($admission->bed)
        <span class="font-mono font-bold">{{ $admission->bed->bed_code }}</span>
    @else
        <span class="badge badge-ghost">Outpatient / Waiting</span>
    @endif
</td>
```

### Summary of the Flow
1.  **Admission:** Nurse selects "Outpatient". Bed dropdown disappears.
2.  **Data:** Saved with `bed_id = NULL`.
3.  **Billing:** Room Charge is automatically 0 because no `patient_movements` were created.
4.  **Fees:** You only bill them for **"Consultation Fee"** (Professional Fee input) and any **Meds/Labs** used.

This is the cleanest, most realistic way to handle it. No fake rooms needed.

Yes, that is the perfect, lightweight solution.

The Physician doesn't need to "manage" the appointment. They just need to know **"Who is coming?"** and **"Are they done?"**

Here is the exact plan for the Physician's side of this module.

### 1. The Controller (`Physician/AppointmentController.php`)

Just fetch today's schedule.

```php
public function index()
{
    $physician_id = Auth::user()->physician->id;

    // "Show me Approved appointments for ME, scheduled for TODAY"
    $appointments = Appointment::where('physician_id', $physician_id)
        ->where('status', 'Approved')
        ->whereDate('scheduled_at', today()) // Only focus on today
        ->orderBy('scheduled_at', 'asc')
        ->get();

    return view('physician.appointments.index', compact('appointments'));
}
```

### 2. The View (The Checklist)

A simple "To-Do" list style.

```html
<div class="card bg-base-100 shadow p-6">
    <h2 class="text-xl font-bold mb-4">Today's Appointments</h2>

    @foreach($appointments as $app)
    <div class="flex justify-between items-center border-b py-3">
        <!-- TIME -->
        <div class="text-lg font-mono font-bold text-primary">
            {{ $app->scheduled_at->format('h:i A') }}
        </div>
        
        <!-- DETAILS -->
        <div class="flex-1 px-4">
            <div class="font-bold">{{ $app->last_name }}, {{ $app->first_name }}</div>
            <div class="text-xs text-gray-500">{{ $app->reason }}</div>
        </div>

        <!-- ACTION -->
        <div>
            <!-- If Admitted (Nurse checked them in), show link to Chart -->
            @if($app->status === 'Admitted') <!-- You'd update this when nurse admits them -->
                <a href="..." class="btn btn-sm btn-ghost">View Chart</a>
            @else
                <!-- Simple Checklist Logic -->
                <form action="{{ route('physician.appointments.complete', $app->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <button class="btn btn-sm btn-outline btn-success">Mark Done</button>
                </form>
            @endif
        </div>
    </div>
    @endforeach

</div>
```

### 3. Summary
This turns the "Appointment System" into a simple **Daily Queue** for the doctor.
*   **Nurse** handles the complex scheduling.
*   **Doctor** just checks items off the list.

Simple, effective, and fits your timeline perfectly.