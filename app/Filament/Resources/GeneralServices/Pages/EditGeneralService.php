<?php

namespace App\Filament\Resources\GeneralServices\Pages;

use App\Filament\Resources\GeneralServices\GeneralServiceResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\DeleteAction;

use Illuminate\Support\Facades\Hash;

class EditGeneralService extends EditRecord
{
    protected static string $resource = GeneralServiceResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // 1. Handle Password Update
        if (! empty($data['password'])) {
            $record->user->update([
                'password' => Hash::make($data['password']),
            ]);
        }

        unset($data['password']);

        // 2. Update Profile
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