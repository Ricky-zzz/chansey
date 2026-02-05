This DTR module plan is solid, and you are asking the right questions about edge cases (like overnight shifts).

Here is the structured breakdown/review of your plan:

### 1. The Public DTR Kiosk (Outside Login)
**Verdict:** **Correct.**
This mimics a real bio-metric/thumbprint machine at the hospital entrance. Nurses shouldn't have to log into their full dashboard just to clock in.

**Implementation:**
*   **Route:** `/dtr` (Public, no auth).
*   **Form:** Input `badge_id` (NUR-001) + `password`.
*   **Action:** Two Big Buttons: **[ TIME IN ]** and **[ TIME OUT ]**.

**Why two buttons?**
The "Single Log Button" logic is dangerous for **Overnight Shifts**.
*   *Scenario:* Nurse works 8 PM to 4 AM.
*   She forgets to Time Out at 4 AM.
*   She comes back at 8 PM (Monday).
*   If you use a toggle, the system thinks 8 PM Monday is her "Time Out" for Sunday.
*   **Explicit Buttons** prevent this. If she clicks "Time In" and she is already In, show an error: *"You have an open session from yesterday. Please resolve with Head Nurse."*

### 2. The Database Structure (Handling Overnight)
You are right to store full datetime.

**Table `daily_time_records`:**
*   `id`
*   `user_id`
*   `time_in` (datetime)
*   `time_out` (datetime, nullable)
*   `shift_schedule_id` (Snapshot of their sched that day, for calculating lates)
*   `status` (Present, Late, Overtime, Incomplete)

**Logic for Overnight:**
Since `time_in` and `time_out` are full datetimes, `2025-01-10 20:00:00` to `2025-01-11 04:00:00` calculates perfectly. No special "cross-day" logic needed.

### 3. The Personal View ("Github Grid")
**Verdict:** **Cool idea, but maybe overkill.**
A "Github Commit Graph" is hard to code from scratch.

**Simpler/Cleaner Alternative:** **A Calendar View.**
*   Show a standard Month Calendar.
*   **Green Day:** Timed in/out perfectly.
*   **Yellow Day:** Late / Undertime.
*   **Red Day:** Absent (Shift was scheduled but no DTR).
*   **Grey Day:** Rest day.

Put this in a **"My Profile"** or **"My Records"** section. Don't hide it too deep; nurses check their DTRs obsessively to ensure pay is correct.

### 4. Head Nurse Logic
**Verdict:** **Just a boolean flag.**
Don't make a new table. In your `nurses` table, just add `is_head_nurse` (boolean).
*   If `true`, they see the "Manage Schedules" and "Print Staff DTRs" buttons.
*   If `false`, they only see "My Patients".

### 5. Printing
**Verdict:** **Standard PDF Logic.**
*   **Input:** Date Range (Jan 1 - Jan 15).
*   **Output:** PDF Table.
    *   *Columns:* Date, Scheduled, Time In, Time Out, Late (mins), Undertime (mins).

### Summary of Adjustments
1.  **Use explicit In/Out buttons** on the Kiosk to handle user errors better.
2.  **Stick to Calendar View** instead of Github Grid (easier to implement with a library or simple loop).
3.  **Head Nurse** is just a flag, not a separate role.

You have a very complete plan here. Go for it!