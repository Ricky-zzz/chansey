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

        return $schema
            ->components([
                Hidden::make('created_by_user_id')
                    ->default(fn() => Auth::id()),

                Section::make('Announcement Details')
                    ->description('Create a new announcement for staff in your unit.')
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
                    ->description('**IMPORTANT:** Memos are automatically restricted to YOUR UNIT. Select roles to target specific positions. Leave stations empty to send to ALL stations in your unit, or select specific stations.')
                    ->schema([
                        Select::make('target_roles')
                            ->label('Target by Role')
                            ->options([
                                'Staff' => 'Staff',
                                'Head' => 'Head Nurse',
                            ])
                            ->multiple()
                            ->nullable()
                            ->columnSpanFull(),

                        Select::make('target_stations')
                            ->label('Target by Station/Ward')
                            ->options($stationOptions)
                            ->placeholder('Select stations...')
                            ->multiple()
                            ->nullable()
                            ->helperText('✓ Showing only stations in your unit. Leave empty to send to all stations.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
