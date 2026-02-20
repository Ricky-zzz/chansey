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
    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $me = $user->nurse;

        if (!$me) {
            return Memo::query()->where('id', null); // Return empty query if no nurse record
        }

        $unitId = (int)($me->getUnitId() ?? 0);
        $stationId = (int)$me->station_id;
        $roleLevel = $me->role_level;

        // Query using indexed pivot tables (fast!)
        return Memo::with(['creator', 'targetRoles', 'targetUnits', 'targetStations'])
            ->where('created_by_user_id', '!=', $me->user_id)
            // Role must match
            ->whereHas('targetRoles', fn($q) => $q->where('role', $roleLevel))
            // Station OR Unit must match
            ->where(function($q) use ($unitId, $stationId) {
                $q->whereHas('targetStations', fn($sq) => $sq->where('station_id', $stationId))
                  ->orWhereHas('targetUnits', fn($uq) => $uq->where('unit_id', $unitId));
            })
            ->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            \Filament\Tables\Columns\TextColumn::make('title')->weight('bold'),
            \Filament\Tables\Columns\TextColumn::make('creator.name')->label('From'),
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
                BulkActionGroup::make([]),
            ]);
    }
}
