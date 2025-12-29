<?php

namespace App\Filament\Resources\Pharmacists\Pages;

use App\Filament\Resources\Pharmacists\PharmacistResource;
use App\Models\User;
use App\Services\BadgeGenerator;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class CreatePharmacist extends CreateRecord
{
    protected static string $resource = PharmacistResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Extract name parts from full_name for badge generation
        $nameParts = explode(' ', $data['full_name'], 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? $firstName;

        $badgeId = BadgeGenerator::generate('pharmacist', $firstName, $lastName);

        // Create User
        $user = User::create([
            'name' => $data['full_name'],
            'badge_id' => $badgeId,
            'email' => strtolower($badgeId) . '@chansey.local',
            'password' => Hash::make($data['password']),
            'user_type' => 'pharmacist',
        ]);

        unset($data['password']);

        return static::getModel()::create([
            'user_id' => $user->id,
            'employee_id' => $badgeId,
            'full_name' => $data['full_name'],
            'license_number' => $data['license_number'],
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
