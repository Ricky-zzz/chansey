<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Admission;
use App\Models\AdmissionBillingInfo;
use App\Models\Bed;
use App\Models\Station;
use App\Models\Physician;
use App\Models\User;
use App\Models\PatientMovement;
use Illuminate\Support\Facades\Hash;

class PatientAdmissionSeeder extends Seeder
{
    public function run(): void
    {
        // Get all stations except OPD
        $stations = Station::whereNotIn('station_code', ['OPD'])->get();

        // Get all physicians
        $physicians = Physician::all();

        // Sample patient data for variety
        $firstNames = ['John', 'Maria', 'Jose', 'Angela', 'Roberto', 'Carmen', 'Manuel', 'Rosa', 'Miguel', 'Teresa', 'Diego', 'Lucia', 'Fernando', 'Alejandra', 'Luis', 'Isabelle', 'Carlos', 'Monica', 'Antonio', 'Gabriela'];
        $lastNames = ['Santos', 'Cruz', 'Reyes', 'Flores', 'Garcia', 'Lopez', 'Martinez', 'Hernandez', 'Morales', 'Ruiz', 'Delgado', 'Castillo', 'Navarro', 'Ortiz', 'Ramirez'];

        $civilStatuses = ['Single', 'Married', 'Widowed', 'Separated'];
        $religions = ['Catholic', 'Protestant', 'Islam', 'Buddhism', 'None'];
        $nationalities = ['Filipino', 'American', 'Chinese', 'Japanese'];
        $caseTypes = ['Medical', 'Surgical', 'Obstetric', 'Pediatric'];
        $modesOfArrival = ['Ambulance', 'Walk-In', 'Transferred', 'Private Vehicle'];

        // Chief complaints for variety
        $chiefComplaints = [
            'Chest pain and shortness of breath',
            'Severe abdominal pain',
            'High fever and cough',
            'Head injury from accident',
            'Severe laceration and bleeding',
            'Diabetic emergency',
            'Acute respiratory distress',
            'Severe hypertension',
            'Pregnancy complications',
            'Pediatric seizures',
        ];

        // Initial diagnoses
        $diagnoses = [
            'Acute Myocardial Infarction',
            'Acute Abdomen - Appendicitis',
            'Pneumonia with sepsis',
            'Traumatic brain injury',
            'Severe laceration - wound care',
            'Diabetic ketoacidosis',
            'Acute respiratory failure',
            'Hypertensive crisis',
            'Preeclampsia',
            'Febrile seizure',
        ];

        // Random admitting clerk (nurses who can admit)
        $admittingNurses = User::where('user_type', 'nurse')->pluck('id')->toArray();
        $patientCounter = 0;

        foreach ($stations as $station) {
            // Create 5 patients per station
            for ($p = 0; $p < 5; $p++) {
                $patientCounter++;

                // Generate unique patient ID
                $year = date('Y');
                $lastPatient = Patient::where('patient_unique_id', 'like', "P-{$year}-%")->latest('id')->first();
                $nextNum = $lastPatient ? intval(explode('-', $lastPatient->patient_unique_id)[2]) + 1 : 1;
                $pid = "P-{$year}-" . str_pad($nextNum, 5, '0', STR_PAD_LEFT);

                // Random patient data
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                $middleInitial = chr(65 + rand(0, 25)); // Random letter A-Z

                // Create patient
                $patient = Patient::create([
                    'patient_unique_id' => $pid,
                    'created_by_user_id' => $admittingNurses[array_rand($admittingNurses)],
                    'first_name' => $firstName,
                    'middle_name' => $middleInitial,
                    'last_name' => $lastName,
                    'date_of_birth' => now()->subYears(rand(18, 85)),
                    'sex' => rand(0, 1) ? 'Male' : 'Female',
                    'civil_status' => $civilStatuses[array_rand($civilStatuses)],
                    'nationality' => $nationalities[array_rand($nationalities)],
                    'religion' => $religions[array_rand($religions)],
                    'address_permanent' => rand(100, 999) . ' ' . ['Main St', 'Second Ave', 'Third Lane', 'Oak Road'][array_rand(['Main St', 'Second Ave', 'Third Lane', 'Oak Road'])] . ', City',
                    'address_present' => rand(100, 999) . ' ' . ['Main St', 'Second Ave', 'Third Lane', 'Oak Road'][array_rand(['Main St', 'Second Ave', 'Third Lane', 'Oak Road'])] . ', City',
                    'contact_number' => '09' . rand(100000000, 999999999),
                    'email' => strtolower($firstName . '.' . $lastName) . '@email.com',
                    'emergency_contact_name' => 'Family Member',
                    'emergency_contact_relationship' => ['Spouse', 'Parent', 'Sibling', 'Child'][array_rand(['Spouse', 'Parent', 'Sibling', 'Child'])],
                    'emergency_contact_number' => '09' . rand(100000000, 999999999),
                    'philhealth_number' => rand(100000000000000, 999999999999999),
                    'senior_citizen_id' => rand(0, 1) ? 'SC-' . rand(1000, 9999) : null,
                ]);

                // Get available bed in this station
                $bed = Bed::whereHas('room', function ($q) use ($station) {
                    $q->where('station_id', $station->id);
                })->where('status', 'Available')->first();

                if (!$bed) {
                    // If no available bed, create one (shouldn't happen with seeder setup)
                    continue;
                }

                // Generate admission number
                $admYear = date('Y');
                $lastAdm = Admission::where('admission_number', 'like', "ADM-{$admYear}-%")->latest('id')->first();
                $nextAdmNum = $lastAdm ? intval(explode('-', $lastAdm->admission_number)[2]) + 1 : 1;
                $admNumber = "ADM-{$admYear}-" . str_pad($nextAdmNum, 5, '0', STR_PAD_LEFT);

                // Create admission
                $admission = Admission::create([
                    'patient_id' => $patient->id,
                    'admission_number' => $admNumber,
                    'admission_date' => now()->subDays(rand(0, 10)),
                    'status' => 'Admitted',
                    'admitting_clerk_id' => $admittingNurses[array_rand($admittingNurses)],
                    'attending_physician_id' => $physicians->random()->id,
                    'station_id' => $station->id,
                    'bed_id' => $bed->id,
                    'admission_type' => 'Inpatient',
                    'case_type' => $caseTypes[array_rand($caseTypes)],
                    'mode_of_arrival' => $modesOfArrival[array_rand($modesOfArrival)],
                    'chief_complaint' => $chiefComplaints[array_rand($chiefComplaints)],
                    'initial_diagnosis' => $diagnoses[array_rand($diagnoses)],
                    'initial_vitals' => [
                        'bp' => (rand(100, 180)) . '/' . (rand(60, 120)),
                        'temp' => (36 + rand(0, 3)) . '.' . rand(0, 9),
                        'hr' => rand(60, 130),
                        'pr' => rand(60, 130),
                        'rr' => rand(12, 30),
                        'o2' => rand(90, 99),
                        'height' => rand(150, 185),
                        'weight' => rand(45, 95),
                    ],
                    'known_allergies' => rand(0, 1) ? ['Penicillin', 'Iodine'] : [],
                ]);

                // Mark bed as occupied
                $bed->update(['status' => 'Occupied']);

                // Create patient movement to track room expenses
                PatientMovement::create([
                    'admission_id' => $admission->id,
                    'room_id' => $bed->room_id,
                    'bed_id' => $bed->id,
                    'room_price' => $bed->room->price ?? 0,
                    'started_at' => $admission->admission_date,
                    'ended_at' => null,
                ]);

                // Create billing info
                AdmissionBillingInfo::create([
                    'admission_id' => $admission->id,
                    'payment_type' => ['Cash', 'Insurance', 'HMO', 'PhilHealth'][array_rand(['Cash', 'Insurance', 'HMO', 'PhilHealth'])],
                    'primary_insurance_provider' => rand(0, 1) ? 'PhilHealth' : 'Private Insurance',
                    'policy_number' => 'POL-' . rand(100000, 999999),
                    'approval_code' => 'APP-' . rand(10000, 99999),
                    'guarantor_name' => 'Family Guarantor',
                    'guarantor_relationship' => ['Spouse', 'Parent', 'Adult Child'][array_rand(['Spouse', 'Parent', 'Adult Child'])],
                    'guarantor_contact' => '09' . rand(100000000, 999999999),
                ]);
            }
        }

        $this->command->info("Successfully seeded " . ($stations->count() * 5) . " patients and admissions!");
    }
}
