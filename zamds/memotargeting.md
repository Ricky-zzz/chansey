# Memo Targeting System Documentation

## Overview
The memo/announcement system uses **strict AND matching** with optional constraints to determine who sees each memo.

## Core Principle: Strict AND Logic

All specified constraints must match for a nurse to see a memo. Empty or null constraints act as wildcards (any value passes).

### The Three Constraints:
1. **target_roles** - Which role levels can see this memo
2. **target_units** - Which units can see this memo  
3. **target_stations** - Which stations can see this memo

### Matching Rules:
- ✅ **Specified constraint** → Nurse's value MUST match
- ✅ **Empty/null constraint** → Any value passes (wildcard)
- 🔗 **All three constraints** use AND logic (all must pass)

---

## Query Logic

```php
// Nurse sees memo if ALL three conditions pass:
WHERE (
    // Condition 1: Role matches OR not specified
    (target_roles contains nurse.role_level OR target_roles IS NULL OR target_roles = '[]')
    
    AND
    
    // Condition 2: Unit matches OR not specified
    (target_units contains nurse.unit_id OR target_units IS NULL OR target_units = '[]')
    
    AND
    
    // Condition 3: Station matches OR not specified  
    (target_stations contains nurse.station_id OR target_stations IS NULL OR target_stations = '[]')
)
```

---

## Example Cases

### Case 1: Global Broadcast (All Nurses)
**Chief creates memo with:**
```json
{
  "target_roles": [],
  "target_units": [],
  "target_stations": []
}
```

**Who sees it:**
- ✅ ALL nurses across all units, stations, and roles
- ✅ Staff, Head, Supervisor, Chief - everyone

**Reason:** All constraints are empty = no restrictions

---

### Case 2: Target Entire Unit
**Chief creates memo with:**
```json
{
  "target_roles": [],
  "target_units": [1],
  "target_stations": []
}
```

**Who sees it:**
- ✅ Staff Nurse at Unit 1, Station 3
- ✅ Head Nurse at Unit 1, Station 7
- ✅ Supervisor at Unit 1
- ✅ Any role at ANY station within Unit 1
- ❌ Anyone from Unit 2, Unit 3, etc.

**Reason:** Role and Station are wildcards, but Unit must be 1

---

### Case 3: Target Specific Station (All Roles)
**Chief creates memo with:**
```json
{
  "target_roles": [],
  "target_units": [1],
  "target_stations": [3]
}
```

**Who sees it:**
- ✅ Staff Nurse at Unit 1, Station 3
- ✅ Head Nurse at Unit 1, Station 3
- ✅ Any role at Unit 1, Station 3
- ❌ Staff Nurse at Unit 1, Station 7 (wrong station)
- ❌ Anyone from Unit 2

**Reason:** Unit AND Station must both match, Role is wildcard

---

### Case 4: Target Specific Role (All Units/Stations)
**Chief creates memo with:**
```json
{
  "target_roles": ["Staff"],
  "target_units": [],
  "target_stations": []
}
```

**Who sees it:**
- ✅ Staff Nurse at Unit 1, Station 3
- ✅ Staff Nurse at Unit 2, Station 5
- ✅ ALL Staff Nurses everywhere
- ❌ Head Nurses
- ❌ Supervisors
- ❌ Chief Nurses

**Reason:** Role must be Staff, Unit and Station are wildcards

---

### Case 5: Target Staff in Specific Unit
**Supervisor creates memo with:**
```json
{
  "target_roles": ["Staff"],
  "target_units": [1],
  "target_stations": []
}
```

**Who sees it:**
- ✅ Staff Nurse at Unit 1, Station 3
- ✅ Staff Nurse at Unit 1, Station 7
- ✅ All Staff Nurses within Unit 1
- ❌ Head Nurse at Unit 1 (wrong role)
- ❌ Staff Nurse at Unit 2 (wrong unit)

**Reason:** Role must be Staff AND Unit must be 1, Station is wildcard

---

### Case 6: Target Multiple Roles in Multiple Units
**Chief creates memo with:**
```json
{
  "target_roles": ["Staff", "Head"],
  "target_units": [1, 2],
  "target_stations": []
}
```

**Who sees it:**
- ✅ Staff Nurse at Unit 1, Station 3
- ✅ Head Nurse at Unit 2, Station 5
- ✅ Staff or Head at Unit 1 or Unit 2, any station
- ❌ Supervisor at Unit 1 (role not in list)
- ❌ Staff Nurse at Unit 3 (unit not in list)

**Reason:** Role must be in [Staff, Head] AND Unit must be in [1, 2]

