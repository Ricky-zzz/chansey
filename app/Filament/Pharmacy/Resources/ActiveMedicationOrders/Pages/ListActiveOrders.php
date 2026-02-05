<?php

namespace App\Filament\Pharmacy\Resources\ActiveMedicationOrders\Pages;

use App\Filament\Pharmacy\Resources\ActiveMedicationOrders\ActiveMedicationOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListActiveOrders extends ListRecords
{
    protected static string $resource = ActiveMedicationOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Refresh handled by Filament automatically
        ];
    }
}
