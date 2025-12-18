<?php

namespace App\Filament\Resources\Nurses;

use App\Filament\Resources\Nurses\Pages;
use App\Models\Nurse;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\TimePicker;

class NurseResource extends Resource
{
    protected static ?string $model = Nurse::class;

    // Use the standard string for the icon
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $recordTitleAttribute = 'last_name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Login Details')
                    ->description('This creates the user account for the system.')
                    ->hidden(fn(string $operation): bool => $operation === 'view')
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->label(fn(string $operation): string => $operation === 'create' ? 'Initial Password' : 'New Password (Leave blank to keep current)')
                            ->formatStateUsing(fn() => null)
                            ->dehydrated(fn($state) => filled($state)),
                    ]),

                Section::make('Nurse Profile')
                    ->description('Clinical details.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('first_name')
                                ->required(),
                            TextInput::make('last_name')
                                ->required(),
                        ]),

                        TextInput::make('license_number')
                            ->label('PRC License Number')
                            ->required(),

                        Select::make('designation')
                            ->options([
                                'Clinical' => 'Clinical Nurse',
                                'Admitting' => 'Admitting Nurse',
                            ])
                            ->live() // <--- React immediately
                            ->required(),

                        Select::make('station_id')
                            ->relationship('station', 'station_name')
                            ->label('Station Assignment')
                            ->nullable()
                            ->visible(fn($get) => $get('designation') === 'Clinical')
                            ->required(fn($get) => $get('designation') === 'Clinical'),

                        Grid::make(2)->schema([
                            TimePicker::make('shift_start')
                                ->label('Shift Start')
                                ->seconds(false) // Hide seconds (06:00:00 -> 06:00)
                                ->required(),

                            TimePicker::make('shift_end')
                                ->label('Shift End')
                                ->seconds(false)
                                ->required(),
                        ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee_id')
                    ->label('Badge ID')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                // 2. Name
                TextColumn::make('last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable(),

                TextColumn::make('designation')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Admitting' => 'warning',
                        'Clinical' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('station_id')
                    ->label('Station')
                    ->formatStateUsing(fn($state, Nurse $record) => $record->station?->station_name ?? 'Admission'),

                TextColumn::make('shift_start')
                    ->label('Shift')
                    ->formatStateUsing(
                        fn(Nurse $record) =>
                        \Carbon\Carbon::parse($record->shift_start)->format('g:i A') . ' - ' .
                            \Carbon\Carbon::parse($record->shift_end)->format('g:i A')
                    )

            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
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
            'index' => Pages\ListNurses::route('/'),
            'create' => Pages\CreateNurse::route('/create'),
            'edit' => Pages\EditNurse::route('/{record}/edit'),
        ];
    }
}
