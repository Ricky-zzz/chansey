<?php

namespace App\Filament\Chief\Resources\HeadNurses\Pages;

use App\Filament\Chief\Resources\HeadNurses\HeadNurseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHeadNurses extends ListRecords
{
    protected static string $resource = HeadNurseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
