<?php

namespace App\Filament\Chief\Resources\StaffNurses\Pages;

use App\Filament\Chief\Resources\StaffNurses\StaffNurseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStaffNurse extends EditRecord
{
    protected static string $resource = StaffNurseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
