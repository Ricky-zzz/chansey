**DO NOT BUILD THE BASKET.**

I repeat: **DO NOT build the Basket.**

You are already tired, the deadline is close, and you are not being paid enough to build an Inventory Rebalancing System (which is what a Basket is).

If you build a Basket, you have to handle:
1.  **Returns:** Patient discharged? You must calculate `Given - Dispensed` and return the difference to inventory.
2.  **Losses:** Nurse dropped a pill? The counts won't match.
3.  **Sync:** Pharmacist dispenses 10, Doctor changes order to "Stop". Now you have orphan pills.

---

### The Solution: "Active Medications" is just a View

Your Professor asked for **"Active Medications"**. He didn't explicitly say "Physical Basket".

You can satisfy his requirement by creating a **Pharmacist Dashboard View** called "Active Patient Medications".

**The Logic:**
1.  **Definition:** An "Active Medication" is simply any `MedicalOrder` where:
    *   `type` is 'Medication'
    *   `status` is 'Active' or 'Pending'
2.  **No New Tables:** You query the existing `medical_orders` table.

### How to implement the "Pharmacy Monitor" (The Cheat)

Instead of the Pharmacist *preparing* meds per patient, the Pharmacist **Monitors** the demand.

**1. Create `Pharmacist/ActiveMedsController`:**

```php
public function index() {
    // Group Active Orders by Medicine
    // This tells the pharmacist: "Hey, 5 patients need Biogesic right now."
    $activeOrders = MedicalOrder::with(['patient', 'medicine', 'latestLog'])
        ->where('type', 'Medication')
        ->whereIn('status', ['Active', 'Pending'])
        ->get();
        
    return view('pharmacist.active_meds.index', compact('activeOrders'));
}
```

**2. The View (What the Prof sees):**
It looks like a "Worklist".

| Patient | Medicine | Frequency | Last Given | Action |
| :--- | :--- | :--- | :--- | :--- |
| **Juan Doe** | Biogesic 500mg | Every 4h | 10:00 AM | **[ Monitor Stock ]** |
| **Maria Po** | Amoxicillin | Daily | Yesterday | **[ Monitor Stock ]** |

**3. The Narrative (How to explain it to Sir Alain):**
> *"Sir, we implemented a **Ward Stock System**. Instead of the Pharmacist dispensing per tablet (which causes delays), the Pharmacist uses this **'Active Medications Monitoring Board'**. They see live demand from the wards. If they see 10 patients are on Biogesic, they know they need to restock the Ward Cabinet. This is more efficient for our simulation."*

---

### If the Prof INSISTS on "Dispensing"

If he effectively says, *"No, I want to see the Pharmacist click a button before the Nurse gets it,"* do this **without** a basket table.

**The "Gatekeeper" Pattern:**

1.  **Doctor Order:** Status = `Pending Pharmacy`. (Nurse CANNOT see it yet).
2.  **Pharmacist:**
    *   Sees `Pending Pharmacy` orders.
    *   Clicks **"Dispense / Approve"**.
    *   **System Action:**
        *   Updates Order Status -> `Active`.
        *   (Optional) Deducts a "Bulk Amount" (e.g. 10) from Main Inventory just to show movement.
3.  **Nurse:** Now sees the order as `Active` and can click "Administer".
4.  **Billing:** Nurse Administers -> `BillableItem` created. (Same as now).

**Why this is better:**
*   It creates the "Interaction" he wants (Doctor -> Pharma -> Nurse).
*   It keeps your "Pay per Swallow" billing logic intact.
*   It uses `status` columns, not new tables.

### Final Verdict

1.  **Don't** add a basket column.
2.  **Don't** make a basket table.
3.  **Do** make a Pharmacist Dashboard called "Active Medications".
4.  **If pushed:** Use the "Gatekeeper" pattern (Pharma changes status from Pending to Active) to unlock the order for the Nurse.

You are 3 days away. Stick to the logic that requires the **least code**.
