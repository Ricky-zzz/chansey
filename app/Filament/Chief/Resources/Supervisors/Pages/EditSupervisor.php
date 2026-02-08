<?php

namespace App\Filament\Chief\Resources\Supervisors\Pages;

use App\Filament\Chief\Resources\Supervisors\SupervisorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSupervisor extends EditRecord
{
    protected static string $resource = SupervisorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
