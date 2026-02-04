<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;
use App\Models\Nurse;
use App\Models\GeneralService;
use App\Models\Physician;
use App\Models\Pharmacist; // Make sure this model exists
use App\Models\Accountant;
use App\Models\Station;
use App\Models\Room;
use App\Models\Bed;
use App\Models\Department;
use App\Models\Medicine;
use App\Models\InventoryItem;
use App\Models\HospitalFee;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password'); // Unified password

        // ==========================================
        // 0. CREATE DEPARTMENTS (Prerequisite)
        // ==========================================
        $deptNames = ['Cardiology', 'Pediatrics', 'Neurology', 'Internal Medicine', 'Surgery', 'OB-GYN'];
        $depts = [];
        foreach ($deptNames as $name) {
            $depts[$name] = Department::create(['name' => $name])->id;
        }

        // ==========================================
        // 1. ADMIN
        // ==========================================
        $adminUser = User::create([
            'name' => 'System Admin',
            'email' => 'admin@chansey.test',
            'password' => $password,
            'user_type' => 'admin',
            'badge_id' => 'ADM-001',
        ]);
        Admin::create([
            'user_id' => $adminUser->id,
            'full_name' => 'Super Administrator',
        ]);

        // ==========================================
        // 2. ACCOUNTANT (Billing)
        // ==========================================
        $accUser = User::create([
            'name' => 'Gwen Perez',
            'email' => 'gwen.perez@chansey.test',
            'password' => $password,
            'user_type' => 'accountant',
            'badge_id' => 'ACC-GP-001',
        ]);
        Accountant::create([
            'user_id' => $accUser->id,
            'employee_id' => 'ACC-GP-001',
            'first_name' => 'Gwen',
            'last_name' => 'Perez',
        ]);

        // ==========================================
        // 3. PHARMACIST (Pharmacy)
        // ==========================================
        $pharmUser = User::create([
            'name' => 'Gabriel Hosmillo',
            'email' => 'gabriel.hosmillo@chansey.test',
            'password' => $password,
            'user_type' => 'pharmacist',
            'badge_id' => 'PHR-GH-001',
        ]);
        Pharmacist::create([
            'user_id' => $pharmUser->id,
            'employee_id' => 'PHR-GH-001',
            'full_name' => 'Gabriel Hosmillo',
            'license_number' => '0123456788'
        ]);

        // ==========================================
        // 4. NURSES
        // ==========================================
        // A. Admitting Nurse
        $admitNurse = User::create([
            'name' => 'Steph Torres',
            'email' => 'steph.torres@chansey.test',
            'password' => $password,
            'user_type' => 'nurse',
            'badge_id' => 'NUR-ST-001',
        ]);
        Nurse::create([
            'user_id' => $admitNurse->id,
            'employee_id' => 'NUR-ST-001',
            'first_name' => 'Steph',
            'last_name' => 'Torres',
            'license_number' => 'RN-1001',
            'designation' => 'Admitting',
            'station_id' => null,
            'is_head_nurse' => false,
        ]);

        // B. Head Nurse - Admitting (Janaih Budy)
        $headAdmitNurse = User::create([
            'name' => 'Janaih Budy',
            'email' => 'janaih.budy@chansey.test',
            'password' => $password,
            'user_type' => 'nurse',
            'badge_id' => 'NUR-JB-001',
        ]);
        Nurse::create([
            'user_id' => $headAdmitNurse->id,
            'employee_id' => 'NUR-JB-001',
            'first_name' => 'Janaih',
            'last_name' => 'Budy',
            'license_number' => 'RN-1002',
            'designation' => 'Admitting',
            'station_id' => null,
            'is_head_nurse' => true,
        ]);

        // C. Clinical Nurse (North Wing)
        $clinNurse = User::create([
            'name' => 'Riovel Dane',
            'email' => 'riovel.dane@chansey.test',
            'password' => $password,
            'user_type' => 'nurse',
            'badge_id' => 'NUR-RD-001',
        ]);

        // D. Head Nurse - East Wing (Althea Marie)
        $headEastNurse = User::create([
            'name' => 'Althea Marie',
            'email' => 'althea.marie@chansey.test',
            'password' => $password,
            'user_type' => 'nurse',
            'badge_id' => 'NUR-AM-001',
        ]);

        // ==========================================
        // 5. GENERAL SERVICE
        // ==========================================
        $gsUser = User::create([
            'name' => 'Firan Maravilla',
            'email' => 'firan@chansey.test',
            'password' => $password,
            'user_type' => 'general_service',
            'badge_id' => 'SVC-FM-001',
        ]);
        GeneralService::create([
            'user_id' => $gsUser->id,
            'employee_id' => 'SVC-FM-001',
            'first_name' => 'Firan',
            'last_name' => 'Maravilla',
            'assigned_area' => 'Lobby / Wards',
            'shift_start' => '08:00:00',
            'shift_end' => '17:00:00',
        ]);

        // ==========================================
        // 6. PHYSICIANS (Updated with Department IDs)
        // ==========================================
        $doctors = [
            ['first' => 'Shimi', 'last' => 'Jallores', 'dept_name' => 'Cardiology', 'id' => 'DOC-SJ-001'],
            ['first' => 'Bato', 'last' => 'Jallores', 'dept_name' => 'Pediatrics', 'id' => 'DOC-BJ-001'],
            ['first' => 'Loyd', 'last' => 'Jallores', 'dept_name' => 'Neurology', 'id' => 'DOC-LJ-001'],
        ];

        foreach ($doctors as $doc) {
            $u = User::create([
                'name' => "Dr. {$doc['first']} {$doc['last']}",
                'email' => strtolower($doc['first']) . "@chansey.test",
                'password' => $password,
                'user_type' => 'physician',
                'badge_id' => $doc['id'],
            ]);

            Physician::create([
                'user_id' => $u->id,
                'employee_id' => $doc['id'],
                'first_name' => $doc['first'],
                'last_name' => $doc['last'],
                'department_id' => $depts[$doc['dept_name']], // Link to Dept ID
                'employment_type' => 'Consultant',
            ]);
        }

        // ==========================================
        // 7. INFRASTRUCTURE & Clinical Nurse Link
        // ==========================================
        $wings = [
            ['name' => 'North Wing', 'code' => 'NW', 'floor' => '1st Floor'],
            ['name' => 'East Wing',  'code' => 'EW', 'floor' => '1st Floor'],
            ['name' => 'West Wing',  'code' => 'WW', 'floor' => '2nd Floor'],
            ['name' => 'South Wing', 'code' => 'SW', 'floor' => '2nd Floor'],
        ];

        $firstStationId = null;

        foreach ($wings as $i => $wing) {
            $station = Station::create([
                'station_name' => $wing['name'],
                'station_code' => $wing['code'],
                'floor_location' => $wing['floor'],
            ]);

            if ($i === 0) $firstStationId = $station->id; // Save North Wing ID

            // Room logic (Same as before)
            $roomNum = ($i + 1) . "01";
            $room = Room::create([
                'station_id' => $station->id,
                'room_number' => $roomNum,
                'room_type' => 'Ward',
                'capacity' => 4,
                'price_per_night' => 1500.00,
                'status' => 'Active',
            ]);

            for ($b = 1; $b <= 4; $b++) {
                $letter = chr(64 + $b);
                Bed::create([
                    'room_id' => $room->id,
                    'bed_code' => "{$station->station_code}-{$room->room_number}-{$letter}",
                    'status' => 'Available',
                ]);
            }
        }
        Station::create([
            'station_name' => 'Outpatient / Lobby',
            'station_code' => 'OPD',
            'floor_location' => 'Ground Floor',
        ]);

        // Link the Clinical Nurse to East Wing (Station 2)
        Nurse::create([
            'user_id' => $clinNurse->id,
            'employee_id' => 'NUR-RD-001',
            'first_name' => 'Riovel',
            'last_name' => 'Dane',
            'license_number' => '21212123',
            'designation' => 'Clinical',
            'station_id' => 2,
            'is_head_nurse' => false,
        ]);

        // Link the Head Nurse (Althea Marie) to East Wing (Station 2)
        Nurse::create([
            'user_id' => $headEastNurse->id,
            'employee_id' => 'NUR-AM-001',
            'first_name' => 'Althea',
            'last_name' => 'Marie',
            'license_number' => 'RN-1003',
            'designation' => 'Clinical',
            'station_id' => 2,
            'is_head_nurse' => true,
        ]);

        // ==========================================
        // 8. MEDICINES (Pharmacy Stock)
        // ==========================================
        $meds = [
            ['Biogesic', 'Paracetamol', '500mg', 'Tablet', 5.00],
            ['Neozep', 'Phenylephrine', '10mg', 'Tablet', 7.00],
            ['Amoxil', 'Amoxicillin', '500mg', 'Capsule', 15.00],
            ['Solmux', 'Carbocisteine', '500mg', 'Capsule', 12.00],
            ['PNSS 1L', 'Sodium Chloride', '1L', 'IV Bag', 150.00],
        ];

        foreach ($meds as $m) {
            Medicine::create([
                'brand_name' => $m[0],
                'generic_name' => $m[1],
                'dosage' => $m[2],
                'form' => $m[3],
                'price' => $m[4],
                'stock_on_hand' => 100, // Start with stock
                'expiry_date' => '2026-01-01',
            ]);
        }

        // ==========================================
        // 9. INVENTORY ITEMS (Gen Service Stock)
        // ==========================================
        $items = [
            ['Admission Kit', 'Hygiene', 350.00],
            ['Extra Pillow', 'Linens', 100.00],
            ['Wool Blanket', 'Linens', 150.00],
            ['Nebulizer Kit', 'Medical', 150.00],
            ['Underpad', 'Medical', 50.00],
        ];

        foreach ($items as $item) {
            InventoryItem::create([
                'item_name' => $item[0],
                'category' => $item[1],
                'price' => $item[2],
                'quantity' => 50,
            ]);
        }

        // ==========================================
        // 10. HOSPITAL FEES (Accountant Menu)
        // ==========================================
        $fees = [
            ['Ambulance Service', 2500.00, 'per_use'],
            ['Emergency Room Fee', 1000.00, 'flat'],
            ['Oxygen Tank Use', 500.00, 'per_hour'],
            ['Medical Certificate', 150.00, 'flat'],
            ['Electricity / TV', 100.00, 'per_day'],
        ];

        foreach ($fees as $fee) {
            HospitalFee::create([
                'name' => $fee[0],
                'price' => $fee[1],
                'unit' => $fee[2],
                'is_active' => true,
            ]);
        }
    }
}
