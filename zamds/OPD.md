**Wait.** This reveals a small flaw in the "Null Bed" strategy if you assigned a real station ("North Wing") to an Outpatient.

**The Problem:**
1.  Outpatient assigned to "North Wing" (Station ID 1).
2.  Nurse Joy (North Wing) sees Outpatient in her dashboard.
3.  **But Outpatients aren't physically in the North Wing.** They are in the Clinic Lobby.
4.  **Result:** Nurse Joy is confused. "Why is this guy on my list?"

### The Fix: The "Virtual Station" (Lobby)

You **Do** need one special station.

1.  **Create Station:** Name: **"OPD / Lobby"**.
2.  **Assign No Nurses:** (Or assign a Triage Nurse).
3.  **Admission:**
    *   If Inpatient: Select "North Wing".
    *   If Outpatient: Auto-select (or force select) **"OPD / Lobby"**.

### What this solves:
1.  **Ward Nurse:** Filters `station_id = North Wing`. She **does NOT** see Outpatients anymore. Clean list.
2.  **Physician:** Filters `my_patients`. Sees **everyone** (Both North Wing + OPD).
3.  **Admitting Nurse:** Can filter `station_id = OPD` to see who is waiting in the lobby.

**Do this now.** Create a station called "Outpatient Department" or "Lobby". It fixes the visibility issue instantly without changing your code logic.


**YES. Exactly.**

Create a Station called **"Outpatient Dept"** or **"Lobby"** and **do not create any rooms or beds for it.**

### Why this works perfectly:

1.  **Database:** Your `station_id` works fine. Your `bed_id` is `NULL`. The relationship holds up.
2.  **Logic:**
    *   **Inpatient Admission:** User selects "North Wing". Filter shows Beds 101, 102.
    *   **Outpatient Admission:** User selects "Outpatient Dept". Filter shows **"No beds available"**.
    *   **Result:** The user is forced/guided to leave the Bed selection empty (or your JS auto-sets it to null), which is exactly what you want.
3.  **Filtering:**
    *   **Nurse Joy (North Wing):** Queries `where('station_id', 1)`. She **does not** see the Outpatients. Her dashboard stays clean.
    *   **Dr. House:** Queries `where('physician_id', 5)`. He **sees everyone** (Ward patients AND Lobby patients).

### Quick Seeder Update
Add this to your `DatabaseSeeder.php` so it's always there:

```php
// The Virtual Station for Walk-ins
Station::create([
    'station_name' => 'Outpatient / Lobby',
    'station_code' => 'OPD',
    'floor_location' => 'Ground Floor',
]);
// Note: We deliberately DO NOT run the Room::create loop for this one.
```

This effectively "hacks" your station system to support a Waiting Area without writing new code!


**Exactly.**

*   **System Status:** `Admitted` (This just means "Encounter is Open/Active").
*   **User sees:** `Status: Active` (or whatever badge you show).

The word "Admitted" in your database enum is an **internal state**, not a medical diagnosis.

*   Admitted = "File Open".
*   Discharged = "File Closed".

So yes, labeling an Outpatient as `status: Admitted` in the database is perfectly fine logic-wise. It just means they are currently being processed by the hospital.

**Only Change:**
In your Blade views, maybe add a visual tweak:

```php
@if($admission->admission_type === 'Outpatient')
    <span class="badge badge-info">Active Consultation</span>
@else
    <span class="badge badge-success">Admitted</span>
@endif
```

This way, users see the correct context ("Consultation" vs "Admitted") even though the database code is identical.