---

### Case 7: Very Specific Targeting
**Head Nurse creates memo with:**
```json
{
  "target_roles": ["Staff"],
  "target_units": [],
  "target_stations": [3]
}
```

**Who sees it:**
- ✅ Staff Nurse at Unit 1, Station 3
- ✅ Staff Nurse at Unit 2, Station 3 (if station 3 exists in Unit 2)
- ✅ All Staff Nurses at Station 3 regardless of unit
- ❌ Head Nurse at Station 3 (wrong role)
- ❌ Staff Nurse at Station 7 (wrong station)

**Reason:** Role must be Staff AND Station must be 3, Unit is wildcard

---

### Case 8: Impossible Constraint Prevented by UI
**This scenario is prevented by dynamic filtering:**
```json
{
  "target_roles": ["Staff"],
  "target_units": [1],
  "target_stations": [99]  // Station 99 belongs to Unit 2
}
```

**Result:** Chief's form filters stations by selected units, so Station 99 won't appear in dropdown if Unit 1 is selected. This prevents creating memos that nobody can see due to impossible location constraints.

---

## Role-Based Creation Rules

### Chief Nurse
- ✅ Can target any role, unit, station
- ✅ No restrictions
- ✅ Dynamic station filtering prevents impossible constraints
- ✅ Can send global broadcasts

### Supervisor
- ✅ Can target Staff and Head roles within their unit
- ✅ Auto-restricted to their own unit_id
- ✅ Can select specific stations within their unit
- ❌ Cannot send outside their unit

### Head Nurse
- ✅ Can target Staff role only
- ✅ Auto-restricted to their own station_id
- ✅ Auto-sets their unit_id as null (implied by station)
- ❌ Cannot target other stations or roles

### Staff Nurse
- ❌ Cannot create memos (read-only access)
- ✅ Can view announcements sent to them

---

## UI Flow Examples

### Example A: Chief Sends to One Unit
1. Chief selects **target_units = [Unit 1]**
2. Chief leaves **target_roles = []** (empty)
3. Chief leaves **target_stations = []** (empty)
4. **Result:** Everyone in Unit 1 sees it (all roles, all stations)

### Example B: Supervisor Sends to Station Staff
1. Supervisor's form auto-fills **target_units = [their unit]**
2. Supervisor selects **target_roles = [Staff]**
3. Supervisor selects **target_stations = [Station 3]**
4. **Result:** Only Staff at Station 3 in Supervisor's unit see it

### Example C: Head Nurse Sends to Station
1. Head's form auto-fills **target_roles = [Staff]**
2. Head's form auto-fills **target_stations = [their station]**
3. Head's form sets **target_units = null** (implied by station)
4. **Result:** Only Staff at Head's specific station see it

---

## Important Notes

### Viewing Own Memos
- Nurses don't see their own created memos in the announcement inbox
- Query includes: `WHERE created_by_user_id != current_user_id`

### JSON Array Matching
- All IDs are cast to integers: `(int)$nurse->unit_id`
- JSON columns use `whereJsonContains()` for array matching
- Empty arrays checked with: `whereRaw("column = '[]'")`

### Hierarchy Enforcement
- Supervisors automatically restricted by their unit (enforced in `mutateFormDataBeforeCreate()`)
- Head Nurses automatically restricted by their station (enforced in controller)
- Dynamic filtering prevents UI from showing impossible options

### Performance Considerations
- Query uses three parallel `where()` clauses with OR conditions
- Latest memos shown first: `->latest()`
- Pagination used: `->paginate(15)`

---

## Testing Scenarios

### Scenario 1: Chief to All Supervisors
```
Roles: [Supervisor]
Units: []
Stations: []
Expected: All Supervisors see it, regardless of location
```

### Scenario 2: Supervisor to Their Unit's Staff
```
Roles: [Staff]
Units: [1] (auto-filled)
Stations: []
Expected: All Staff in Unit 1 see it
```

### Scenario 3: Head to Their Station Staff
```
Roles: [Staff]
Units: [] (set to null/empty)
Stations: [3] (auto-filled)
Expected: Only Staff at Station 3 see it
```

### Scenario 4: Chief Global Announcement
```
Roles: []
Units: []
Stations: []
Expected: Every single nurse sees it
```

---

## Summary

**Key Takeaway:** Empty = "I don't care", Specified = "Must match exactly"

The system is flexible:
- Want everyone? Leave everything empty
- Want one unit? Specify unit, leave others empty
- Want specific people? Specify all three constraints

All constraints work together with AND logic to create precise or broad targeting as needed.
