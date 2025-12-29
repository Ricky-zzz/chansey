<?php

namespace App\Filament\Resources\Pharmacists\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PharmacistForm
{
    public static function configure(Schema $schema): Schema
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

                Section::make('Pharmacist Profile')
                    ->description('Professional details and credentials.')
                    ->schema([
                        TextInput::make('full_name')
                            ->label('Full Name')
                            ->required(),

                        TextInput::make('license_number')
                            ->label('License Number')
                            ->placeholder('e.g. PRC-0123456')
                            ->required(),
                    ]),
            ]);
    }
}
