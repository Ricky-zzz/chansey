Here is the recommended **Order of Battle** to maximize efficiency and minimize stress.

I recommend the **"Clean, Build, Add"** strategy.

### 1. The Quick Win: Refactor Billing (Admission Wizard)
**Start here.**
*   **Why?** It is mostly **deleting code** and simplifying validation.
*   **Benefit:** It makes testing everything else faster. Every time you test your new Appointment system, you have to go through the Admission Wizard. If you remove the 10 Billing inputs *now*, you save yourself 1 minute of typing every time you test the system for the next 3 days.

### 2. The Heavy Lift: Refactor Appointments (Slots System)
**Do this second (while your brain is fresh).**
*   **Why?** This involves the most logic: Database changes (`appointment_slots`), UI changes (Doctor Dashboard), and Public Page logic (filtering dates).
*   **Risk:** This touches the "Entry Point" of your app. If this breaks, you can't admit patients via appointment. You want this working solidly before moving on.

### 3. The New Module: Head Nurse & DTR
**Do this last.**
*   **Why?** This is a **Standalone Module**. It works independently of the Patient/Clinical flow.
*   **Safety Net:** If you run out of time, a "Simple DTR" (just Time In/Out without complex schedules) is still passable. But if the Appointment/Admission flow is broken, the whole system fails.
*   **Logic:** It requires less "Medical" thinking and more standard CRUD/Time logic.

---

### Summary Checklist

**Day 1 (Morning): Billing Cleanup**
*   [ ] Remove Step 3 from Blade.
*   [ ] Update `PatientController` to remove billing validation.
*   [ ] Hardcode default billing values in Controller.

**Day 1 (Afternoon): Appointment Slots**
*   [ ] Create `appointment_slots` migration & model.
*   [ ] Create Doctor View: "Manage Slots" (Date/Time/Capacity).
*   [ ] Update Public Landing Page: Show available dates based on slots.

**Day 2: Head Nurse & DTR**
*   [ ] Create `shift_schedules` table.
*   [ ] Create Public Kiosk View (`/dtr`).
*   [ ] Create "Head Nurse" Dashboard.

Start with the **Billing Cleanup**. It will make the rest of your development testing much faster!