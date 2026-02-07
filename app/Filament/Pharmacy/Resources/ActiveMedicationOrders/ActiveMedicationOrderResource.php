<?php

namespace App\Filament\Pharmacy\Resources\ActiveMedicationOrders;

use App\Filament\Pharmacy\Resources\ActiveMedicationOrders\Pages\ListActiveOrders;
use App\Models\MedicalOrder;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ActiveMedicationOrderResource extends Resource
{
    protected static ?string $model = MedicalOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;

    protected static ?string $navigationLabel = 'Active Medications';

    protected static ?string $modelLabel = 'Medication Order';

    protected static ?string $pluralModelLabel = 'Medication Orders';

    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                MedicalOrder::query()
                    ->where('type', 'Medication')
                    ->whereIn('status', ['Active', 'Pending'])
                    ->with(['admission.patient', 'admission.bed', 'medicine', 'dispensedBy'])
                    ->orderBy('created_at', 'desc')
            )
            ->columns([
                TextColumn::make('admission.patient.full_name')
                    ->label('Patient Name')
                    ->searchable(['admission.patient.first_name', 'admission.patient.last_name']),

                TextColumn::make('admission.bed.bed_code')
                    ->label('Room')
                    ->badge()
                    ->color('info'),

                TextColumn::make('medicine.generic_name')
                    ->label('Medicine')
                    ->searchable()
                    ->description(fn($record) => $record->medicine?->brand_name),

                TextColumn::make('medicine.dosage')
                    ->label('Dosage'),

                TextColumn::make('quantity')
                    ->label('Qty'),

                TextColumn::make('frequency')
                    ->label('Frequency')
                    ->badge()
                    ->color(fn($state) => match (true) {
                        $state === 'PRN' => 'danger',
                        str_contains($state ?? '', 'Hour') => 'info',
                        $state === 'Once' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('medicine.stock_on_hand')
                    ->label('Stock')
                    ->color(fn($state) => $state <= 20 ? 'danger' : 'success'),

                IconColumn::make('dispensed')
                    ->boolean()
                    ->label('Dispensed?'),

                TextColumn::make('dispensed_at')
                    ->label('Dispensed At')
                    ->dateTime('M d H:i')
                    ->placeholder('—'),

                TextColumn::make('dispensedBy.name')
                    ->label('Dispensed By')
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('dispensed')
                    ->options([
                        '1' => 'Dispensed',
                        '0' => 'Pending Dispense',
                    ]),
            ])
            ->recordActions([
                Action::make('dispense')
                    ->label('Dispense')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->hidden(fn($record) => $record->dispensed)
                    ->requiresConfirmation()
                    ->modalHeading('Dispense Medication')
                    ->modalDescription(fn($record) => "Dispense {$record->quantity} x {$record->medicine?->generic_name} for {$record->admission?->patient?->first_name}?")
                    ->action(fn($record) => static::dispenseOrder($record)),
            ]);
    }

    public static function dispenseOrder(MedicalOrder $order): void
    {
        try {
            if ($order->medicine->stock_on_hand < $order->quantity) {
                throw new \Exception("Not enough stock for {$order->medicine->generic_name}!");
            }

            $order->medicine->decrement('stock_on_hand', $order->quantity);

            $order->update([
                'dispensed' => true,
                'dispensed_by_user_id' => Auth::id(),
                'dispensed_at' => now(),
            ]);

            Notification::make()
                ->title('Success')
                ->body("{$order->medicine->generic_name} dispensed for {$order->admission->patient->first_name}")
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActiveOrders::route('/'),
        ];
    }
}
