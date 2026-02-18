<?php

namespace App\Filament\Chief\Resources\StaffNurses\Tables;

use App\Models\Nurse;
use App\Models\Station;
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

class StaffNursesTable
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

                TextColumn::make('nurseType.name')
                    ->label('Nurse Type')
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
                    Action::make('promote')
                        ->label('Promote to Head Nurse')
                        ->icon('heroicon-o-arrow-up-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Promote to Head Nurse')
                        ->modalDescription('Assign the nurse to lead a station. Only stations without a current Head Nurse are available.')
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
                                'unit_id' => null,
                                'designation' => $designation,
                            ]);

                            Notification::make()
                                ->title('Promotion Successful')
                                ->body("{$record->first_name} {$record->last_name} has been promoted to Head Nurse.")
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
