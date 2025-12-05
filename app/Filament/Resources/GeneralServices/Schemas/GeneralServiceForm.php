<?php

namespace App\Filament\Resources\GeneralServices\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GeneralServiceForm
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
                TextInput::make('assigned_area')
                    ->required(),
                TextInput::make('shift')
                    ->required(),
            ]);
    }
}
