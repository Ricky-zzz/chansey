<?php

namespace App\Filament\Chief\Resources\Supervisors\Pages;

use App\Filament\Chief\Resources\Supervisors\SupervisorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSupervisors extends ListRecords
{
    protected static string $resource = SupervisorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
