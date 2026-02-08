<?php

namespace App\Filament\Resources\NurseTypes\Pages;

use App\Filament\Resources\NurseTypes\NurseTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNurseTypes extends ListRecords
{
    protected static string $resource = NurseTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
