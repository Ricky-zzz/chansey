<?php

namespace App\Filament\Resources\Accountants\Pages;

use App\Filament\Resources\Accountants\AccountantResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class EditAccountant extends EditRecord
{
    protected static string $resource = AccountantResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $userUpdates = [];

        if (! empty($data['password'])) {
            $userUpdates['password'] = Hash::make($data['password']);
        }

        if (isset($data['first_name']) || isset($data['last_name'])) {
            $firstName = $data['first_name'] ?? $record->first_name;
            $lastName = $data['last_name'] ?? $record->last_name;
            $userUpdates['name'] = $firstName . ' ' . $lastName;
        }

        if (! empty($userUpdates)) {
            $record->user->update($userUpdates);
        }

        unset($data['password']);

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
