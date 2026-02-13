<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Nurse;
use App\Models\NurseType;

class FloatingNurseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        // Get the Floating Nurse type ID
        $floatingNurseType = NurseType::where('name', 'Floating Nurse')->first();

        if (!$floatingNurseType) {
            $this->command->error('Floating Nurse type not found. Please run DatabaseSeeder first.');
            return;
        }

        // Dummy names for floating nurses
        $floatingNurses = [
            ['first' => 'Julia', 'last' => 'Santos'],
            ['first' => 'Ramon', 'last' => 'Cruz'],
            ['first' => 'Isabel', 'last' => 'Garcia'],
            ['first' => 'Miguel', 'last' => 'Torres'],
            ['first' => 'Sofia', 'last' => 'Reyes'],
            ['first' => 'Pedro', 'last' => 'Mendoza'],
            ['first' => 'Ana', 'last' => 'Flores'],
            ['first' => 'Luis', 'last' => 'Ramos'],
            ['first' => 'Elena', 'last' => 'Castro'],
            ['first' => 'Marco', 'last' => 'Jimenez'],
        ];

        $counter = 200; // Starting counter for unique IDs

        foreach ($floatingNurses as $nurse) {
            $counter++;
            $badgeId = 'NUR-FLT-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
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
                'license_number' => 'RN-FLT-' . $counter,
                'contact_number' => '09' . rand(100000000, 999999999),
                'designation' => 'Clinical',
                'role_level' => 'Staff',
                'nurse_type_id' => $floatingNurseType->id,
                'date_hired' => now()->subMonths(rand(1, 24)),
                'station_id' => null, // Floating nurses start unassigned
                'shift_schedule_id' => null,
            ]);
        }

        $this->command->info('Created 10 floating nurses successfully.');
    }
}
