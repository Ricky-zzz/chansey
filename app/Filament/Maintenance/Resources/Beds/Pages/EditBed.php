<?php

namespace App\Filament\Maintenance\Resources\Beds\Pages;

use App\Filament\Maintenance\Resources\Beds\BedResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBed extends EditRecord
{
    protected static string $resource = BedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
