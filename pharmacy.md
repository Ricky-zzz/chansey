# Implementation Plan: Active Medications with Dispensed Status

## Overview
Add `dispensed` status to MedicalOrder so pharmacists batch-dispense one dose per cycle. Nurses can only administer dispensed meds. One-at-a-time prevents waste and keeps audit trail clean.

---

## Step 1: Database Migration

Create migration file: 

php artisan make:migration add_dispensed_to_medical_orders_table


**File:** `database/migrations/YYYY_MM_DD_XXXXXX_add_dispensed_to_medical_orders_table.php`

Add to the `up()` method:
```php
Schema::table('medical_orders', function (Blueprint $table) {
    $table->boolean('dispensed')->default(false)->after('status');
    $table->foreignId('dispensed_by_user_id')->nullable()->after('dispensed');
    $table->timestamp('dispensed_at')->nullable()->after('dispensed_by_user_id');
    
    // Add foreign key constraint
    $table->foreign('dispensed_by_user_id')
          ->references('id')
          ->on('users')
          ->nullOnDelete();
});

Add to the down() method:
<?php
Schema::table('medical_orders', function (Blueprint $table) {
    $table->dropForeignKey(['dispensed_by_user_id']);
    $table->dropColumn(['dispensed', 'dispensed_by_user_id', 'dispensed_at']);
});

Run migration:

php artisan migrate


Step 2: Update MedicalOrder Model
File: MedicalOrder.php

2a. Add to $fillable array:
<?php
'dispensed', 'dispensed_by_user_id', 'dispensed_at',


2b. Add to $casts array:

<?php
'dispensed' => 'boolean',
'dispensed_at' => 'datetime',

2c. Add relationship (at end of class, before closing brace):
<?php
public function dispensedBy()
{
    return $this->belongsTo(User::class, 'dispensed_by_user_id');
}


Step 3: Create Pharmacy Resource - Active Medications
Create files structure:

app/Filament/Pharmacy/Resources/ActiveMedicationOrders/
├── ActiveMedicationOrderResource.php
├── Pages/
│   ├── ListActiveOrders.php
│   ├── ViewOrder.php (optional detail view)
│   └── DispenseAction.php (custom action handler)
└── Tables/
    └── ActiveOrdersTable.php

3a. Create Resource File:

<?php

namespace App\Filament\Pharmacy\Resources\ActiveMedicationOrders;

use App\Models\MedicalOrder;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class ActiveMedicationOrderResource extends Resource
{
    protected static ?string $model = MedicalOrder::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $navigationLabel = 'Active Medications';
    protected static ?string $modelLabel = 'Medication Order';
    protected static ?string $pluralModelLabel = 'Medication Orders';
    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return ActiveOrdersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActiveOrders::route('/'),
        ];
    }
}


3b. Create Table Configuration:

File: app/Filament/Pharmacy/Resources/ActiveMedicationOrders/Tables/ActiveOrdersTable.php

<?php

namespace App\Filament\Pharmacy\Resources\ActiveMedicationOrders\Tables;

use App\Models\MedicalOrder;
use Filament\Tables;
use Filament\Tables\Table;

class ActiveOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                MedicalOrder::where('type', 'Medication')
                    ->where(fn($q) => $q->where('status', 'Active')
                        ->orWhere('status', 'Pending'))
                    ->with(['admission.patient', 'medicine', 'dispensedBy'])
                    ->orderBy('created_at', 'desc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('admission.patient.first_name')
                    ->label('Patient Name')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('medicine.generic_name')
                    ->label('Medicine')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('medicine.dosage')
                    ->label('Dosage'),
                
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Qty'),
                
                Tables\Columns\BadgeColumn::make('frequency')
                    ->label('Frequency')
                    ->colors([
                        'danger' => 'PRN',
                        'info' => fn($state) => str_contains($state, 'Hour'),
                        'success' => 'Once',
                    ]),
                
                Tables\Columns\TextColumn::make('medicine.stock_on_hand')
                    ->label('Stock')
                    ->color(fn($state) => $state <= 20 ? 'danger' : 'success'),
                
                Tables\Columns\IconColumn::make('dispensed')
                    ->boolean()
                    ->label('Dispensed?'),
                
                Tables\Columns\TextColumn::make('dispensed_at')
                    ->label('Dispensed At')
                    ->dateTime('M d H:i'),
                
                Tables\Columns\TextColumn::make('dispensedBy.name')
                    ->label('Dispensed By'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('dispensed')
                    ->options([
                        '1' => 'Dispensed',
                        '0' => 'Pending Dispense',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('dispense')
                    ->label('Dispense')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->hidden(fn($record) => $record->dispensed) // Hide if already dispensed
                    ->action(fn($record) => static::dispenseOrder($record)),
            ])
            ->bulkActions([
                // optional: batch dispense multiple at once
            ]);
    }

    public static function dispenseOrder(MedicalOrder $order): void
    {
        try {
            // Validate stock
            if ($order->medicine->stock_on_hand < $order->quantity) {
                throw new \Exception("Not enough stock for {$order->medicine->generic_name}!");
            }

            // Decrement stock
            $order->medicine->decrement('stock_on_hand', $order->quantity);

            // Mark as dispensed
            $order->update([
                'dispensed' => true,
                'dispensed_by_user_id' => auth()->id(),
                'dispensed_at' => now(),
            ]);

            // Success notification
            \Filament\Notifications\Notification::make()
                ->title('Success')
                ->body("{$order->medicine->generic_name} dispensed for {$order->admission->patient->first_name}")
                ->success()
                ->send();

        } catch (\Exception $e) {
            \Filament\Notifications\Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}

3c. Create List Page:

File: app/Filament/Pharmacy/Resources/ActiveMedicationOrders/Pages/ListActiveOrders.php

<?php

namespace App\Filament\Pharmacy\Resources\ActiveMedicationOrders\Pages;

use App\Filament\Pharmacy\Resources\ActiveMedicationOrders\ActiveMedicationOrderResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActiveOrders extends ListRecords
{
    protected static string $resource = ActiveMedicationOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Add refresh action if needed
        ];
    }
}


Step 4: Update ClinicalLogController
File: ClinicalLogController.php

4a. Add validation before creating log (around line 40):

<?php
// Validate medicine is dispensed
if ($type === 'Medication' && $order && !$order->dispensed) {
    throw new \Exception("Medicine not yet dispensed by pharmacy! Contact pharmacy.");
}

4b. Update stock decrement logic (around line 50):

Replace:
<?php
if ($order->medicine->stock_on_hand < $order->quantity) {
    throw new \Exception("Not enough stock for {$order->medicine->name}!");
}

$order->medicine->decrement('stock_on_hand', $order->quantity);

With:
<?php
// Stock already decremented at dispense time
// Just verify it happened correctly
if (!$order->dispensed) {
    throw new \Exception("Medicine must be dispensed first!");
}

4c. Update order status logic (around line 75):
<?php
if ($order->frequency === 'Once' || $order->type === 'Utility') {
    $order->update(['status' => 'Done']);
}
else {
    if ($order->status === 'Pending') {
        $order->update(['status' => 'Active']);
    }
}
With:
<?php
// Reset dispensed for recurring meds (nurse administered = ready for next dose)
if ($order->frequency !== 'Once' && $order->type !== 'Utility') {
    $order->update(['dispensed' => false]); // Back to pharmacy for next dose
}
else {
    $order->update(['status' => 'Done']); // One-time order complete
}

// Update status to Active on first dose
if ($order->status === 'Pending') {
    $order->update(['status' => 'Active']);
}

Step 5: Update Nurse Ward View
File: show.blade.php

Around line 102-110 (where order type badge and action buttons are):

5a. Add dispensed badge after type badge:

<!-- Order Type Badge -->
<span class="px-3 py-1 rounded-full text-sm font-bold ...">
    {{ $order->type }}
</span>

<!-- Dispensed Status Badge -->
<span class="badge {{ $order->dispensed ? 'badge-success' : 'badge-warning' }}">
    @if($order->dispensed)
         Dispensed
    @else
         Awaiting Pharmacy
    @endif
</span>

<span class="text-xs font-bold text-slate-500">{{ $order->frequency }}</span>


5b. Update medication button (around line 154):

Replace current button with:
@elseif($order->type === 'Medication')
<button
    @click="openLogModal(@js($order->id), @js('Medication'), @js($order->medicine?->brand_name ?? ''), @js($order->quantity))"
    class="btn btn-sm btn-primary w-full gap-2"
    @if(!$order->dispensed) disabled title="Waiting for pharmacy to dispense" @endif
>
    {{ $order->dispensed ? ' Give Medication' : ' Awaiting Dispense' }}
</button>

no need to back fill i plan to migrate fresh so we can test it out freshly
