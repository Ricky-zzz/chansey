<?php

namespace App\Filament\Supervisor\Resources\Memos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MemosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Announcement Title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('content')
                    ->label('Preview')
                    ->html()
                    ->formatStateUsing(function ($state) {
                        $text = strip_tags($state);
                        $preview = Str::limit($text, 80, '...');
                        return htmlspecialchars($preview);
                    }),

                TextColumn::make('targetRoles.role')
                    ->label('Target Roles')
                    ->getStateUsing(function ($record) {
                        $roles = $record->targetRoles->pluck('role')->toArray();
                        if (empty($roles)) {
                            return '—';
                        }
                        $display = implode(', ', array_slice($roles, 0, 3));
                        $count = count($roles);
                        if ($count > 3) {
                            $display .= ' +' . ($count - 3);
                        }
                        return $display;
                    }),

                TextColumn::make('targetStations.station_name')
                    ->label('Target Stations')
                    ->getStateUsing(function ($record) {
                        $stationNames = $record->targetStations->pluck('station_name')->toArray();
                        if (empty($stationNames)) {
                            return '—';
                        }
                        $display = implode(', ', array_slice($stationNames, 0, 2));
                        $count = count($stationNames);
                        if ($count > 2) {
                            $display .= ' +' . ($count - 2);
                        }
                        return $display;
                    }),

                TextColumn::make('attachment_path')
                    ->label('Attachment')
                    ->getStateUsing(function ($record) {
                        return empty($record->attachment_path) ? '—' : '📎 View';
                    })
                    ->url(function ($record) {
                        if (empty($record->attachment_path)) {
                            return null;
                        }
                        return Storage::url($record->attachment_path);
                    })
                    ->openUrlInNewTab(),

                TextColumn::make('creator.name')
                    ->label('Created By')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
