<?php

namespace App\Filament\Supervisor\Resources\Incidents\Pages;

use App\Filament\Supervisor\Resources\Incidents\IncidentResource;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewIncident extends ViewRecord
{
    protected static string $resource = IncidentResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // INCIDENT OVERVIEW
                Section::make('Incident Overview')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('time_of_incident')
                                    ->label('Date & Time of Incident')
                                    ->dateTime('M d, Y H:i'),

                                TextEntry::make('time_reported')
                                    ->label('Time Reported')
                                    ->dateTime('M d, Y H:i'),

                                TextEntry::make('incident_category')
                                    ->label('Category')
                                    ->formatStateUsing(fn(string $state): string => str_replace('_', ' ', ucfirst($state)))
                                    ->columnSpan(1),

                                TextEntry::make('severity_level')
                                    ->label('Severity Level')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'Severe' => 'danger',
                                        'High' => 'warning',
                                        'Moderate' => 'info',
                                        'Low' => 'success',
                                    }),

                                TextEntry::make('station.station_name')
                                    ->label('Station')
                                    ->placeholder('No station assigned'),

                                TextEntry::make('location_details')
                                    ->label('Location Details'),

                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'resolved' => 'success',
                                        'investigating' => 'warning',
                                        'unresolved' => 'danger',
                                    })
                                    ->formatStateUsing(fn(string $state): string => ucfirst($state))
                                    ->columnSpan(1),
                            ]),
                    ]),

                // PATIENT & PERSONNEL
                Section::make('Patient & Personnel')
                    ->icon('heroicon-o-users')
                    ->schema([
                        Grid::make(2)
                            ->schema([

                                TextEntry::make('admission.patient.full_name')
                                    ->label('Patient Name')
                                    ->placeholder('No patient associated'),

                                TextEntry::make('createdBy.name')
                                    ->label('Reported By'),

                                TextEntry::make('resolvedBy.name')
                                    ->label('Resolved By')
                                    ->placeholder('Pending Resolution'),
                            ]),
                    ]),

                // INCIDENT DETAILS
                Section::make('Incident Details')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        TextEntry::make('narrative')
                            ->label('Summary')
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                TextEntry::make('what_happened')
                                    ->label('What Happened'),

                                TextEntry::make('how_discovered')
                                    ->label('How Discovered'),

                                TextEntry::make('action_taken')
                                    ->label('Action Taken')
                                    ->columnSpanFull(),
                            ]),
                    ]),

                // CLINICAL VITALS
                Section::make('Clinical Vitals')
                    ->icon('heroicon-o-heart')
                    ->visible(fn($record) => $record->vitals !== null)
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('vitals.temperature')
                                    ->label('Temperature')
                                    ->formatStateUsing(fn($state) => $state ? $state . '°C' : 'N/A'),

                                TextEntry::make('vitals.bp')
                                    ->label('Blood Pressure'),

                                TextEntry::make('vitals.hr')
                                    ->label('Heart Rate')
                                    ->formatStateUsing(fn($state) => $state ? $state . ' bpm' : 'N/A'),

                                TextEntry::make('vitals.pr')
                                    ->label('Pulse Rate')
                                    ->formatStateUsing(fn($state) => $state ? $state . ' bpm' : 'N/A'),

                                TextEntry::make('vitals.rr')
                                    ->label('Respiratory Rate')
                                    ->formatStateUsing(fn($state) => $state ? $state . ' /min' : 'N/A'),

                                TextEntry::make('vitals.o2')
                                    ->label('O2 Saturation')
                                    ->formatStateUsing(fn($state) => $state ? $state . '%' : 'N/A'),
                            ]),
                    ]),

                // INJURY & NOTIFICATIONS
                Section::make('Injury & Notifications')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('injury')
                                    ->label('Injury Occurred')
                                    ->formatStateUsing(fn(bool $state): string => $state ? 'Yes' : 'No')
                                    ->badge()
                                    ->color(fn(bool $state): string => $state ? 'danger' : 'success'),

                                TextEntry::make('injury_type')
                                    ->label('Injury Type')
                                    ->placeholder('No injury reported'),

                                TextEntry::make('doctor_notified')
                                    ->label('Doctor Notified')
                                    ->formatStateUsing(fn(bool $state): string => $state ? 'Yes' : 'No')
                                    ->badge()
                                    ->color(fn(bool $state): string => $state ? 'success' : 'warning'),

                                TextEntry::make('family_notified')
                                    ->label('Family Notified')
                                    ->formatStateUsing(fn(bool $state): string => $state ? 'Yes' : 'No')
                                    ->badge()
                                    ->color(fn(bool $state): string => $state ? 'success' : 'warning'),
                            ]),
                    ]),

                // ROOT CAUSE & FOLLOW-UP
                Section::make('Root Cause & Follow-up')
                    ->icon('heroicon-o-check-circle')
                    ->schema([
                        TextEntry::make('root_cause')
                            ->label('Root Cause')
                            ->formatStateUsing(fn($state) => $state ? str_replace('_', ' ', ucfirst($state)) : 'To be determined')
                            ->columnSpanFull(),

                        TextEntry::make('follow_up_actions')
                            ->label('Follow-up Actions')
                            ->columnSpanFull(),

                        TextEntry::make('follow_up_instructions')
                            ->label('Follow-up Instructions')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
