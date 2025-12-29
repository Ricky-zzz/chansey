<?php

namespace App\Filament\Pharmacy\Resources\Medicines\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MedicineForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Medicine Details')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('generic_name')
                                ->required()
                                ->placeholder('e.g. Paracetamol'),

                            TextInput::make('brand_name')
                                ->placeholder('e.g. Biogesic'),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('dosage')
                                ->required()
                                ->placeholder('e.g. 500mg'),

                            Select::make('form')
                                ->options([
                                    'Tablet' => 'Tablet',
                                    'Capsule' => 'Capsule',
                                    'Syrup' => 'Syrup',
                                    'Injection' => 'Injection',
                                    'Cream' => 'Cream/Ointment',
                                    'Drops' => 'Drops',
                                    'Inhaler' => 'Inhaler',
                                    'Suppository' => 'Suppository',
                                ])
                                ->required(),
                        ]),
                    ]),

                Section::make('Inventory & Pricing')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('stock_on_hand')
                                ->numeric()
                                ->default(0)
                                ->label('Current Stock'),

                            TextInput::make('critical_level')
                                ->numeric()
                                ->default(20)
                                ->label('Low Stock Warning Level')
                                ->helperText('System will alert if stock falls below this.'),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('price')
                                ->numeric()
                                ->prefix('₱')
                                ->default(0.00)
                                ->label('Unit Price'),

                            DatePicker::make('expiry_date')
                                ->label('Expiry Date'),
                        ]),
                    ]),
            ]);
    }
}
