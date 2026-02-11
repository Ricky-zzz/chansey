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

        // Get unit: from nurse record OR from station (for Head Nurses)
        $unitId = (int)($me->getUnitId() ?? 0);
        $stationId = (int)$me->station_id;
        $roleLevel = $me->role_level;

        return Memo::query()
            // Don't show my own memos in inbox
            ->where('created_by_user_id', '!=', $me->user_id)
            ->where(function($q) use ($unitId, $stationId, $roleLevel) {

                // STRICT MATCHING: All specified constraints must match

                // Constraint 1: Role must match (if specified)
                $q->where(function($roleCheck) use ($roleLevel) {
                    $roleCheck->whereJsonContains('target_roles', $roleLevel)
                              ->orWhereNull('target_roles')
                              ->orWhereJsonLength('target_roles', 0);
                })

                // Constraint 2: Unit must match (if specified)
                ->where(function($unitCheck) use ($unitId) {
                    $unitCheck->whereJsonContains('target_units', $unitId)
                              ->orWhereNull('target_units')
                              ->orWhereJsonLength('target_units', 0);
                })

                // Constraint 3: Station must match (if specified)
                ->where(function($stationCheck) use ($stationId) {
                    $stationCheck->whereJsonContains('target_stations', $stationId)
                                 ->orWhereNull('target_stations')
                                 ->orWhereJsonLength('target_stations', 0);
                });
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
