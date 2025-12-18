<?php

namespace App\Filament\Maintenance\Resources\Rooms;
use Filament\Notifications\Notification;
use App\Filament\Maintenance\Resources\Rooms\Pages\CreateRoom;
use App\Filament\Maintenance\Resources\Rooms\Pages\EditRoom;
use App\Filament\Maintenance\Resources\Rooms\Pages\ListRooms;
use App\Models\Room;
use App\Models\Station;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'room_number';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Room Details')
                    ->schema([
                        Select::make('station_id')
                            ->relationship('station', 'station_name')
                            ->label('Nurse Station / Ward')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('room_number')
                            ->label('Room Number / Name')
                            ->required()
                            ->unique(ignoreRecord: true),

                        Select::make('room_type')
                            ->options([
                                'Private' => 'Private Suite',
                                'Semi-Private' => 'Semi-Private (2 Bed)',
                                'Ward' => 'Ward (Multi-Bed)',
                                'ICU' => 'ICU',
                                'ER' => 'Emergency Room',
                            ])
                            ->required(),

                        TextInput::make('capacity')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->maxValue(10)
                            ->label('Bed Capacity')
                            ->helperText('Beds will be auto-generated based on this number.')
                            ->required(),

                        TextInput::make('price_per_night')
                            ->numeric()
                            ->label('Price Per Night')
                            ->prefix('₱')
                            ->required(),

                        Select::make('status')
                            ->options([
                                'Active' => 'Active',
                                'Maintenance' => 'Under Maintenance',
                                'Closed' => 'Closed',
                            ])
                            ->default('Active')
                            ->required(),
                    ])
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('station.station_code')->sortable()->weight('bold'),
                TextColumn::make('room_number')->sortable()->searchable()->weight('bold'),
                TextColumn::make('room_type')->sortable(),
                TextColumn::make('capacity')->label('Beds'),
                TextColumn::make('beds_count')->counts('beds')->label('Actual Beds'),
                TextColumn::make('price_per_night')->label('Price/Night')->formatStateUsing(fn($state) => '₱' . number_format($state, 2)),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Active' => 'success',
                        'Maintenance' => 'warning',
                        'Closed' => 'danger',
                    }),
            ])
            ->filters([
                SelectFilter::make('station_id')
                    ->relationship('station', 'station_name')
                    ->label('Station'),
                SelectFilter::make('room_type')
                    ->options([
                        'Private' => 'Private Suite',
                        'Semi-Private' => 'Semi-Private (2 Bed)',
                        'Ward' => 'Ward (Multi-Bed)',
                        'ICU' => 'ICU',
                        'ER' => 'Emergency Room',
                    ])
                    ->label('Room Type'),
                SelectFilter::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Maintenance' => 'Under Maintenance',
                        'Closed' => 'Closed',
                    ])
                    ->label('Status'),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()
                        ->before(function ($record, DeleteAction $action) {
                            if ($record->beds()->where('status', 'Occupied')->exists()) {
                                Notification::make()
                                    ->title('Action Blocked')
                                    ->body('Cannot delete this room. One or more beds are currently occupied.')
                                    ->danger()
                                    ->send();

                                $action->cancel();
                            }
                        }),
                ]),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListRooms::route('/'),
            'create' => CreateRoom::route('/create'),
            'edit' => EditRoom::route('/{record}/edit'),
        ];
    }
}
