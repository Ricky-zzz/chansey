<?php

namespace App\Filament\Maintenance\Resources\Stations\Pages;

use App\Filament\Maintenance\Resources\Stations\StationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStation extends EditRecord
{
    protected static string $resource = StationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
