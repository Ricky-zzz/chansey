<?php

namespace App\Filament\Resources\Nurses\Pages;

use App\Filament\Resources\Nurses\NurseResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class EditNurse extends EditRecord
{
    protected static string $resource = NurseResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load the user's profile image into the nested form field
        $data['user'] = [
            'profile_image_path' => $this->record->user?->profile_image_path,
        ];

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $userUpdates = [];

        if (! empty($data['password'])) {
            $userUpdates['password'] = Hash::make($data['password']);
        }

        if (isset($data['first_name']) && isset($data['last_name'])) {
            $userUpdates['name'] = $data['first_name'] . ' ' . $data['last_name'];
        }

        // Handle nested user.profile_image_path
        if (isset($data['user']['profile_image_path'])) {
            $userUpdates['profile_image_path'] = $data['user']['profile_image_path'];
        }

        if (! empty($userUpdates)) {
            $record->user->update($userUpdates);
        }

        unset($data['password']);
        unset($data['user']);

        $record->update($data);

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
