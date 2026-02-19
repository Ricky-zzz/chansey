<?php

namespace App\Filament\Supervisor\Resources\Incidents;

use App\Filament\Supervisor\Resources\Incidents\Pages\ListIncidents;
use App\Filament\Supervisor\Resources\Incidents\Pages\ViewIncident;
use App\Filament\Supervisor\Resources\Incidents\Tables\IncidentsTable;
use App\Models\Incident;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class IncidentResource extends Resource
{
    protected static ?string $model = Incident::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'status';

    public static function table(Table $table): Table
    {
        return IncidentsTable::configure($table);
    }

    /**
     * Filter incidents by supervisor's unit
     * Show only incidents from stations under their unit
     */
    public static function getEloquentQuery(): Builder
    {
        $myUnitId = Auth::user()->nurse->unit_id;

        return parent::getEloquentQuery()
            ->whereHas('station', function ($query) use ($myUnitId) {
                $query->where('unit_id', $myUnitId);
            })
            ->with(['station', 'admission.patient', 'createdBy', 'resolvedBy', 'involvedStaff']);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIncidents::route('/'),
            'view' => ViewIncident::route('/{record}'),
        ];
    }
}
