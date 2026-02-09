<?php

namespace App\Filament\Chief\Resources\Memos;

use App\Filament\Chief\Resources\Memos\Pages\CreateMemo;
use App\Filament\Chief\Resources\Memos\Pages\EditMemo;
use App\Filament\Chief\Resources\Memos\Pages\ListMemos;
use App\Filament\Chief\Resources\Memos\Schemas\MemoForm;
use App\Filament\Chief\Resources\Memos\Tables\MemosTable;
use App\Models\Memo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MemoResource extends Resource
{
    protected static ?string $model = Memo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Announcements';

    protected static ?string $navigationLabel = 'Memos';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return MemoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MemosTable::configure($table);
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
            'index' => ListMemos::route('/'),
            'create' => CreateMemo::route('/create'),
            'edit' => EditMemo::route('/{record}/edit'),
        ];
    }
}
