<?php

namespace App\Filament\Supervisor\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Memo;
use Filament\Actions\ViewAction;

class IncomingMemos extends TableWidget
{
    protected function getTableQuery(): Builder
    {
        $me = \Illuminate\Support\Facades\Auth::user()->nurse;
        return Memo::query()
            ->where('created_by_user_id', '!=', $me->user_id)
            ->where(function($q) use ($me) {
                $q->whereJsonContains('target_roles', 'Supervisor')
                  ->orWhereJsonContains('target_units', (string)$me->unit_id);
            })
            ->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            \Filament\Tables\Columns\TextColumn::make('title')->weight('bold'),
            \Filament\Tables\Columns\TextColumn::make('created_by_user.name')->label('From'),
            \Filament\Tables\Columns\TextColumn::make('created_at')->date(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            ViewAction::make()
                ->modalHeading('Memo Details')
                ->modalContent(fn ($record) => view('filament.supervisor.memos.view', ['memo' => $record])),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => $this->getTableQuery())
            ->columns($this->getTableColumns())
            ->filters([])
            ->headerActions([])
            ->recordActions($this->getTableActions())
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([]),
            ]);
    }
}
