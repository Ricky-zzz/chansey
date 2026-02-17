<?php

namespace App\Filament\Chief\Resources\HeadNurses\Pages;

use App\Filament\Chief\Resources\HeadNurses\HeadNurseResource;
use App\Models\Unit;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\On;
use Illuminate\Database\Eloquent\Builder;

class ListHeadNurses extends ListRecords
{
    protected static string $resource = HeadNurseResource::class;
    protected string $view = 'filament.chief.resources.head-nurses.pages.list';

    public ?int $selectedUnitId = null;
    public $units = [];
    public $supervisor = null;

    public function mount(): void
    {
        parent::mount();
        $this->units = Unit::orderBy('name')->get()->toArray();
    }

    #[On('unit-filter-changed')]
    public function updateUnitFilter($unitId)
    {
        $this->selectedUnitId = $unitId ?: null;
        $this->supervisor = null;
        $this->resetPage();

        if ($this->selectedUnitId) {
            // Get supervisor for this unit
            $this->supervisor = \App\Models\Nurse::where('role_level', 'Supervisor')
                ->where('unit_id', $this->selectedUnitId)
                ->with('user')
                ->first();
        }
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();

        if ($this->selectedUnitId) {
            $query->whereHas('station', function (Builder $query) {
                $query->where('unit_id', $this->selectedUnitId);
            });
        }

        return $query;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
