<?php

namespace App\Filament\Supervisor\Resources\StationHeads;

use App\Filament\Supervisor\Resources\StationHeads\Pages\CreateStationHead;
use App\Filament\Supervisor\Resources\StationHeads\Pages\EditStationHead;
use App\Filament\Supervisor\Resources\StationHeads\Pages\ListStationHeads;
use App\Filament\Supervisor\Resources\StationHeads\Schemas\StationHeadForm;
use App\Filament\Supervisor\Resources\StationHeads\Tables\StationHeadsTable;
use App\Models\Nurse;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class StationHeadResource extends Resource
{
    protected static ?string $model = Nurse::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Nurses';

    protected static ?string $navigationLabel = 'Station Heads';

    protected static ?string $recordTitleAttribute = 'last_name';

    public static function form(Schema $schema): Schema
    {
        return StationHeadForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StationHeadsTable::configure($table);
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
            'index' => ListStationHeads::route('/'),
            'create' => CreateStationHead::route('/create'),
            'edit' => EditStationHead::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $myUnitId = Auth::user()->nurse->unit_id;

        return parent::getEloquentQuery()
            ->where('role_level', 'Head')
            ->whereHas('station', function ($query) use ($myUnitId) {
                $query->where('unit_id', $myUnitId);
            });
    }
}
