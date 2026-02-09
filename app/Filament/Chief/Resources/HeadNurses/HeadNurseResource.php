<?php

namespace App\Filament\Chief\Resources\HeadNurses;

use App\Filament\Chief\Resources\HeadNurses\Pages\CreateHeadNurse;
use App\Filament\Chief\Resources\HeadNurses\Pages\EditHeadNurse;
use App\Filament\Chief\Resources\HeadNurses\Pages\ListHeadNurses;
use App\Filament\Chief\Resources\HeadNurses\Schemas\HeadNurseForm;
use App\Filament\Chief\Resources\HeadNurses\Tables\HeadNursesTable;
use App\Models\Nurse;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class HeadNurseResource extends Resource
{
    protected static ?string $model = Nurse::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Nurses';

    protected static ?string $navigationLabel = 'Head Nurses';

    protected static ?string $recordTitleAttribute = 'last_name';

    public static function form(Schema $schema): Schema
    {
        return HeadNurseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HeadNursesTable::configure($table);
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
            'index' => ListHeadNurses::route('/'),
            'create' => CreateHeadNurse::route('/create'),
            'edit' => EditHeadNurse::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role_level', 'Head');
    }
}
