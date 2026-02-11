<?php

namespace App\Filament\Supervisor\Resources\StationHeads\Tables;

use App\Models\Nurse;
use App\Models\Station;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class StationHeadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                ImageColumn::make('user.profile_image_path')
                    ->label('')
                    ->circular()
                    ->disk('public')
                    ->checkFileExistence(false)
                    ->defaultImageUrl(fn(Nurse $record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->first_name . ' ' . $record->last_name) . '&color=7F9CF5&background=EBF4FF'),

                TextColumn::make('employee_id')
                    ->label('Badge ID')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable(),

                TextColumn::make('designation')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Admitting' => 'warning',
                        'Clinical' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('nurseType.name')
                    ->label('Nurse Type')
                    ->default('—')
                    ->sortable(),

                TextColumn::make('station.station_name')
                    ->label('Station')
                    ->default('—')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => $state === 'Active' ? 'success' : 'gray')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('designation')
                    ->options([
                        'Clinical' => 'Clinical',
                        'Admitting' => 'Admitting',
                    ]),

                SelectFilter::make('nurse_type_id')
                    ->label('Nurse Type')
                    ->relationship('nurseType', 'name')
                    ->preload()
                    ->searchable(),

                SelectFilter::make('station_id')
                    ->label('Station')
                    ->relationship('station', 'station_name')
                    ->preload()
                    ->searchable(),
            ])
            ->recordActions([
                Action::make('reassignStation')
                    ->label('Reassign Station')
                    ->modalHeading('Reassign Station')
                    ->form([
                        Select::make('station_id')
                            ->label('Select New Station')
                            ->options(function (Nurse $record) {
                                $supervisorUnitId = Auth::user()->nurse->unit_id;
                                return Station::where('unit_id', $supervisorUnitId)
                                    ->pluck('station_name', 'id');
                            })
                            ->default(fn(Nurse $record) => $record->station_id)
                            ->required(),
                    ])
                    ->action(function (array $data, Nurse $record): void {
                        $record->update($data);
                    })
                    ->successNotificationTitle('Station Assigned'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
