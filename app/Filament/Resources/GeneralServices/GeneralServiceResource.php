<?php

namespace App\Filament\Resources\GeneralServices;


use App\Models\GeneralService;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TimePicker;

class GeneralServiceResource extends Resource
{
    protected static ?string $model = GeneralService::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::WrenchScrewdriver;
    protected static ?string $recordTitleAttribute = 'last_name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Login Details')
                    ->description('Access control for the staff member.')
                    ->hidden(fn(string $operation): bool => $operation === 'view')
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->label(fn(string $operation): string => $operation === 'create' ? 'Initial Password' : 'New Password (Leave blank to keep current)')
                            ->formatStateUsing(fn() => null)
                            ->dehydrated(fn($state) => filled($state)),
                    ]),

                Section::make('Staff Profile')
                    ->description('Assignment details.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('first_name')->required(),
                            TextInput::make('last_name')->required(),
                        ]),

                        TextInput::make('assigned_area')
                            ->label('Assigned Area / Floor')
                            ->placeholder('e.g. Lobby, ER Hallway, 3rd Floor')
                            ->required(),

                        Grid::make(2)->schema([
                            TimePicker::make('shift_start')
                                ->label('Shift Start')
                                ->seconds(false) 
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

                TextColumn::make('last_name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('first_name')
                    ->searchable(),

                TextColumn::make('assigned_area')
                    ->label('Area')
                    ->searchable(),

                TextColumn::make('shift_start')
                    ->label('Shift')
                    ->formatStateUsing(
                        fn(GeneralService $record) =>
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
            'index' => Pages\ListGeneralServices::route('/'),
            'create' => Pages\CreateGeneralService::route('/create'),
            'edit' => Pages\EditGeneralService::route('/{record}/edit'),
        ];
    }
}