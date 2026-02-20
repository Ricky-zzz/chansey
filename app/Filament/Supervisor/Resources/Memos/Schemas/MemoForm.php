<?php

namespace App\Filament\Supervisor\Resources\Memos\Schemas;

use Filament\Schemas\Schema;

use App\Models\Station;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Facades\Auth;

class MemoForm
{
    public static function configure(Schema $schema): Schema
    {
        $myUnitId = Auth::user()->nurse->unit_id;
        $stations = Station::where('unit_id', $myUnitId)->get();
        $stationOptions = $stations->mapWithKeys(fn($station) => [
            $station->id => $station->station_name,
        ])->toArray();
        $allStationIds = $stations->pluck('id')->toArray();

        return $schema
            ->columns(1)
            ->components([
                Hidden::make('created_by_user_id')
                    ->default(fn() => Auth::id()),

                Section::make('Announcement Details')
                    ->description('Create a new announcement for staff in your unit.')
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
                    ->description('Memos are automatically restricted to YOUR UNIT. Select roles and stations.')
                    ->columns(1)
                    ->schema([
                        Select::make('target_roles_input')
                            ->label('Target by Role')
                            ->options([
                                '*all*' => '✓ All Roles',
                                'Staff' => 'Staff',
                                'Head' => 'Head Nurse',
                            ])
                            ->multiple()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $roles = $get('target_roles_input');
                                if (is_array($roles) && in_array('*all*', $roles)) {
                                    $set('target_roles_input', ['Staff', 'Head']);
                                }
                            })
                            ->columnSpanFull(),

                        Select::make('target_stations_input')
                            ->label('Target by Station/Ward')
                            ->options(['*all*' => '✓ All Stations'] + $stationOptions)
                            ->placeholder('Select stations...')
                            ->multiple()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) use ($allStationIds) {
                                $stations = $get('target_stations_input');
                                if (is_array($stations) && in_array('*all*', $stations)) {
                                    $set('target_stations_input', $allStationIds);
                                }
                            })
                            ->helperText('✓ Showing only stations in your unit.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
