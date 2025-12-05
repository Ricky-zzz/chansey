<?php

namespace App\Filament\Resources\Physicians\Pages;

use App\Filament\Resources\Physicians\PhysicianResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use App\Services\BadgeGenerator;
use App\Models\User;

class CreatePhysician extends CreateRecord
{
    protected static string $resource = PhysicianResource::class;

        protected function handleRecordCreation(array $data): Model
    {
        $badgeId = BadgeGenerator::generate('physician', $data['first_name'], $data['last_name']);

        $user = User::create([
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'badge_id' => $badgeId,
            'email' => strtolower($badgeId) . '@chansey.local',
            'password' => Hash::make($data['password']),
            'user_type' => 'physician',
        ]);

        return static::getModel()::create([
            'user_id' => $user->id,
            'employee_id' => $badgeId,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'specialization' => $data['specialization'],
            'employment_type' => $data['employment_type'],
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
