<?php

namespace App\Livewire\Chief;

use App\Models\Nurse;
use App\Models\Station;
use App\Models\Unit;
use Livewire\Component;

class HeadNurseFilter extends Component
{
    public ?int $unitId = null;
    public $headNurses = [];
    public $units = [];

    public function mount()
    {
        $this->units = Unit::all()->keyBy('id');
    }

    #[\Livewire\Attributes\On('unit-selected')]
    public function filterByUnit($unitId)
    {
        $this->unitId = $unitId;
        $this->headNurses = Nurse::where('role_level', 'Head')
            ->whereHas('station', function ($query) use ($unitId) {
                $query->where('unit_id', $unitId);
            })
            ->with(['station', 'nurseType', 'user'])
            ->get();
    }

    public function render()
    {
        return view('livewire.chief.head-nurse-filter');
    }
}
