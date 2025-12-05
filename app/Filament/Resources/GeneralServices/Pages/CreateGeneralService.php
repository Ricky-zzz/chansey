<?php

namespace App\Filament\Resources\GeneralServices\Pages;

use App\Filament\Resources\GeneralServices\GeneralServiceResource;
use App\Models\User;
use App\Services\BadgeGenerator;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class CreateGeneralService extends CreateRecord
{
    protected static string $resource = GeneralServiceResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $badgeId = BadgeGenerator::generate('general_service', $data['first_name'], $data['last_name']);

        // 2. Create User
        $user = User::create([
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'badge_id' => $badgeId,
            'email' => strtolower($badgeId) . '@chansey.local',
            'password' => Hash::make($data['password']),
            'user_type' => 'general_service', 
        ]);

        unset($data['password']);

        return static::getModel()::create([
            'user_id' => $user->id,
            'employee_id' => $badgeId,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'assigned_area' => $data['assigned_area'],
            'shift_start' => $data['shift_start'],
            'shift_end' => $data['shift_end'],
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}