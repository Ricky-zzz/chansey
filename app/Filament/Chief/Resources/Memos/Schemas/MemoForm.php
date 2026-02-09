<?php

namespace App\Filament\Chief\Resources\Memos\Schemas;

use App\Models\Unit;
use App\Models\Station;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class MemoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Announcement Details')
                    ->description('Create a new announcement for staff and units.')
                    ->schema([
                        TextInput::make('title')
                            ->label('Announcement Title')
                            ->placeholder('e.g. Board Meeting on Friday')
                            ->required()
                            ->columnSpanFull(),

                        RichEditor::make('content')
                            ->label('Announcement Content')
                            ->placeholder('Write your announcement here...')
                            ->required()
                            ->columnSpanFull(),

                        FileUpload::make('attachment_path')
                            ->label('Attach File (Optional)')
                            ->disk('public')
                            ->directory('memos/attachments')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->helperText('Max 5MB. Accepted: PDF, Word, Images')
                            ->columnSpanFull(),
                    ]),

                Section::make('Target Audience')
                    ->description('Select who should receive this announcement. Leave empty to not target that category.')
                    ->schema([
                        Select::make('target_roles')
                            ->label('Target by Role')
                            ->options([
                                'Staff' => 'Staff',
                                'Head' => 'Head Nurse',
                                'Supervisor' => 'Supervisor',
                                'Chief' => 'Chief Nurse',
                                '*all*' => '✓ All Roles',
                            ])
                            ->multiple()
                            ->nullable()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $roles = $get('target_roles');
                                if (is_array($roles) && in_array('*all*', $roles)) {
                                    $set('target_roles', [
                                        'Staff', 'Head', 'Supervisor', 'Chief'
                                    ]);
                                }
                            })
                            ->columnSpanFull(),

                        Select::make('target_units')
                            ->label('Target by Unit')
                            ->options(
                                Unit::all()->mapWithKeys(fn($unit) => [
                                    $unit->id => $unit->name,
                                ])->toArray() + ['*all*' => '✓ All Units']
                            )
                            ->placeholder('Select units...')
                            ->multiple()
                            ->nullable()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $units = $get('target_units');
                                if (is_array($units) && in_array('*all*', $units)) {
                                    // Get all unit IDs as strings
                                    $allUnitIds = Unit::pluck('id')->map(fn($id) => (string) $id)->toArray();
                                    $set('target_units', $allUnitIds);
                                }
                            })
                            ->columnSpanFull(),

                        Select::make('target_stations')
                            ->label('Target by Station/Ward')
                            ->options(
                                Station::all()->mapWithKeys(fn($station) => [
                                    $station->id => $station->station_name,
                                ])->toArray() + ['*all*' => '✓ All Stations']
                            )
                            ->placeholder('Select stations...')
                            ->multiple()
                            ->nullable()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $stations = $get('target_stations');
                                if (is_array($stations) && in_array('*all*', $stations)) {
                                    // Get all station IDs as strings
                                    $allStationIds = Station::pluck('id')->map(fn($id) => (string) $id)->toArray();
                                    $set('target_stations', $allStationIds);
                                }
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
