<?php

namespace App\Filament\Pharmacy\Resources\Medicines;

use App\Filament\Pharmacy\Resources\Medicines\Pages\CreateMedicine;
use App\Filament\Pharmacy\Resources\Medicines\Pages\EditMedicine;
use App\Filament\Pharmacy\Resources\Medicines\Pages\ListMedicines;
use App\Models\Medicine;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Carbon\Carbon;

class MedicineResource extends Resource
{
    protected static ?string $model = Medicine::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'generic_name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Medicine Details')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('generic_name')
                                ->required()
                                ->placeholder('e.g. Paracetamol'),

                            TextInput::make('brand_name')
                                ->placeholder('e.g. Biogesic'),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('dosage')
                                ->required()
                                ->placeholder('e.g. 500mg'),

                            Select::make('form')
                                ->options([
                                    'Tablet' => 'Tablet',
                                    'Capsule' => 'Capsule',
                                    'Syrup' => 'Syrup',
                                    'Injection' => 'Injection',
                                    'Cream' => 'Cream/Ointment',
                                    'Drops' => 'Drops',
                                    'Inhaler' => 'Inhaler',
                                    'Suppository' => 'Suppository',
                                ])
                                ->required(),
                        ]),
                    ]),

                Section::make('Inventory & Pricing')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('stock_on_hand')
                                ->numeric()
                                ->default(0)
                                ->label('Current Stock'),

                            TextInput::make('critical_level')
                                ->numeric()
                                ->default(20)
                                ->label('Low Stock Warning Level')
                                ->helperText('System will alert if stock falls below this.'),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('price')
                                ->numeric()
                                ->prefix('₱')
                                ->default(0.00)
                                ->label('Unit Price'),

                            DatePicker::make('expiry_date')
                                ->label('Expiry Date'),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
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
                    ->color(function (Medicine $record): string {
                        if (!$record->expiry_date) {
                            return 'success';
                        }
                        $expiry = Carbon::parse($record->expiry_date);
                        if ($expiry->isPast()) {
                            return 'danger';
                        }
                        if ($expiry->diffInMonths(now()) <= 3) {
                            return 'warning';
                        }
                        return 'success';
                    }),

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
                    ->schema([
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMedicines::route('/'),
            'create' => CreateMedicine::route('/create'),
            'edit' => EditMedicine::route('/{record}/edit'),
        ];
    }
}
