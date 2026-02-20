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
        // Preload options for "All" selectors
        $allRoles = ['Staff', 'Head', 'Supervisor', 'Chief'];
        $allUnits = Unit::pluck('id')->toArray();
        $allStations = Station::pluck('id')->toArray();

        return $schema
            ->columns(1)
            ->components([
                Section::make('Announcement Details')
                    ->description('Create a new announcement for staff and units.')
                    ->columns(1)
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
                    ->description('Select who should receive this memo. Use "All" options to auto-populate all entries.')
                    ->columns(1)
                    ->schema([
                        Select::make('target_roles_input')
                            ->label('Target by Role')
                            ->options([
                                '*all*' => '✓ All Roles',
                                'Staff' => 'Staff',
                                'Head' => 'Head Nurse',
                                'Supervisor' => 'Supervisor',
                                'Chief' => 'Chief Nurse',
                            ])
                            ->multiple()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) use ($allRoles) {
                                $roles = $get('target_roles_input');
                                if (is_array($roles) && in_array('*all*', $roles)) {
                                    $set('target_roles_input', $allRoles);
                                }
                            })
                            ->columnSpanFull(),

                        Select::make('target_units_input')
                            ->label('Target by Unit')
                            ->options(
                                ['*all*' => '✓ All Units'] + Unit::all()->mapWithKeys(fn($unit) => [
                                    $unit->id => $unit->name,
                                ])->toArray()
                            )
                            ->placeholder('Select units...')
                            ->multiple()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) use ($allUnits, $allStations) {
                                $units = $get('target_units_input');
                                if (is_array($units) && in_array('*all*', $units)) {
                                    // When "All Units" is selected, auto-populate all units AND all stations
                                    $set('target_units_input', $allUnits);
                                    $set('target_stations_input', $allStations);
                                } else {
                                    // Clear stations that don't belong to selected units
                                    $selectedStations = $get('target_stations_input');
                                    if (is_array($selectedStations) && !empty($units)) {
                                        $validStations = Station::whereIn('unit_id', $units)->pluck('id')->toArray();
                                        $filteredStations = array_intersect($selectedStations, $validStations);
                                        if (count($filteredStations) !== count($selectedStations)) {
                                            $set('target_stations_input', array_values($filteredStations));
                                        }
                                    }
                                }
                            })
                            ->columnSpanFull(),

                        Select::make('target_stations_input')
                            ->label('Target by Station/Ward')
                            ->options(function (Get $get) {
                                $selectedUnits = $get('target_units_input');

                                if (empty($selectedUnits) || !is_array($selectedUnits)) {
                                    return ['*all*' => '✓ All Stations'] + Station::with('unit')->get()->mapWithKeys(fn($station) => [
                                        $station->id => $station->station_name . ' (' . ($station->unit->name ?? 'N/A') . ')',
                                    ])->toArray();
                                }

                                return ['*all*' => '✓ All Stations (in selected units)'] + Station::whereIn('unit_id', $selectedUnits)
                                    ->with('unit')
                                    ->get()
                                    ->mapWithKeys(fn($station) => [
                                        $station->id => $station->station_name . ' (' . ($station->unit->name ?? 'N/A') . ')',
                                    ])->toArray();
                            })
                            ->placeholder('Select stations...')
                            ->multiple()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $stations = $get('target_stations_input');
                                $selectedUnits = $get('target_units_input');

                                if (is_array($stations) && in_array('*all*', $stations)) {
                                    if (!empty($selectedUnits) && is_array($selectedUnits)) {
                                        $allStationIds = Station::whereIn('unit_id', $selectedUnits)->pluck('id')->toArray();
                                    } else {
                                        $allStationIds = Station::pluck('id')->toArray();
                                    }
                                    $set('target_stations_input', $allStationIds);
                                }
                            })
                            ->helperText(fn(Get $get) =>
                                !empty($get('target_units_input'))
                                    ? '✓ Filtered to show only stations in selected units'
                                    : 'Select units first to filter stations'
                            )
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
