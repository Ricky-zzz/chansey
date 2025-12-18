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

        $user = User::create([
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'badge_id' => $badgeId,
            'email' => strtolower($badgeId) . '@chansey.local',
            'password' => Hash::make($data['password']),
            'user_type' => 'nurse',
        ]);

        return static::getModel()::create([
            'user_id' => $user->id,
            'employee_id' => $badgeId,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'license_number' => $data['license_number'],
            'designation' => $data['designation'],
            'station_id' => $data['station_id'] ?? null,
            'shift_start' => $data['shift_start'],
            'shift_end' => $data['shift_end'],
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
