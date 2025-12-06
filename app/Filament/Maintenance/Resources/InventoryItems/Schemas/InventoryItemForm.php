<?php

namespace App\Filament\Maintenance\Resources\InventoryItems\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InventoryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('item_name')
                    ->required(),
                TextInput::make('category')
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('critical_level')
                    ->required()
                    ->numeric()
                    ->default(10),
            ]);
    }
}
