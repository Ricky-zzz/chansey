<?php

namespace App\Filament\Resources\Pharmacists\Pages;

use App\Filament\Resources\Pharmacists\PharmacistResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class EditPharmacist extends EditRecord
{
    protected static string $resource = PharmacistResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $userUpdates = [];

        if (! empty($data['password'])) {
            $userUpdates['password'] = Hash::make($data['password']);
        }

        if (isset($data['full_name'])) {
            $userUpdates['name'] = $data['full_name'];
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
