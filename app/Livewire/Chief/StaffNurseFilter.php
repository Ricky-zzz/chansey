<?php

namespace App\Livewire\Chief;

use App\Models\Nurse;
use App\Models\Station;
use App\Models\Unit;
use Livewire\Component;

class StaffNurseFilter extends Component
{
    public ?int $unitId = null;
    public ?int $stationId = null;
    public $staffNurses = [];
    public $units = [];
    public $stations = [];
    public $supervisor = null;
    public $stationData = null;

    public function mount()
    {
        $this->units = Unit::all()->keyBy('id');
    }

    #[\Livewire\Attributes\On('unit-selected')]
    public function filterByUnit($unitId)
    {
        $this->unitId = $unitId;
        $this->stationId = null;
        $this->staffNurses = [];
        $this->stationData = null;

        // Get all stations for this unit
        $this->stations = Station::where('unit_id', $unitId)
            ->with(['nurses'])
            ->get()
            ->keyBy('id');
    }

    #[\Livewire\Attributes\On('station-selected')]
    public function filterByStation($stationId)
    {
        $this->stationId = $stationId;

        // Get station data
        $station = Station::find($stationId);
        if ($station) {
            $this->stationData = $station;

            // Get supervisor for this unit
            $this->supervisor = Nurse::where('role_level', 'Supervisor')
                ->where('unit_id', $station->unit_id)
                ->with('user')
                ->first();

            // Get staff nurses in this station
            $this->staffNurses = Nurse::where('role_level', 'Staff')
                ->where('station_id', $stationId)
                ->with(['station', 'nurseType', 'user'])
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.chief.staff-nurse-filter');
    }
}
