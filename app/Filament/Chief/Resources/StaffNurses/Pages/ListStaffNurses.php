<?php

namespace App\Filament\Chief\Resources\StaffNurses\Pages;

use App\Filament\Chief\Resources\StaffNurses\StaffNurseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStaffNurses extends ListRecords
{
    protected static string $resource = StaffNurseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
