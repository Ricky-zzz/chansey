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

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! empty($data['password'])) {
            // Update the linked User account
            $record->user->update([
                'password' => Hash::make($data['password']),
            ]);
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
