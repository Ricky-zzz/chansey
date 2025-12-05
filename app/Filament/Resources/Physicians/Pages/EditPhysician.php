<?php

namespace App\Filament\Resources\Physicians\Pages;

use App\Filament\Resources\Physicians\PhysicianResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class EditPhysician extends EditRecord
{
    protected static string $resource = PhysicianResource::class;

        protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! empty($data['password'])) {
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
