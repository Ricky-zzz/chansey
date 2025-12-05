<?php

namespace App\Filament\Resources\Nurses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class NurseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('employee_id')
                    ->required(),
                TextInput::make('first_name')
                    ->required(),
                TextInput::make('last_name')
                    ->required(),
                TextInput::make('license_number')
                    ->required(),
                TextInput::make('station_assignment'),
                TextInput::make('shift_schedule'),
            ]);
    }
}
