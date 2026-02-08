<?php

namespace App\Filament\Chief\Resources\StaffNurses;

use App\Filament\Chief\Resources\StaffNurses\Pages\CreateStaffNurse;
use App\Filament\Chief\Resources\StaffNurses\Pages\EditStaffNurse;
use App\Filament\Chief\Resources\StaffNurses\Pages\ListStaffNurses;
use App\Filament\Chief\Resources\StaffNurses\Schemas\StaffNurseForm;
use App\Filament\Chief\Resources\StaffNurses\Tables\StaffNursesTable;
use App\Models\Nurse;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StaffNurseResource extends Resource
{
    protected static ?string $model = Nurse::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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
        return parent::getEloquentQuery()->where('role_level', 'Staff');
    }
}
