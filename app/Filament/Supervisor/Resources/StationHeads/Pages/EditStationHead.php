<?php

namespace App\Filament\Supervisor\Resources\StationHeads\Pages;

use App\Filament\Supervisor\Resources\StationHeads\StationHeadResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStationHead extends EditRecord
{
    protected static string $resource = StationHeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
