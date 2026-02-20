<?php

namespace App\Filament\Supervisor\Resources\Memos\Pages;

use App\Filament\Supervisor\Resources\Memos\MemoResource;
use App\Models\Station;
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
        $supervisor = Auth::user()->nurse;

        // Sync target roles
        if (!empty($data['target_roles_input'])) {
            $record->syncTargetRoles($data['target_roles_input']);
        }

        // Force target unit to supervisor's unit
        $record->targetUnits()->sync([$supervisor->unit_id]);

        // Sync target stations (already filtered to unit in form)
        if (!empty($data['target_stations_input'])) {
            $record->targetStations()->sync($data['target_stations_input']);
        } else {
            // If empty, target all stations in supervisor's unit
            $allStationIds = Station::where('unit_id', $supervisor->unit_id)->pluck('id')->toArray();
            $record->targetStations()->sync($allStationIds);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
