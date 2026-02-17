<?php

namespace App\Filament\Chief\Resources\StaffNurses\Pages;

use App\Filament\Chief\Resources\StaffNurses\StaffNurseResource;
use App\Models\Station;
use App\Models\Unit;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\On;
use Illuminate\Database\Eloquent\Builder;

class ListStaffNurses extends ListRecords
{
    protected static string $resource = StaffNurseResource::class;
    protected string $view = 'filament.chief.resources.staff-nurses.pages.list';

    public ?int $selectedUnitId = null;
    public ?int $selectedStationId = null;
    public $units = [];
    public $stations = [];
    public $supervisor = null;
    public $stationInfo = null;

    public function mount(): void
    {
        parent::mount();
        $this->units = Unit::orderBy('name')->get()->toArray();
    }

    #[On('unit-filter-changed')]
    public function updateUnitFilter($unitId)
    {
        $this->selectedUnitId = $unitId ?: null;
        $this->selectedStationId = null;
        $this->supervisor = null;
        $this->stationInfo = null;

        if ($this->selectedUnitId) {
            $this->stations = Station::where('unit_id', $this->selectedUnitId)
                ->orderBy('station_name')
                ->get()
                ->toArray();

            // Get supervisor for this unit
            $this->supervisor = \App\Models\Nurse::where('role_level', 'Supervisor')
                ->where('unit_id', $this->selectedUnitId)
                ->with('user')
                ->first();
        }
    }

    #[On('station-filter-changed')]
    public function updateStationFilter($stationId)
    {
        $this->selectedStationId = $stationId ?: null;

        if ($this->selectedStationId) {
            $station = Station::find($this->selectedStationId);
            if ($station) {
                $this->stationInfo = [
                    'name' => $station->station_name,
                    'head_nurse' => $station->nurses()
                        ->where('role_level', 'Head')
                        ->first(),
                ];
            }
        }

        $this->resetPage();
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();

        if ($this->selectedStationId) {
            $query->where('station_id', $this->selectedStationId);
        } elseif ($this->selectedUnitId) {
            // Show no results if unit selected but no station
            $query->whereRaw('1 = 0');
        }

        return $query;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
