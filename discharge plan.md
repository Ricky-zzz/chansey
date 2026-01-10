Using `whereNot('status', 'Discharged')` or `where('status', '!=', 'Discharged')` is generally safer for "Active" lists because it automatically includes any future statuses you might invent (e.g., 'Transferred', 'ICU', 'Surgery') without you having to update the query every time.

### Recommendation:

**1. For Clinical/Ward Dashboards (Active Patients)**
Instead of:
```php
->whereIn('status', ['Admitted', 'Ready for Discharge', 'Cleared'])
```
Use:
```php
->where('status', '!=', 'Discharged')
```
*   **Why:** Even if the patient is "Ready for Discharge" (Billing) or "Cleared" (Paid but waiting for ride), they are physically still in the bed. The nurse must still see them.

**2. For Admitting Dashboard (Incoming/Processing)**
Keep using specific checks because Admitting cares about specific phases.

**3. For Accountant (Billing Queue)**
Be specific.
```php
->where('status', 'Ready for Discharge')
```
*   **Why:** Accountants don't care about 'Admitted' patients. They only want the ones ready to pay.

**Summary:**
*   **Use `!= 'Discharged'`** for anyone who needs to see the patient *physically* in the hospital.
*   **Use specific status** for workflow steps (Billing, Transfer Requests).

---------------------------------------------------------------------------------------------------------------

You should **Add a new Tab/Section** on the Admitting Dashboard called **"Ready for Release"**.

Don't bury it in the Search or the general Admission List.

**Why?**
*   **Workflow Priority:** Discharging a patient frees up a bed. This is a high-priority task. If it's hidden in a general search, the nurse might miss it, and the bed stays "occupied" for hours unnecessarily.
*   **Clarity:** A patient who is "Cleared" (Paid) is fundamentally different from a patient who is "Admitted" (Being treated). Mixing them in one list confuses the user ("Wait, do I need to admit him or kick him out?").

### Implementation

**1. Dashboard Controller (`Admitting/DashboardController`)**
Fetch a separate list.

```php
$forRelease = Admission::with(['patient', 'bed.room'])
    ->where('status', 'Cleared') // Paid and ready to go
    ->get();
```

**2. Dashboard View (`admitting/dashboard.blade.php`)**
Add a dedicated card/table at the top (or a tab).

```html
<!-- ALERT SECTION: FOR RELEASE -->
@if($forRelease->count() > 0)
    <div class="card bg-success text-white shadow-xl mb-8">
        <div class="card-body">
            <h2 class="card-title">
                <svg ...>...</svg>
                Ready for Release ({{ $forRelease->count() }})
            </h2>
            <p>These patients have settled their bills. Discharge them to free up beds.</p>
            
            <div class="overflow-x-auto mt-2">
                <table class="table text-white">
                    <thead>
                        <tr class="border-white/30">
                            <th>Patient</th>
                            <th>Bed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($forRelease as $adm)
                        <tr class="border-white/30">
                            <td class="font-bold">{{ $adm->patient->last_name }}</td>
                            <td>{{ $adm->bed->bed_code }}</td>
                            <td>
                                <!-- THE DISCHARGE BUTTON -->
                                <form action="{{ route('nurse.admitting.discharge', $adm->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-white text-success hover:bg-gray-100">
                                        Final Discharge
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
```

This makes it impossible for the nurse to miss a cleared patient!