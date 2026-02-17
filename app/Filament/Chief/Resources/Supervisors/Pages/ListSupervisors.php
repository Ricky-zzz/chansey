<?php

namespace App\Filament\Chief\Resources\Supervisors\Pages;

use App\Filament\Chief\Resources\Supervisors\SupervisorResource;
use App\Models\Nurse;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\On;

class ListSupervisors extends ListRecords
{
    protected static string $resource = SupervisorResource::class;
    protected string $view = 'filament.chief.resources.supervisors.pages.list';

    public ?int $selectedSupervisorId = null;
    public $supervisorStats = null;

    #[On('openSupervisorStats')]
    public function openSupervisorStats($supervisorId)
    {
        $this->selectedSupervisorId = $supervisorId;
        $supervisor = Nurse::find($supervisorId);

        if ($supervisor) {
            $this->supervisorStats = [
                'name' => $supervisor->first_name . ' ' . $supervisor->last_name,
                'unit_name' => $supervisor->unit?->name,
                'station_count' => $supervisor->unit?->stations()->count() ?? 0,
                'nurse_count' => Nurse::where('unit_id', $supervisor->unit_id)->count(),
                'stations' => $supervisor->unit?->stations()->with('nurses')->get() ?? collect(),
            ];
        }
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
