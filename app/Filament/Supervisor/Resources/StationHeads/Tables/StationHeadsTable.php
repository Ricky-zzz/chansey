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
use Illuminate\Support\HtmlString;
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

                TextColumn::make('station.station_name')
                    ->label('Station')
                    ->default('—')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => $state === 'Active' ? 'success' : 'gray')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('station_id')
                    ->label('Station')
                    ->relationship('station', 'station_name', fn($query) => $query->where('unit_id', Auth::user()->nurse->unit_id))
                    ->preload()
                    ->searchable(),
            ])
            ->recordActions([
                Action::make('viewStats')
                    ->label('View Stats')
                    ->icon('heroicon-o-chart-bar')
                    ->color('info')
                    ->modalHeading(fn(Nurse $record) => "{$record->first_name} {$record->last_name} - Station Stats")
                    ->modalContent(function (Nurse $record) {
                        $station = $record->station;
                        if (!$station) {
                            return new HtmlString('<div class="p-4 text-center text-gray-500">No station assigned</div>');
                        }

                        $nurseCount = Nurse::where('station_id', $station->id)->count();
                        $stationName = $station->station_name;

                        $content = '<div class="space-y-4"><div class="p-4 bg-sky-50 rounded-lg border border-sky-200"><p class="text-sm text-gray-600">Station / Department</p><p class="text-lg font-bold text-sky-900">' . $stationName . '</p></div><div class="grid grid-cols-1 gap-4"><div class="p-4 bg-green-50 rounded-lg border border-green-200"><p class="text-xs text-gray-600 mb-1">Total Nurses Under This Station</p><p class="text-3xl font-bold text-green-600">' . $nurseCount . '</p></div></div></div>';

                        return new HtmlString($content);
                    })
                    ->modalWidth('lg'),
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
            ->toolbarActions([]);
    }
}
