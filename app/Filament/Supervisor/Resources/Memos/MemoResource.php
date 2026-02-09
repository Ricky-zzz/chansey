<?php

namespace App\Filament\Supervisor\Resources\Memos;

use App\Filament\Supervisor\Resources\Memos\Pages\CreateMemo;
use App\Filament\Supervisor\Resources\Memos\Pages\EditMemo;
use App\Filament\Supervisor\Resources\Memos\Pages\ListMemos;
use App\Filament\Supervisor\Resources\Memos\Schemas\MemoForm;
use App\Filament\Supervisor\Resources\Memos\Tables\MemosTable;
use App\Models\Memo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MemoResource extends Resource
{
    protected static ?string $model = Memo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('created_by_user_id', Auth::id());
    }
}
