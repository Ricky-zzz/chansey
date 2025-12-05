<?php

namespace App\Filament\Resources\GeneralServices\Pages;

use App\Filament\Resources\GeneralServices\GeneralServiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGeneralServices extends ListRecords
{
    protected static string $resource = GeneralServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
