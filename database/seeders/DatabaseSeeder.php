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
use App\Models\ShiftSchedule;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password'); // Unified password

        // ==========================================
        // 0. CREATE DEPARTMENTS (Prerequisite)
        // ==========================================
        $deptNames = ['Cardiology', 'Pediatrics', 'Neurology', 'Internal Medicine', 'Surgery', 'OB-GYN','ENT'];
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

        // 7. INFRASTRUCTURE (Functional Departments)
        // ==========================================

        $stationConfigs = [
            [
                'name' => 'Emergency Room',
                'code' => 'ER',
                'floor' => 'Ground Floor',
                'room_type' => 'ER',
                'capacity' => 10,
                'price' => 1000.00,
                'room_count' => 5
            ],
            [
                'name' => 'Intensive Care Unit',
                'code' => 'ICU',
                'floor' => '2nd Floor',
                'room_type' => 'ICU',
                'capacity' => 8,
                'price' => 5000.00
            ],
            [
                'name' => 'Medical-Surgical Ward',
                'code' => 'MS-WARD',
                'floor' => '3rd Floor',
                'room_type' => 'Ward',
                'capacity' => 6,
                'price' => 1500.00,
                'room_count' => 5
            ],
            [
                'name' => 'OB-GYN Ward',
                'code' => 'OB',
                'floor' => '3rd Floor',
                'room_type' => 'Ward', // Can be Semi-Private if you prefer
                'capacity' => 4,
                'price' => 1500.00
            ],
            [
                'name' => 'Private Wing',
                'code' => 'PVT',
                'floor' => '4th Floor',
                'room_type' => 'Private',
                'capacity' => 1, // Private rooms = 1 bed
                'price' => 4000.00,
                'room_count' => 5 // Create 5 separate rooms for this wing
            ],
        ];

        // Store IDs for Nurse Assignments later
        $stationIds = [];

        foreach ($stationConfigs as $config) {
            // A. Create Station
            $station = Station::create([
                'station_name' => $config['name'],
                'station_code' => $config['code'],
                'floor_location' => $config['floor'],
            ]);

            $stationIds[$config['code']] = $station->id;

            // B. Create Rooms
            // If it's Private Wing, we create multiple single-bed rooms.
            // If it's a Ward/ER, we create one big room with many beds.

            $count = $config['room_count'] ?? 1;

            for ($r = 1; $r <= $count; $r++) {
                $roomNum = $config['code'] . '-' . str_pad($r, 3, '0', STR_PAD_LEFT);

                $room = Room::create([
                    'station_id' => $station->id,
                    'room_number' => $roomNum,
                    'room_type' => $config['room_type'],
                    'capacity' => $config['capacity'],
                    'price_per_night' => $config['price'],
                    'status' => 'Active',
                ]);

                // C. Create Beds
                for ($b = 1; $b <= $config['capacity']; $b++) {
                    $letter = chr(64 + $b); // A, B, C...
                    Bed::create([
                        'room_id' => $room->id,
                        'bed_code' => "{$roomNum}-{$letter}",
                        'status' => 'Available',
                    ]);
                }
            }
        }

        // C. Create the Virtual OPD (No Rooms, No Nurses)
        $opd = Station::create([
            'station_name' => 'Outpatient Dept / Lobby',
            'station_code' => 'OPD',
            'floor_location' => 'Ground Floor',
        ]);
        $stationIds['OPD'] = $opd->id;

        // ==========================================
        // 7B. CREATE CLINICAL NURSES FOR EACH STATION
        // ==========================================
        $stationNurses = [
            'ER' => [
                ['first' => 'Riovel', 'last' => 'Dane', 'head' => false],
                ['first' => 'Althea', 'last' => 'Marie', 'head' => true],
            ],
            'ICU' => [
                ['first' => 'Carlos', 'last' => 'Mendoza', 'head' => false],
                ['first' => 'Maria', 'last' => 'Santos', 'head' => true],
            ],
            'MS-WARD' => [
                ['first' => 'Angelo', 'last' => 'Cruz', 'head' => false],
                ['first' => 'Patricia', 'last' => 'Reyes', 'head' => true],
            ],
            'OB' => [
                ['first' => 'Diana', 'last' => 'Flores', 'head' => false],
                ['first' => 'Carmen', 'last' => 'Garcia', 'head' => true],
            ],
            'PVT' => [
                ['first' => 'Jerome', 'last' => 'Lim', 'head' => false],
                ['first' => 'Beatrice', 'last' => 'Tan', 'head' => true],
            ],
        ];

        $nurseCounter = 100; // For unique IDs
        foreach ($stationNurses as $stationCode => $nurses) {
            foreach ($nurses as $nurse) {
                $nurseCounter++;
                $badgeId = 'NUR-' . strtoupper(substr($nurse['first'], 0, 1) . substr($nurse['last'], 0, 1)) . '-' . $nurseCounter;
                $email = strtolower($nurse['first'] . '.' . $nurse['last']) . '@chansey.test';

                $user = User::create([
                    'name' => $nurse['first'] . ' ' . $nurse['last'],
                    'email' => $email,
                    'password' => $password,
                    'user_type' => 'nurse',
                    'badge_id' => $badgeId,
                ]);

                Nurse::create([
                    'user_id' => $user->id,
                    'employee_id' => $badgeId,
                    'first_name' => $nurse['first'],
                    'last_name' => $nurse['last'],
                    'license_number' => 'RN-' . $nurseCounter,
                    'designation' => 'Clinical',
                    'station_id' => $stationIds[$stationCode],
                    'is_head_nurse' => $nurse['head'],
                ]);
            }
        }

        // ==========================================
        // 8. SHIFT SCHEDULES
        // ==========================================
        $schedules = [
            // M-W-F Schedules (36 hours)
            [
                'name' => 'M-W-F Morning',
                'start_time' => '08:00',
                'end_time' => '16:00',
                'days' => ['monday' => true, 'wednesday' => true, 'friday' => true]
            ],
            [
                'name' => 'M-W-F Night',
                'start_time' => '20:00',
                'end_time' => '08:00',
                'days' => ['monday' => true, 'wednesday' => true, 'friday' => true]
            ],
            // T-TH-S Schedules (36 hours)
            [
                'name' => 'T-TH-S Morning',
                'start_time' => '08:00',
                'end_time' => '16:00',
                'days' => ['tuesday' => true, 'thursday' => true, 'saturday' => true]
            ],
            [
                'name' => 'T-TH-S Night',
                'start_time' => '20:00',
                'end_time' => '08:00',
                'days' => ['tuesday' => true, 'thursday' => true, 'saturday' => true]
            ],
            // SAT-SUN Schedules (12 hours each)
            [
                'name' => 'Weekend Morning',
                'start_time' => '08:00',
                'end_time' => '14:00',
                'days' => ['saturday' => true, 'sunday' => true]
            ],
            [
                'name' => 'Weekend Night',
                'start_time' => '20:00',
                'end_time' => '02:00',
                'days' => ['saturday' => true, 'sunday' => true]
            ],
        ];

        foreach ($schedules as $schedule) {
            $days = $schedule['days'];
            ShiftSchedule::create([
                'name' => $schedule['name'],
                'start_time' => $schedule['start_time'],
                'end_time' => $schedule['end_time'],
                'monday' => $days['monday'] ?? false,
                'tuesday' => $days['tuesday'] ?? false,
                'wednesday' => $days['wednesday'] ?? false,
                'thursday' => $days['thursday'] ?? false,
                'friday' => $days['friday'] ?? false,
                'saturday' => $days['saturday'] ?? false,
                'sunday' => $days['sunday'] ?? false,
            ]);
        }

        // ==========================================
        // 9. MEDICINES (Pharmacy Stock)
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
        // 10. INVENTORY ITEMS (Gen Service Stock)
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
        // 11. HOSPITAL FEES (Accountant Menu)
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

        $this->call(PatientAdmissionSeeder::class);
    }
}
