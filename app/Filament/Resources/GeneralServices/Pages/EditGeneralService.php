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
        $userUpdates = [];

        if (! empty($data['password'])) {
            $userUpdates['password'] = Hash::make($data['password']);
        }

        if (isset($data['first_name']) && isset($data['last_name'])) {
            $userUpdates['name'] = $data['first_name'] . ' ' . $data['last_name'];
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