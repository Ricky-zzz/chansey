<?php

namespace App\Filament\Resources\Physicians;

use App\Filament\Resources\Physicians\Pages\CreatePhysician;
use App\Filament\Resources\Physicians\Pages\EditPhysician;
use App\Filament\Resources\Physicians\Pages\ListPhysicians;
use App\Filament\Resources\Physicians\Schemas\PhysicianForm;
use App\Filament\Resources\Physicians\Tables\PhysiciansTable;
use App\Models\Physician;
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


class PhysicianResource extends Resource
{
    protected static ?string $model = Physician::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Heart;

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

                Section::make('Physician Profile')
                    ->description('Clinical details.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('first_name')->required(),
                            TextInput::make('last_name')->required(),
                        ]),

                        // 1. SPECIALIZATION (Searchable Select)
                        Select::make('specialization')
                            ->label('Department / Specialization')
                            ->searchable() // Allows typing to find "Cardiology" fast
                            ->preload()
                            ->options([
                                'General Practice' => 'General Practice / Family Medicine',
                                'Internal Medicine' => 'Internal Medicine',
                                'Pediatrics' => 'Pediatrics',
                                'Ob-Gyn' => 'Obstetrics & Gynecology (OB-GYN)',
                                'General Surgery' => 'General Surgery',
                                'Emergency Medicine' => 'Emergency Medicine',
                                'Cardiology' => 'Cardiology',
                                'Anesthesiology' => 'Anesthesiology',
                                'Neurology' => 'Neurology',
                                'Psychiatry' => 'Psychiatry',
                                'Orthopedics' => 'Orthopedics',
                                'Radiology' => 'Radiology',
                                'Oncology' => 'Oncology',
                            ])
                            ->required(),

                        // 2. EMPLOYMENT TYPE (Your Specific List)
                        Select::make('employment_type')
                            ->label('Employment Status')
                            ->options([
                                'Full-Time' => 'Full-Time (Standard 40hr)',
                                'Part-Time' => 'Part-Time',
                                'Contractual' => 'Contractual / Independent',
                                'Per Diem' => 'Per Diem / On-Call',
                                'Locum Tenens' => 'Locum Tenens (Temp Coverage)',
                                'Resident' => 'Resident / Fellow (Training)',
                                'Volunteer' => 'Volunteer',
                            ])
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

                // 2. Name
                TextColumn::make('last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable(),

                TextColumn::make('specialization')
                    ->label('Department')
                    ->sortable(),

                TextColumn::make('employment_type')
                    ->label('Type')
                    ->sortable(),


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
            'index' => ListPhysicians::route('/'),
            'create' => CreatePhysician::route('/create'),
            'edit' => EditPhysician::route('/{record}/edit'),
        ];
    }
}
