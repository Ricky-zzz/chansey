<?php

namespace App\Filament\Supervisor\Resources\StaffNurses;

use App\Filament\Supervisor\Resources\StaffNurses\Pages\CreateStaffNurse;
use App\Filament\Supervisor\Resources\StaffNurses\Pages\EditStaffNurse;
use App\Filament\Supervisor\Resources\StaffNurses\Pages\ListStaffNurses;
use App\Filament\Supervisor\Resources\StaffNurses\Schemas\StaffNurseForm;
use App\Filament\Supervisor\Resources\StaffNurses\Tables\StaffNursesTable;
use App\Models\Nurse;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class StaffNurseResource extends Resource
{
    protected static ?string $model = Nurse::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Nurses';

    protected static ?string $navigationLabel = 'Staff Nurses';

    protected static ?string $recordTitleAttribute = 'last_name';

    public static function form(Schema $schema): Schema
    {
        return StaffNurseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StaffNursesTable::configure($table);
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
            'index' => ListStaffNurses::route('/'),
            'create' => CreateStaffNurse::route('/create'),
            'edit' => EditStaffNurse::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $myUnitId = Auth::user()->nurse->unit_id;

        return parent::getEloquentQuery()
            ->whereIn('role_level', ['Staff', 'Charge'])
            ->whereHas('station', function ($query) use ($myUnitId) {
                $query->where('unit_id', $myUnitId);
            });
    }
}
