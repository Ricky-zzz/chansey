<?php

namespace App\Filament\Supervisor\Resources\StationHeads\Pages;

use App\Filament\Supervisor\Resources\StationHeads\StationHeadResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStationHeads extends ListRecords
{
    protected static string $resource = StationHeadResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
