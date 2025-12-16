<?php

namespace App\Filament\Maintenance\Resources\Beds;
use Filament\Notifications\Notification;
use App\Filament\Maintenance\Resources\Beds\Pages\CreateBed;
use App\Filament\Maintenance\Resources\Beds\Pages\EditBed;
use App\Filament\Maintenance\Resources\Beds\Pages\ListBeds;
use App\Models\Bed;
use App\Models\Room;
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
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;

class BedResource extends Resource
{
    protected static ?string $model = Bed::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'bed_code';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Bed Details')
                    ->schema([
                        Select::make('room_id')
                            ->relationship('room', 'room_number')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                if (! $state) return;


                                $room = Room::with('station')->find($state);

                                $count = $room->beds()->count();
                                $nextLetter = chr(64 + $count + 1);

                                $set('bed_code', "{$room->station->station_code}-{$room->room_number}-{$nextLetter}");
                            }),

                        TextInput::make('bed_code')
                            ->label('Bed ID')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Auto-generated based on Room selection. You can edit this if needed.'),

                        // 3. STATUS
                        Select::make('status')
                            ->options([
                                'Available' => 'Available',
                                'Occupied' => 'Occupied (Patient)',
                                'Cleaning' => 'Needs Cleaning / Housekeeping',
                                'Maintenance' => 'Broken / Maintenance',
                            ])
                            ->default('Available')
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('room.room_number')
                    ->label('Room')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('bed_code')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Available' => 'success',
                        'Occupied' => 'danger',
                        'Cleaning' => 'warning',
                        'Maintenance' => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Available' => 'Available',
                        'Occupied' => 'Occupied',
                        'Cleaning' => 'Cleaning',
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()
                        ->before(function ($record, DeleteAction $action) {
                            if ($record->status === 'Occupied') {
                                Notification::make()
                                    ->title('Action Blocked')
                                    ->body('This bed is currently occupied by a patient and cannot be deleted.')
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
            'index' => ListBeds::route('/'),
            'create' => CreateBed::route('/create'),
            'edit' => EditBed::route('/{record}/edit'),
        ];
    }
}
