<?php

namespace App\Filament\Chief\Resources\Memos\Pages;

use App\Filament\Chief\Resources\Memos\MemoResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateMemo extends CreateRecord
{
    protected static string $resource = MemoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by_user_id'] = Auth::id();

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        $data = $this->data;

        // Sync pivot tables
        if (!empty($data['target_roles_input'])) {
            $record->syncTargetRoles($data['target_roles_input']);
        }

        if (!empty($data['target_units_input'])) {
            $record->targetUnits()->sync($data['target_units_input']);
        }

        if (!empty($data['target_stations_input'])) {
            $record->targetStations()->sync($data['target_stations_input']);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
