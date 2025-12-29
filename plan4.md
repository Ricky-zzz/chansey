This is the **"Survival Mode"** strategy. I have read through your rant (totally valid, by the way) and your requirements. You are being asked to build an Enterprise ERP for 5k pesos.

**We are going to cheat intelligently.** We will merge features, simplify workflows, and focus only on what looks good during the presentation.

Here is your **Finalized "Cheat Sheet" Database Structure** and your **Step-by-Step Battle Plan** to finish by January 10.

---

### Part 1: The "Cheat" Strategy (Read this first)

1.  **The "Universal Order" Table:** Instead of separate tables for "Lab Requests", "Radiology", "Dietary", and "Medication Orders", we use **ONE** table called `medical_orders`. The `type` column differentiates them.
2.  **The "Universal Log" Table:** Instead of separate tables for "Vitals", "Notes", and "Mar", we use **ONE** table called `clinical_logs`. The `type` column differentiates them.
3.  **Billing:** We don't calculate complex taxes or HMO math. We just list items in a `used_items` table and `SUM()` them up.
4.  **Appointments:** We pretend the Admitting Nurse receives a phone call and types it in. No public website.
5.  **Public Health:** We just run a simple SQL query `COUNT(*)` on the diagnosis column for the report. Done.

---

### Part 2: Finalized Database Structure (`db_structure.md`)

This includes your existing tables and the new ones needed to finish the job.

#### A. Auth & Staff (DONE)
*   `users` (id, name, badge_id, email, password, user_type)
*   `admins`, `nurses` (with designation), `physicians` (with specialization), `general_services`
*   **NEW:** `pharmacists` (Just duplicate the Admin structure, they just need to see the Meds table).

#### B. Infrastructure (DONE)
*   `stations` (name, code)
*   `rooms` (number, price_per_night)
*   `beds` (code, status)
*   `inventory_items` (name, price, qty)

#### C. Clinical Core (DONE / IN PROGRESS)
*   `patients` (demographics)
*   `admissions` (status, bed_id, physician_id, station_id)
*   `patient_files` (uploads)

#### D. The New Modules (To Create)

**1. `medicines` (Pharmacy Module)**
*Simulates the Pharmacy Inventory.*
```php
Schema::create('medicines', function (Blueprint $table) {
    $table->id();
    $table->string('name')->index(); // Generic + Brand
    $table->string('dosage'); // "500mg"
    $table->integer('stock')->default(0);
    $table->decimal('price', 10, 2); // For billing
    $table->timestamps();
});
```

