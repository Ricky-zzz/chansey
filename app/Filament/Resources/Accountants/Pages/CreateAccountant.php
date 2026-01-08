<?php

namespace App\Filament\Resources\Accountants\Pages;

use App\Filament\Resources\Accountants\AccountantResource;
use App\Models\User;
use App\Services\BadgeGenerator;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class CreateAccountant extends CreateRecord
{
    protected static string $resource = AccountantResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $badgeId = BadgeGenerator::generate('accountant', $data['first_name'], $data['last_name']);

        // Create User
        $user = User::create([
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'badge_id' => $badgeId,
            'email' => strtolower($badgeId) . '@chansey.local',
            'password' => Hash::make($data['password']),
            'user_type' => 'accountant',
        ]);

        unset($data['password']);

        return static::getModel()::create([
            'user_id' => $user->id,
            'employee_id' => $badgeId,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
