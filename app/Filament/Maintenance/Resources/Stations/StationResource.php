<?php

namespace App\Filament\Maintenance\Resources\Stations;
use Filament\Notifications\Notification;
use App\Filament\Maintenance\Resources\Stations\Pages\CreateStation;
use App\Filament\Maintenance\Resources\Stations\Pages\EditStation;
use App\Filament\Maintenance\Resources\Stations\Pages\ListStations;
use App\Filament\Maintenance\Resources\Stations\Schemas\StationForm;
use App\Filament\Maintenance\Resources\Stations\Tables\StationsTable;
use App\Models\Station;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Models\Room;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;

class StationResource extends Resource
{
    protected static ?string $model = Station::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'station_name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Station Details')
                    ->schema([
                        TextInput::make('station_name')
                            ->label('Station Name')
                            ->placeholder('e.g. Ward 3 West, ICU Main')
                            ->required()
                            ->unique(ignoreRecord: true),

                        TextInput::make('station_code')
                            ->label('Station Code')
                            ->placeholder('e.g. W3-W, ICU-M')
                            ->required()
                            ->unique(ignoreRecord: true),

                        TextInput::make('floor_location')
                            ->label('Floor / Building Location')
                            ->placeholder('e.g. 3rd Floor Main Wing')
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('station_name')->sortable()->searchable()->weight('bold'),
                TextColumn::make('station_code')->sortable(),
                TextColumn::make('floor_location'),

                TextColumn::make('rooms_count')->counts('rooms')->label('Rooms'),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()
                        ->before(function ($record, DeleteAction $action) {
                            if ($record->beds()->where('beds.status', 'Occupied')->exists()) {
                                Notification::make()
                                    ->title('Action Blocked')
                                    ->body('Cannot delete station. It contains rooms with occupied beds.')
                                    ->danger()
                                    ->send();

                                $action->cancel();
                            }
                        }),
                ]),
            ]);
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
            'index' => ListStations::route('/'),
            'create' => CreateStation::route('/create'),
            'edit' => EditStation::route('/{record}/edit'),
        ];
    }
}