**2. `medical_orders` (The Physician's Command Center)**
*Handles CPOE, Labs, Meds, and Transfer Requests.*
```php
Schema::create('medical_orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('admission_id')->constrained();
    $table->foreignId('physician_id')->constrained();
    
    // Type determines the logic: 'Medication', 'Lab', 'Radiology', 'Transfer', 'Discharge'
    $table->string('type')->index(); 
    
    // The instruction: "Paracetamol 500mg" or "CBC" or "Transfer to ICU"
    $table->text('instruction'); 
    
    // If it's a med, link it (Optional, mainly for price/stock deduction)
    $table->foreignId('medicine_id')->nullable()->constrained();
    
    // Frequency: "Every 4 hours", "Once", "Stat"
    $table->string('frequency')->nullable();
    
    // Status: 'Pending' (Nurse sees it), 'Done' (Nurse did it), 'Cancelled'
    $table->string('status')->default('Pending')->index();
    
    // Who executed it? (The Nurse)
    $table->foreignId('fulfilled_by_nurse_id')->nullable()->constrained('nurses');
    $table->timestamp('fulfilled_at')->nullable();
    
    $table->timestamps();
});
```

**3. `clinical_logs` (The Nurse's Charting & Monitoring)**
*Handles Vitals, SOAP Notes, and Task Completion.*
```php
Schema::create('clinical_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('admission_id')->constrained();
    $table->foreignId('nurse_id')->constrained();
    
    // Type: 'Vitals', 'Note', 'IntakeOutput', 'MedicationGiven'
    $table->string('type')->index();
    
    // Structured Data (For Vitals) -> {"bp": "120/80", "temp": 37.5}
    // Unstructured Data (For Notes) -> {"note": "Patient complaining of headache"}
    $table->json('data')->nullable();
    
    $table->timestamps();
});
```

**4. `billable_items` (The Money Trail)**
*Every time a med is given or a lab is done, add a row here.*
```php
Schema::create('billable_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('admission_id')->constrained();
    
    $table->string('name'); // "Paracetamol" or "Room Charge (3 Nights)"
    $table->decimal('amount', 10, 2); // Price at moment of use
    $table->integer('quantity')->default(1);
    $table->decimal('total', 10, 2); // amount * quantity
    
    // 'Unpaid', 'Paid'
    $table->string('status')->default('Unpaid'); 
    $table->timestamps();
});
```

---

### Part 3: The Battle Plan (Action_Plan.md)

This is your schedule to finish by Jan 10. Focus **only** on the "Happy Path" (what happens when everything goes right). Ignore complex error handling.

#### **Phase 1: The Orders Loop (Days 1-3)**
*Goal: Physician orders meds, Nurse gives meds, Stock decreases, Price adds to bill.*

1.  **Create `Pharmacist` User & `medicines` Resource (Filament):** Simple CRUD. Add 5 fake medicines.
2.  **Create `Physician` Dashboard (Blade):**
    *   Show list of patients (`where('attending_physician_id', $me)`).
    *   Click Patient -> "Create Order" Modal.
    *   Dropdown: Meds/Labs/Transfer/Discharge.
    *   Saves to `medical_orders`.
3.  **Create `Clinical Nurse` Dashboard (Blade):**
    *   **Logic:** Filter by `Station`. `Admission::where('station_id', $my_station)->get()`.
    *   **View:** List of Patients in Ward.
    *   **Show Patient:**
        *   **Left Col:** Active Orders (From `medical_orders`). Button: "Administer/Done".
        *   **Right Col:** Logs/History (From `clinical_logs`).
4.  **The "Done" Logic (Controller):**
    *   When Nurse clicks "Done" on a Med order:
        *   DB Transaction:
        *   Update `medical_orders` -> `status = Done`.
        *   Create `clinical_logs` -> "Gave Paracetamol".
        *   Create `billable_items` -> Name: Paracetamol, Price: 5.00.
        *   Decrement `medicines` -> Stock - 1.

#### **Phase 2: Labs & Transfers (Days 4-5)**
*Goal: Handle non-medication orders.*

1.  **Labs:**
    *   Physician orders "X-Ray".
    *   Nurse sees order.
    *   Nurse clicks "Upload Result" (re-use your File Upload logic).
    *   Nurse clicks "Done". Charge added to bill.
2.  **Transfer:**
    *   Physician orders "Transfer to ICU".
    *   Clinical Nurse sees order -> clicks "Request Transfer".
    *   **Admitting Nurse Dashboard:** See "Transfer Requests".
    *   Admitting Nurse selects New Bed -> Updates `admission` table -> Updates `rooms` table.

#### **Phase 3: Billing & Discharge (Days 6-7)**
*Goal: Let the patient leave.*

1.  **Discharge Order:** Physician creates order "Ready for Discharge".
2.  **Billing Dashboard (Filament):**
    *   Create `BillingStaff` role or just use Admin.
    *   Show list of patients with "Ready for Discharge" status.
    *   **Click Bill:**
        *   Script runs: Calculate Room Days (`now() - admission_date`) * Price. Add to `billable_items`.
        *   Show table of all `billable_items`.
        *   Show Total.
        *   Button: "Mark Paid".
3.  **Release:**
    *   Once Paid, Admitting Nurse sees "Cleared".
    *   Clicks "Discharge".
    *   Bed becomes "Cleaning" (Gen Service sees it).
    *   Admission Status -> "Discharged".

#### **Phase 4: Cleanup & Reports (Days 8-10)**
1.  **Appointments:** Just add a "Calendar" view for doctors. Don't connect it to anything complex. Just visual.
2.  **Reports:** Use Filament Widgets to show simple charts:
    *   "Patients Admitted this Month" (Count).
    *   "Top Diagnosis" (SQL Group By).
    *   "Total Earnings" (Sum of billable items).

---

### Summary of "Cheats"
*   **No Lab System:** Nurse just uploads a PDF.
*   **No Pharmacy System:** Nurse auto-deducts stock when clicking "Done".
*   **No Billing Engine:** Just a running list of items added when the nurse does things.
*   **No Complex Scheduling:** Just a list of patients.

This plan is achievable by Jan 10. **Start creating the `medicines` and `medical_orders` migrations now.** You got this.