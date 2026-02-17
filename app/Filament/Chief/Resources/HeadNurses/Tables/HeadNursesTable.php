<?php

namespace App\Filament\Chief\Resources\HeadNurses\Tables;

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

class HeadNursesTable
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
                ActionGroup::make([
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
                    Action::make('promote')
                        ->label('Promote to Supervisor')
                        ->icon('heroicon-o-arrow-up-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Promote to Supervisor')
                        ->modalDescription('Assign the nurse to supervise a unit/building. Only units without a current Supervisor are available.')
                        ->form([
                            Select::make('unit_id')
                                ->label('Unit / Building Assignment')
                                ->options(function () {
                                    // Get units that don't have a supervisor
                                    $occupiedUnitIds = Nurse::where('role_level', 'Supervisor')
                                        ->whereNotNull('unit_id')
                                        ->pluck('unit_id')
                                        ->toArray();

                                    return Unit::whereNotIn('id', $occupiedUnitIds)
                                        ->pluck('name', 'id');
                                })
                                ->required()
                                ->searchable()
                                ->preload()
                                ->helperText('Only units without an assigned Supervisor are shown.'),
                        ])
                        ->action(function (Nurse $record, array $data) {
                            // Double-check no one else is supervisor of this unit
                            $existingSupervisor = Nurse::where('role_level', 'Supervisor')
                                ->where('unit_id', $data['unit_id'])
                                ->where('id', '!=', $record->id)
                                ->exists();

                            if ($existingSupervisor) {
                                Notification::make()
                                    ->title('Position Occupied')
                                    ->body('This unit already has a Supervisor assigned.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $record->update([
                                'role_level' => 'Supervisor',
                                'unit_id' => $data['unit_id'],
                                'station_id' => null, // Clear station - supervisors float
                            ]);

                            Notification::make()
                                ->title('Promotion Successful')
                                ->body("{$record->first_name} {$record->last_name} has been promoted to Supervisor.")
                                ->success()
                                ->send();
                        }),
                    Action::make('demote')
                        ->label('Demote to Staff')
                        ->icon('heroicon-o-arrow-down-circle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Demote to Staff Nurse')
                        ->modalDescription('Optionally reassign the nurse to a different station.')
                        ->form([
                            Select::make('station_id')
                                ->label('Station Assignment (Optional)')
                                ->options(Station::pluck('station_name', 'id'))
                                ->searchable()
                                ->preload()
                                ->helperText('Leave empty to keep the current station assignment.'),
                        ])
                        ->action(function (Nurse $record, array $data) {
                            $updateData = ['role_level' => 'Staff'];

                            // If station changed, update designation based on Admission station
                            if (!empty($data['station_id'])) {
                                $station = Station::find($data['station_id']);
                                $designation = ($station && $station->station_code === 'ADM') ? 'Admitting' : 'Clinical';
                                $updateData['station_id'] = $data['station_id'];
                                $updateData['designation'] = $designation;
                            }

                            $record->update($updateData);

                            Notification::make()
                                ->title('Demotion Complete')
                                ->body("{$record->first_name} {$record->last_name} is now a Staff Nurse.")
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
