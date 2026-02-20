<?php

namespace App\Filament\Supervisor\Resources\Memos\Pages;

use App\Filament\Supervisor\Resources\Memos\MemoResource;
use App\Models\Station;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

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
        $data['target_stations_input'] = $record->targetStations->pluck('id')->toArray();

        return $data;
    }

    protected function afterSave(): void
    {
        $record = $this->record;
        $data = $this->data;
        $supervisor = Auth::user()->nurse;

        // Sync target roles
        if (isset($data['target_roles_input'])) {
            $record->syncTargetRoles($data['target_roles_input'] ?? []);
        }

        // Force target unit to supervisor's unit
        $record->targetUnits()->sync([$supervisor->unit_id]);

        // Sync target stations
        if (isset($data['target_stations_input'])) {
            $record->targetStations()->sync($data['target_stations_input'] ?? []);
        }
    }
}
