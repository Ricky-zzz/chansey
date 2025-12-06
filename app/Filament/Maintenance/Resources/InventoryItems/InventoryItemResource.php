<?php

namespace App\Filament\Maintenance\Resources\InventoryItems;

use App\Filament\Maintenance\Resources\InventoryItems\Pages;
use App\Models\InventoryItem;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use Filament\Schemas\Components\Section;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Tables\Filters\Filter;
use Filament\Notifications\Notification;

class InventoryItemResource extends Resource
{
    protected static ?string $model = InventoryItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $recordTitleAttribute = 'item_name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Item Details')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('item_name')
                                ->required()
                                ->placeholder('e.g. Pillow Case'),

                            Select::make('category')
                                ->options([
                                    'Linens' => 'Linens (Sheets, Pillows)',
                                    'Toiletries' => 'Toiletries (Soap, Tissue)',
                                    'Cleaning' => 'Cleaning Supplies',
                                    'Medical' => 'Basic Medical (Gloves, Masks)',
                                ])
                                ->required(),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('quantity')
                                ->numeric()
                                ->default(0)
                                ->label('Current Stock'),

                            TextInput::make('critical_level')
                                ->numeric()
                                ->default(10)
                                ->label('Low Stock Warning Level')
                                ->helperText('System will alert if stock falls below this.'),
                        ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('item_name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('category')
                    ->badge(),

                // SMART COLUMN: Changes color based on stock level
                TextColumn::make('quantity')
                    ->label('In Stock')
                    ->sortable()
                    ->color(
                        fn(InventoryItem $record): string =>
                        $record->quantity <= $record->critical_level ? 'danger' : 'success'
                    )
                    ->weight('bold'),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->since(),
            ])
            ->filters([
                // Filter to quickly see what is running low
                Filter::make('low_stock')
                    ->query(fn($query) => $query->whereColumn('quantity', '<=', 'critical_level'))
                    ->label('Low Stock Only'),
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
                    ->action(function (InventoryItem $record, array $data) {
                        if ($data['type'] === 'add') {
                            $record->increment('quantity', $data['amount']);
                        } else {
                            // Prevent negative stock
                            if ($record->quantity < $data['amount']) {
                                Notification::make()
                                    ->title('Error')
                                    ->body('Not enough stock to dispense!')
                                    ->danger()
                                    ->send();
                                return;
                            }
                            $record->decrement('quantity', $data['amount']);
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventoryItems::route('/'),
            'create' => Pages\CreateInventoryItem::route('/create'),
            'edit' => Pages\EditInventoryItem::route('/{record}/edit'),
        ];
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }
}
