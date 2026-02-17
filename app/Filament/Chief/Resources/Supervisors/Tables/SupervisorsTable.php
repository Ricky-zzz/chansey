<?php

namespace App\Filament\Chief\Resources\Supervisors\Tables;

use App\Models\Nurse;
use App\Models\Station;
use App\Models\Unit;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class SupervisorsTable
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

                TextColumn::make('unit.name')
                    ->label('Unit / Building')
                    ->default('—')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => $state === 'Active' ? 'success' : 'gray')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('nurse_type_id')
                    ->label('Nurse Type')
                    ->relationship('nurseType', 'name')
                    ->preload()
                    ->searchable(),

                SelectFilter::make('unit_id')
                    ->label('Unit')
                    ->relationship('unit', 'name')
                    ->preload()
                    ->searchable(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('viewStats')
                        ->label('View Stats')
                        ->icon('heroicon-o-chart-bar')
                        ->color('info')
                        ->modalHeading(fn(Nurse $record) => "{$record->first_name} {$record->last_name} - Unit Stats")
                        ->modalContent(function (Nurse $record) {
                            $stations = $record->unit?->stations()->with('nurses')->get() ?? collect();
                            $stationCount = $stations->count();
                            $totalNurses = Nurse::where('unit_id', $record->unit_id)->count();
                            $unitName = $record->unit?->name ?? 'N/A';

                            $stationsHtml = '';
                            foreach ($stations as $station) {
                                $count = $station->nurses->count();
                                $stationsHtml .= '<tr class="border-b hover:bg-gray-50"><td class="px-4 py-2 text-sm">' . $station->station_name . '</td><td class="px-4 py-2 text-sm"><span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">' . $count . '</span></td></tr>';
                            }

                            $tablesContent = '';
                            if ($stations->count()) {
                                $tablesContent = '<div class="border rounded-lg overflow-hidden"><table class="w-full"><thead class="bg-gray-100 border-b"><tr><th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Station</th><th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Nurses</th></tr></thead><tbody>' . $stationsHtml . '</tbody></table></div>';
                            }

                            $content = '<div class="space-y-4"><div class="p-4 bg-sky-50 rounded-lg border border-sky-200"><p class="text-sm text-gray-600">Unit / Building</p><p class="text-lg font-bold text-sky-900">' . $unitName . '</p></div><div class="grid grid-cols-2 gap-4"><div class="p-4 bg-blue-50 rounded-lg border border-blue-200"><p class="text-xs text-gray-600 mb-1">Total Stations</p><p class="text-3xl font-bold text-blue-600">' . $stationCount . '</p></div><div class="p-4 bg-green-50 rounded-lg border border-green-200"><p class="text-xs text-gray-600 mb-1">Total Nurses</p><p class="text-3xl font-bold text-green-600">' . $totalNurses . '</p></div></div>' . $tablesContent . '</div>';

                            return new HtmlString($content);
                        })
                        ->modalWidth('lg'),
                    Action::make('demote')
                        ->label('Demote to Head Nurse')
                        ->icon('heroicon-o-arrow-down-circle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Demote to Head Nurse')
                        ->modalDescription('Assign the nurse to lead a specific station. Only stations without a current Head Nurse are available.')
                        ->form([
                            Select::make('station_id')
                                ->label('Station Assignment')
                                ->options(function () {
                                    // Get stations that don't have a head nurse
                                    $occupiedStationIds = Nurse::where('role_level', 'Head')
                                        ->whereNotNull('station_id')
                                        ->pluck('station_id')
                                        ->toArray();

                                    return Station::whereNotIn('id', $occupiedStationIds)
                                        ->pluck('station_name', 'id');
                                })
                                ->required()
                                ->searchable()
                                ->preload()
                                ->helperText('Only stations without an assigned Head Nurse are shown.'),
                        ])
                        ->action(function (Nurse $record, array $data) {
                            // Double-check no one else is head of this station
                            $existingHead = Nurse::where('role_level', 'Head')
                                ->where('station_id', $data['station_id'])
                                ->where('id', '!=', $record->id)
                                ->exists();

                            if ($existingHead) {
                                Notification::make()
                                    ->title('Position Occupied')
                                    ->body('This station already has a Head Nurse assigned.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            // Check if station is Admission (ADM) to set designation
                            $station = Station::find($data['station_id']);
                            $designation = ($station && $station->station_code === 'ADM') ? 'Admitting' : 'Clinical';

                            $record->update([
                                'role_level' => 'Head',
                                'station_id' => $data['station_id'],
                                'unit_id' => null, // Clear unit
                                'designation' => $designation,
                            ]);

                            Notification::make()
                                ->title('Demotion Complete')
                                ->body("{$record->first_name} {$record->last_name} is now a Head Nurse.")
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
