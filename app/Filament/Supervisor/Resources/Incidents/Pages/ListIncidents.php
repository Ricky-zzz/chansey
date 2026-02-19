<?php

namespace App\Filament\Supervisor\Resources\Incidents\Pages;

use App\Filament\Supervisor\Resources\Incidents\IncidentResource;
use Filament\Resources\Pages\ListRecords;

class ListIncidents extends ListRecords
{
    protected static string $resource = IncidentResource::class;
}
