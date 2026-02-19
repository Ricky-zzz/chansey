<?php

namespace App\Filament\Supervisor\Resources\Incidents\Tables;

use App\Models\Station;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class IncidentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('time_of_incident')
                    ->label('Date & Time')
                    ->dateTime('M d, H:i')
                    ->sortable(),

                TextColumn::make('incident_category')
                    ->label('Category')
                    ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', ucfirst($state)))
                    ->searchable(),

                TextColumn::make('station.name')
                    ->label('Station')
                    ->sortable(),

                BadgeColumn::make('severity_level')
                    ->label('Severity')
                    ->color(fn (string $state): string => match ($state) {
                        'Severe' => 'danger',
                        'High' => 'warning',
                        'Moderate' => 'info',
                        'Low' => 'success',
                    }),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->color(fn (string $state): string => match ($state) {
                        'resolved' => 'success',
                        'investigating' => 'warning',
                        'unresolved' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('createdBy.name')
                    ->label('Reported By')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('station_id')
                    ->label('Filter by Station')
                    ->placeholder('All Stations')
                    ->options(function () {
                        return Station::pluck('station_name', 'id')->toArray();
                    }),

                SelectFilter::make('status')
                    ->label('Filter by Status')
                    ->options([
                        'unresolved' => 'Unresolved',
                        'investigating' => 'Investigating',
                        'resolved' => 'Resolved',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->defaultSort('time_of_incident', 'desc');
    }
}
