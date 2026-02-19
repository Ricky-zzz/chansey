<?php

namespace App\Filament\Supervisor\Resources\Incidents\Pages;

use App\Filament\Supervisor\Resources\Incidents\IncidentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIncident extends CreateRecord
{
    protected static string $resource = IncidentResource::class;
}
