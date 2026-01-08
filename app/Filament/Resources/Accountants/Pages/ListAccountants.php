<?php

namespace App\Filament\Resources\Accountants\Pages;

use App\Filament\Resources\Accountants\AccountantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAccountants extends ListRecords
{
    protected static string $resource = AccountantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
