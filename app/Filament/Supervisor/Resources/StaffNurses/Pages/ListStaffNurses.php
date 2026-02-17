<?php

namespace App\Filament\Supervisor\Resources\StaffNurses\Pages;

use App\Filament\Supervisor\Resources\StaffNurses\StaffNurseResource;
use App\Models\Station;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\On;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListStaffNurses extends ListRecords
{
    protected static string $resource = StaffNurseResource::class;
    protected string $view = 'filament.supervisor.resources.staff-nurses.pages.list';

    public ?int $selectedStationId = null;
    public $stations = [];

    public function mount(): void
    {
        parent::mount();
        $supervisorUnitId = Auth::user()->nurse->unit_id;
        $this->stations = Station::where('unit_id', $supervisorUnitId)
            ->orderBy('station_name')
            ->get()
            ->toArray();
    }

    #[On('station-filter-changed')]
    public function updateStationFilter($stationId)
    {
        $this->selectedStationId = $stationId ?: null;
        $this->resetPage();
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();

        if ($this->selectedStationId) {
            $query->where('station_id', $this->selectedStationId);
        } else {
            // Show no results until a station is selected
            $query->whereRaw('1 = 0');
        }

        return $query;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
