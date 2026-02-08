<?php

namespace App\Filament\Resources\Nurses\Pages;

use App\Filament\Resources\Nurses\NurseResource;
use App\Models\User;
use App\Services\BadgeGenerator;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class CreateNurse extends CreateRecord
{
    protected static string $resource = NurseResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $badgeId = BadgeGenerator::generate('nurse', $data['first_name'], $data['last_name']);

        // Extract password and profile image before creating nurse
        $password = $data['password'] ?? null;
        $profileImagePath = $data['user']['profile_image_path'] ?? null;

        // Remove password and any user-related fields from data to avoid duplication
        unset($data['password']);
        unset($data['user_id']);
        unset($data['user']);

        // Create the user first
        $user = User::create([
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'badge_id' => $badgeId,
            'email' => strtolower($badgeId) . '@chansey.local',
            'password' => Hash::make($password),
            'user_type' => 'nurse',
            'profile_image_path' => $profileImagePath,
        ]);

        // Add user_id and employee_id to the cleaned data
        $data['user_id'] = $user->id;
        $data['employee_id'] = $badgeId;

        // Create the nurse record with only the fields it needs
        $nurse = static::getModel()::create($data);

        return $nurse;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
