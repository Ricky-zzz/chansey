<?php

namespace App\Filament\Pharmacy\Resources\Medicines\Tables;

use App\Models\Medicine;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MedicinesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('generic_name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('brand_name')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('dosage')
                    ->searchable(),

                TextColumn::make('form')
                    ->badge(),

                TextColumn::make('stock_on_hand')
                    ->label('In Stock')
                    ->sortable()
                    ->color(
                        fn(Medicine $record): string =>
                        $record->stock_on_hand <= $record->critical_level ? 'danger' : 'success'
                    )
                    ->weight('bold'),

                TextColumn::make('price')
                    ->money('PHP')
                    ->sortable(),

                TextColumn::make('expiry_date')
                    ->date()
                    ->sortable()
                    ->color(
                        fn(Medicine $record): string =>
                        $record->expiry_date && $record->expiry_date->isPast() ? 'danger' :
                        ($record->expiry_date && $record->expiry_date->diffInMonths(now()) <= 3 ? 'warning' : 'success')
                    ),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->since(),
            ])
            ->filters([
                Filter::make('low_stock')
                    ->query(fn($query) => $query->whereColumn('stock_on_hand', '<=', 'critical_level'))
                    ->label('Low Stock Only'),

                Filter::make('expiring_soon')
                    ->query(fn($query) => $query->whereNotNull('expiry_date')
                        ->where('expiry_date', '<=', now()->addMonths(3)))
                    ->label('Expiring Soon'),

                Filter::make('expired')
                    ->query(fn($query) => $query->whereNotNull('expiry_date')
                        ->where('expiry_date', '<', now()))
                    ->label('Expired'),

                SelectFilter::make('form')
                    ->options([
                        'Tablet' => 'Tablet',
                        'Capsule' => 'Capsule',
                        'Syrup' => 'Syrup',
                        'Injection' => 'Injection',
                        'Cream' => 'Cream/Ointment',
                        'Drops' => 'Drops',
                        'Inhaler' => 'Inhaler',
                        'Suppository' => 'Suppository',
                    ]),
            ])
            ->recordActions([
                Action::make('adjust_stock')
                    ->label('Adjust')
                    ->icon('heroicon-o-arrows-up-down')
                    ->color('info')
                    ->form([
                        Select::make('type')
                            ->options([
                                'add' => 'Restock (Add)',
                                'sub' => 'Dispense (Remove)',
                            ])
                            ->default('sub')
                            ->required(),
                        TextInput::make('amount')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->minValue(1),
                    ])
                    ->action(function (Medicine $record, array $data) {
                        if ($data['type'] === 'add') {
                            $record->increment('stock_on_hand', $data['amount']);
                        } else {
                            if ($record->stock_on_hand < $data['amount']) {
                                Notification::make()
                                    ->title('Error')
                                    ->body('Not enough stock to dispense!')
                                    ->danger()
                                    ->send();
                                return;
                            }
                            $record->decrement('stock_on_hand', $data['amount']);
                        }

                        Notification::make()
                            ->title('Inventory Updated')
                            ->success()
                            ->send();
                    }),

                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
