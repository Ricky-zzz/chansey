<?php

namespace App\Filament\Resources\Nurses\Pages;

use App\Filament\Resources\Nurses\NurseResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\EditAction;

class ViewNurse extends ViewRecord
{
    protected static string $resource = NurseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
