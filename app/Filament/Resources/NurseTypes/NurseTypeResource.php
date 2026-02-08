<?php

namespace App\Filament\Resources\NurseTypes;

use App\Filament\Resources\NurseTypes\Pages\CreateNurseType;
use App\Filament\Resources\NurseTypes\Pages\EditNurseType;
use App\Filament\Resources\NurseTypes\Pages\ListNurseTypes;
use App\Filament\Resources\NurseTypes\Schemas\NurseTypeForm;
use App\Filament\Resources\NurseTypes\Tables\NurseTypesTable;
use App\Models\NurseType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NurseTypeResource extends Resource
{
    protected static ?string $model = NurseType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return NurseTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NurseTypesTable::configure($table);
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
            'index' => ListNurseTypes::route('/'),
            'create' => CreateNurseType::route('/create'),
            'edit' => EditNurseType::route('/{record}/edit'),
        ];
    }
}
