**Yes, sticking to the "Big 4" (Private, Ward, ICU, ER) is completely fine.**

Here is why your current setup works perfectly for your Seeder and Scope:

1.  **Your Seeder Logic:**
    Look at your seeder code for OB-GYN:
    ```php
    [
        'name' => 'OB-GYN Ward',
        // ...
        'room_type' => 'Ward', // <--- YOU MAPPED IT TO 'WARD'
    ],
    ```
    Since you classified the OB-GYN beds as **"Ward"** type, they **will** appear in your "Ward" statistics. You are safe.

2.  **Hospital Reality vs. Simulation:**
    *   **Delivery Room (DR):** This is a *procedure room* (like an Operating Theater). Patients don't stay here overnight; they give birth and move to the Ward. Tracking DR capacity is usually separate from Bed Census.
    *   **Nursery:** Usually counts as a "Bassinet", not a Bed.
    *   **Isolation:** Usually just a Private room with a "Do Not Enter" sign.

**Recommendation:**
**Don't add** Delivery/Nursery/Isolation to your dashboard stats unless you actually create those rooms. It will just clutter the view with "0".

### Minimal Tweak (Safety Net)
If you are worried an Admin might manually create a "Delivery" room and it vanishes from the stats, just add a catch-all category or specifically add Maternity.

**In `Admitting/DashboardController.php`:**

```php
$stats = [
    'ER'      => $this->getBedStats('ER'),
    'ICU'     => $this->getBedStats('ICU'),
    'Ward'    => $this->getBedStats('Ward'),   // Includes MS-Ward and OB-Ward
    'Private' => $this->getBedStats('Private'),
    
    // Optional: Add this ONLY if you plan to create Delivery/Nursery rooms manually
    // 'Maternity' => $this->getBedStats(['Delivery', 'Nursery']), 
];
```

*Note: You'd need to tweak `getBedStats` to accept an array if you do the array approach, but honestly? **Don't bother.** Keep it to the Big 4. It looks cleaner.*

**Do NOT try to "fit" a Surgery or Delivery module now.** That is massive scope creep.

You have built a generalized **Inpatient Care System**. It works perfectly for Maternity and Surgery cases **without** dedicated buttons.

**How you handle these cases with your EXISTING system:**

### 1. The Maternity Flow (Manual)
*   **Admit:** Patient admitted to **OB-GYN Ward** (Room Type: Ward).
*   **Delivery:**
    *   Doctor writes Order: *"Transfer to Delivery Room for NSVD"* (Normal Spontaneous Vaginal Delivery).
    *   **Nurse Action:** Logs a Clinical Note: *"Patient transferred to DR."*
    *   **Baby:** After birth, Nurse **Registers a New Patient** (Baby Boy Dela Cruz) and admits to **OB-GYN Ward** (or Nursery if you made one).
*   **Post-Delivery:** Mother stays in the Ward bed the whole time (System-wise). The DR time is just a procedure.

### 2. The Surgery Flow (Manual)
*   **Pre-Op:** Patient in **Private Room**.
*   **Surgery:**
    *   Doctor Order: *"NPO since midnight. Scheduled for Appendectomy at 8AM."*
    *   **Nurse Action:** Logs Note: *"Patient sent to Operating Room (OR)."*
    *   **The System Bed:** **REMAINS OCCUPIED.** (You don't discharge them just because they went to surgery for 2 hours. Their stuff is still in the room).
*   **Post-Op:** Patient returns to the same room.

**Why this is better:**
*   You don't need a "Surgery Scheduler".
*   You don't need a "DR Occupancy" tracker.
*   You just use **Clinical Logs (Notes)** to track where the patient physically is for those few hours, while their **System Status** stays "Admitted" in their designated bed.

**Verdict:** Your system handles these complex cases naturally. Don't add buttons. Just add **Notes**.
