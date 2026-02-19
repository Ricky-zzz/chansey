<?php

namespace App\Filament\Supervisor\Resources\Incidents\Pages;

use App\Filament\Supervisor\Resources\Incidents\IncidentResource;
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
                // Incident Details
                TextEntry::make('time_of_incident')
                    ->label('Date & Time')
                    ->dateTime('M d, Y H:i'),

                TextEntry::make('incident_category')
                    ->label('Category')
                    ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', ucfirst($state))),

                TextEntry::make('severity_level')
                    ->label('Severity')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Severe' => 'danger',
                        'High' => 'warning',
                        'Moderate' => 'info',
                        'Low' => 'success',
                    }),

                TextEntry::make('station.name')
                    ->label('Station'),

                TextEntry::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'resolved' => 'success',
                        'investigating' => 'warning',
                        'unresolved' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextEntry::make('location_details')
                    ->label('Location'),

                // Patient & Staff
                TextEntry::make('admission.patient.name')
                    ->label('Patient')
                    ->default('N/A'),

                TextEntry::make('createdBy.name')
                    ->label('Reported By'),

                TextEntry::make('resolvedBy.name')
                    ->label('Resolved By')
                    ->default('Pending'),

                TextEntry::make('time_reported')
                    ->label('Time Reported')
                    ->dateTime('M d, Y H:i'),

                // Narrative
                TextEntry::make('narrative')
                    ->label('Summary'),

                TextEntry::make('what_happened')
                    ->label('What Happened'),

                TextEntry::make('how_discovered')
                    ->label('How Discovered'),

                TextEntry::make('action_taken')
                    ->label('Action Taken'),

                // Clinical Data
                TextEntry::make('vitals.temperature')
                    ->label('Temperature')
                    ->formatStateUsing(fn ($state) => $state ? $state . '°C' : 'N/A')
                    ->visible(fn ($record) => $record->vitals !== null),

                TextEntry::make('vitals.bp')
                    ->label('Blood Pressure')
                    ->visible(fn ($record) => $record->vitals !== null),

                TextEntry::make('vitals.hr')
                    ->label('Heart Rate')
                    ->formatStateUsing(fn ($state) => $state ? $state . ' bpm' : 'N/A')
                    ->visible(fn ($record) => $record->vitals !== null),

                TextEntry::make('vitals.pr')
                    ->label('Pulse Rate')
                    ->formatStateUsing(fn ($state) => $state ? $state . ' bpm' : 'N/A')
                    ->visible(fn ($record) => $record->vitals !== null),

                TextEntry::make('vitals.rr')
                    ->label('Respiratory Rate')
                    ->formatStateUsing(fn ($state) => $state ? $state . ' /min' : 'N/A')
                    ->visible(fn ($record) => $record->vitals !== null),

                TextEntry::make('vitals.o2')
                    ->label('O2 Saturation')
                    ->formatStateUsing(fn ($state) => $state ? $state . '%' : 'N/A')
                    ->visible(fn ($record) => $record->vitals !== null),

                // Injury & Notifications
                TextEntry::make('injury')
                    ->label('Injury Occurred')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'danger' : 'success'),

                TextEntry::make('injury_type')
                    ->label('Injury Type'),

                TextEntry::make('doctor_notified')
                    ->label('Doctor Notified')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'warning'),

                TextEntry::make('family_notified')
                    ->label('Family Notified')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'warning'),

                // Root Cause & Follow-up
                TextEntry::make('root_cause')
                    ->label('Root Cause')
                    ->formatStateUsing(fn ($state) => $state ? str_replace('_', ' ', ucfirst($state)) : 'N/A'),

                TextEntry::make('follow_up_actions')
                    ->label('Follow-up Actions'),

                TextEntry::make('follow_up_instructions')
                    ->label('Follow-up Instructions'),
            ]);
    }
}
