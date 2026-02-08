<?php

namespace App\Filament\Chief\Resources\HeadNurses\Pages;

use App\Filament\Chief\Resources\HeadNurses\HeadNurseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHeadNurse extends EditRecord
{
    protected static string $resource = HeadNurseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
