<?php

namespace App\Filament\Chief\Resources\Memos\Pages;

use App\Filament\Chief\Resources\Memos\MemoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMemo extends EditRecord
{
    protected static string $resource = MemoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->record;

        // Load existing pivot data into form inputs
        $data['target_roles_input'] = $record->targetRoles->pluck('role')->toArray();
        $data['target_units_input'] = $record->targetUnits->pluck('id')->toArray();
        $data['target_stations_input'] = $record->targetStations->pluck('id')->toArray();

        return $data;
    }

    protected function afterSave(): void
    {
        $record = $this->record;
        $data = $this->data;

        // Sync pivot tables
        if (isset($data['target_roles_input'])) {
            $record->syncTargetRoles($data['target_roles_input'] ?? []);
        }

        if (isset($data['target_units_input'])) {
            $record->targetUnits()->sync($data['target_units_input'] ?? []);
        }

        if (isset($data['target_stations_input'])) {
            $record->targetStations()->sync($data['target_stations_input'] ?? []);
        }
    }
}
