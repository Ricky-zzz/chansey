<?php

namespace App\Filament\Resources\Pharmacists;

use App\Filament\Resources\Pharmacists\Pages\CreatePharmacist;
use App\Filament\Resources\Pharmacists\Pages\EditPharmacist;
use App\Filament\Resources\Pharmacists\Pages\ListPharmacists;
use App\Models\Pharmacist;
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
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;

class PharmacistResource extends Resource
{
    protected static ?string $model = Pharmacist::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'employee_id';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Login Details')
                    ->description('Access control for the pharmacist.')
                    ->hidden(fn(string $operation): bool => $operation === 'view')
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->label(fn(string $operation): string => $operation === 'create' ? 'Initial Password' : 'New Password (Leave blank to keep current)')
                            ->formatStateUsing(fn() => null)
                            ->dehydrated(fn($state) => filled($state)),
                    ]),

                Section::make('Pharmacist Profile')
                    ->description('Professional details.')
                    ->schema([
                        TextInput::make('full_name')
                            ->label('Full Name')
                            ->placeholder('e.g. Juan Dela Cruz')
                            ->required(),

                        TextInput::make('license_number')
                            ->label('License Number')
                            ->placeholder('e.g. PRC-0012345')
                            ->required(),
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

                TextColumn::make('full_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('license_number')
                    ->label('License No.')
                    ->searchable(),
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
            'index' => ListPharmacists::route('/'),
            'create' => CreatePharmacist::route('/create'),
            'edit' => EditPharmacist::route('/{record}/edit'),
        ];
    }
}