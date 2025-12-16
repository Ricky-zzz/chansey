<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;
use App\Models\Nurse;
use App\Models\GeneralService; 
use App\Models\Physician;
use App\Models\Station;
use App\Models\Room;
use App\Models\Bed;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password'); 

        // ==========================================
        // 1. ADMIN ACCOUNT
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
        // 2. ADMITTING NURSE (Steph Torres)
        // ==========================================
        $nurseUser = User::create([
            'name' => 'Steph Torres',
            'email' => 'steph@chansey.test',
            'password' => $password,
            'user_type' => 'nurse',
            'badge_id' => 'NUR-ST-001',
        ]);
        Nurse::create([
            'user_id' => $nurseUser->id,
            'employee_id' => 'NUR-ST-001',
            'first_name' => 'Steph',
            'last_name' => 'Torres',
            'license_number' => 'RN-1001',
            'designation' => 'Admitting', // 
            'station_assignment' => 'North Wing',
            'shift_start' => '06:00:00',
            'shift_end' => '14:00:00',
        ]);

        // ==========================================
        // 3. GENERAL SERVICE (Firan Maravilla)
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
        // 4. PHYSICIANS (The Jallores Trio)
        // ==========================================
        
        $doctors = [
            ['first' => 'Shimi', 'last' => 'Jallores', 'dept' => 'Cardiology', 'id' => 'DOC-SJ-001'],
            ['first' => 'Bato', 'last' => 'Jallores', 'dept' => 'Pediatrics', 'id' => 'DOC-BJ-001'],
            ['first' => 'Loyd', 'last' => 'Jallores', 'dept' => 'Neurology', 'id' => 'DOC-LJ-001'],
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
                'specialization' => $doc['dept'],
                'employment_type' => 'Consultant',
            ]);
        }

        // ==========================================
        // 5. INFRASTRUCTURE (4 Wings, 1 Room Each, 4 Beds Each)
        // ==========================================

        $wings = [
            ['name' => 'North Wing', 'code' => 'NW', 'floor' => '1st Floor'],
            ['name' => 'East Wing',  'code' => 'EW', 'floor' => '1st Floor'],
            ['name' => 'West Wing',  'code' => 'WW', 'floor' => '2nd Floor'],
            ['name' => 'South Wing', 'code' => 'SW', 'floor' => '2nd Floor'],
        ];

        foreach ($wings as $i => $wing) {
            // 1. Create Station
            $station = Station::create([
                'station_name' => $wing['name'],
                'station_code' => $wing['code'],
                'floor_location' => $wing['floor'],
            ]);

            // 2. Create Room (e.g., 101, 201, 301, 401)
            $roomNum = ($i + 1) . "01"; 
            
            $room = Room::create([
                'station_id' => $station->id,
                'room_number' => $roomNum,
                'room_type' => 'Ward',
                'capacity' => 4,
                'status' => 'Active',
            ]);


            for ($b = 1; $b <= 4; $b++) {
                $letter = chr(64 + $b); // A, B, C, D
                Bed::create([
                    'room_id' => $room->id,
                    'bed_code' => "{$station->station_code}-{$room->room_number}-{$letter}",
                    'status' => 'Available',
                ]);
            }
        }
    }
}