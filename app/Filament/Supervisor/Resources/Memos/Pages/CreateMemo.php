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
        $supervisor = Auth::user()->nurse;

        // Always force target_units to supervisor's unit
        $data['target_units'] = [(int)$supervisor->unit_id];

        // If target_stations is empty, populate with all stations under this unit
        if (empty($data['target_stations']) || !is_array($data['target_stations'])) {
            $allStationIds = Station::where('unit_id', $supervisor->unit_id)
                ->pluck('id')
                ->map(fn($id) => (int)$id)
                ->toArray();
            $data['target_stations'] = $allStationIds;
        }

        return $data;
    }
}
