<?php

namespace App\Filament\Chief\Resources\Supervisors;

use App\Filament\Chief\Resources\Supervisors\Pages\CreateSupervisor;
use App\Filament\Chief\Resources\Supervisors\Pages\EditSupervisor;
use App\Filament\Chief\Resources\Supervisors\Pages\ListSupervisors;
use App\Filament\Chief\Resources\Supervisors\Schemas\SupervisorForm;
use App\Filament\Chief\Resources\Supervisors\Tables\SupervisorsTable;
use App\Models\Nurse;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SupervisorResource extends Resource
{
    protected static ?string $model = Nurse::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'last_name';

    public static function form(Schema $schema): Schema
    {
        return SupervisorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SupervisorsTable::configure($table);
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
            'index' => ListSupervisors::route('/'),
            'create' => CreateSupervisor::route('/create'),
            'edit' => EditSupervisor::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role_level', 'Supervisor');
    }
}
