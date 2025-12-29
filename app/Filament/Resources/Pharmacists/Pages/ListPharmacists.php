<?php

namespace App\Filament\Resources\Pharmacists\Pages;

use App\Filament\Resources\Pharmacists\PharmacistResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPharmacists extends ListRecords
{
    protected static string $resource = PharmacistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
