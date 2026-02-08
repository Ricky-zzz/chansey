<?php

namespace App\Filament\Resources\NurseTypes\Pages;

use App\Filament\Resources\NurseTypes\NurseTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNurseType extends EditRecord
{
    protected static string $resource = NurseTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
