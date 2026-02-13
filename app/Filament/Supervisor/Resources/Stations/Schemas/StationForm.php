<?php

namespace App\Filament\Supervisor\Resources\Stations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('station_name')
                    ->required(),
                TextInput::make('station_code'),
                TextInput::make('floor_location'),
            ]);
    }
}
