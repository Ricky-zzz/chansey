<?php

namespace App\Filament\Supervisor\Resources\Stations\Pages;

use App\Models\Station;
use App\Models\Nurse;
use App\Models\Room;
use App\Filament\Supervisor\Resources\Stations\StationResource;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class ManageStation extends Page
{
    protected static string $resource = StationResource::class;

    protected string $view = 'filament.supervisor.resources.stations.manage';

    public $station;
    public $stationHead;
    public $rooms;

    public bool $showCreateRoomModal = false;
    public bool $showManageBedsModal = false;
    public $selectedRoom = null;

    #[Validate('required|string|max:10')]
    public $room_number = '';

    #[Validate('required|string|in:Single,Double,Ward')]
    public $room_type = '';

    #[Validate('required|integer|min:1')]
    public $capacity = '';

    #[Validate('nullable|numeric|min:0')]
    public $price_per_night = '';

    // Edit room properties
    public bool $showEditRoomModal = false;
    public $editingRoomId = null;

    #[Validate('required|string|max:10')]
    public $edit_room_number = '';

    #[Validate('required|string|in:Single,Double,Ward')]
    public $edit_room_type = '';

    #[Validate('required|integer|min:1')]
    public $edit_capacity = '';

    #[Validate('nullable|numeric|min:0')]
    public $edit_price_per_night = '';

    // Delete room properties
    public bool $showDeleteRoomModal = false;
    public $deletingRoomId = null;

    // Bed properties
    #[Validate('required|string|max:20')]
    public $bed_code = '';

    // Delete bed properties
    public bool $showDeleteBedModal = false;
    public $deletingBedId = null;

    public function mount($record)
    {
        // Get the station
        $this->station = Station::find($record);

        if (!$this->station) {
            abort(404);
        }

        // Verify supervisor has access to this station
        $supervisor = Auth::user()->nurse;
        if ($this->station->unit_id !== $supervisor->unit_id) {
            abort(403, 'Unauthorized');
        }

        // Get station head (Head nurse assigned to this station)
        $this->stationHead = Nurse::where('station_id', $this->station->id)
            ->where('role_level', 'Head')
            ->first();

        // Get all rooms in this station
        $this->loadRooms();
    }

    private function loadRooms()
    {
        $this->rooms = $this->station->rooms()
            ->with('beds')
            ->get();
    }

    public function openCreateRoomModal()
    {
        $this->resetCreateRoomForm();
        $this->showCreateRoomModal = true;
    }

    public function closeCreateRoomModal()
    {
        $this->showCreateRoomModal = false;
        $this->resetCreateRoomForm();
    }

    public function resetCreateRoomForm()
    {
        $this->room_number = '';
        $this->room_type = '';
        $this->capacity = '';
        $this->price_per_night = '';
        $this->resetErrorBag();
    }

    public function createRoom()
    {
        try {
            // Only validate create room fields
            $this->validate([
                'room_number' => 'required|string|max:10',
                'room_type' => 'required|string|in:Single,Double,Ward',
                'capacity' => 'required|integer|min:1',
                'price_per_night' => 'nullable|numeric|min:0',
            ]);

            if (!$this->station) {
                \Filament\Notifications\Notification::make()
                    ->title('Error')
                    ->body('Station not found')
                    ->danger()
                    ->send();
                return;
            }

            Room::create([
                'station_id' => $this->station->id,
                'room_number' => $this->room_number,
                'room_type' => $this->room_type,
                'capacity' => (int)$this->capacity,
                'price_per_night' => $this->price_per_night ? (float)$this->price_per_night : 0.00,
                'status' => 'Available',
            ]);

            $this->loadRooms();
            $this->closeCreateRoomModal();
            \Filament\Notifications\Notification::make()
                ->title('Success')
                ->body('Room created successfully')
                ->success()
                ->send();
        } catch (\Exception $e) {
            \Filament\Notifications\Notification::make()
                ->title('Error')
                ->body('Failed to create room: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function openManageBedsModal($roomId)
    {
        $this->selectedRoom = Room::with('beds')->find($roomId);
        $this->showManageBedsModal = true;
    }

    public function closeManageBedsModal()
    {
        $this->showManageBedsModal = false;
        $this->selectedRoom = null;
    }

    public function confirmDeleteRoom($roomId)
    {
        $this->deletingRoomId = $roomId;
        $this->showDeleteRoomModal = true;
    }

    public function closeDeleteRoomModal()
    {
        $this->showDeleteRoomModal = false;
        $this->deletingRoomId = null;
    }

    public function deleteRoom()
    {
        $room = Room::find($this->deletingRoomId);

        if (!$room || $room->station_id !== $this->station->id) {
            $this->closeDeleteRoomModal();
            return;
        }

        if ($room->beds()->where('status', 'Occupied')->exists()) {
            \Filament\Notifications\Notification::make()
                ->title('Error')
                ->body('Cannot delete room with occupied beds')
                ->danger()
                ->send();
            $this->closeDeleteRoomModal();
            return;
        }

        $room->beds()->delete();
        $room->delete();

        $this->closeDeleteRoomModal();
        $this->loadRooms();
        \Filament\Notifications\Notification::make()
            ->title('Success')
            ->body('Room deleted successfully')
            ->success()
            ->send();
    }

    public function openEditRoomModal($roomId)
    {
        $room = Room::find($roomId);
        if (!$room || $room->station_id !== $this->station->id) {
            return;
        }

        $this->editingRoomId = $roomId;
        $this->edit_room_number = $room->room_number;
        $this->edit_room_type = $room->room_type;
        $this->edit_capacity = $room->capacity;
        $this->edit_price_per_night = $room->price_per_night;
        $this->showEditRoomModal = true;
    }

    public function closeEditRoomModal()
    {
        $this->showEditRoomModal = false;
        $this->editingRoomId = null;
        $this->resetEditRoomForm();
    }

    public function resetEditRoomForm()
    {
        $this->edit_room_number = '';
        $this->edit_room_type = '';
        $this->edit_capacity = '';
        $this->edit_price_per_night = '';
        $this->resetErrorBag();
    }

    public function updateRoom()
    {
        $this->validate([
            'edit_room_number' => 'required|string|max:10',
            'edit_room_type' => 'required|string|in:Single,Double,Ward',
            'edit_capacity' => 'required|integer|min:1',
            'edit_price_per_night' => 'nullable|numeric|min:0',
        ]);

        $room = Room::find($this->editingRoomId);
        if (!$room || $room->station_id !== $this->station->id) {
            return;
        }

        $room->update([
            'room_number' => $this->edit_room_number,
            'room_type' => $this->edit_room_type,
            'capacity' => $this->edit_capacity,
            'price_per_night' => $this->edit_price_per_night,
        ]);

        $this->loadRooms();
        $this->closeEditRoomModal();
        \Filament\Notifications\Notification::make()
            ->title('Success')
            ->body('Room updated successfully')
            ->success()
            ->send();
    }

    public function createBed()
    {
        $this->validate(['bed_code' => 'required|string|max:20']);

        if (!$this->selectedRoom) {
            return;
        }

        \App\Models\Bed::create([
            'room_id' => $this->selectedRoom->id,
            'bed_code' => $this->bed_code,
            'status' => 'Available',
        ]);

        $this->selectedRoom = Room::with('beds')->find($this->selectedRoom->id);
        $this->bed_code = '';
        $this->resetErrorBag();
        \Filament\Notifications\Notification::make()
            ->title('Success')
            ->body('Bed created successfully')
            ->success()
            ->send();
    }

    public function confirmDeleteBed($bedId)
    {
        $this->deletingBedId = $bedId;
        $this->showDeleteBedModal = true;
    }

    public function closeDeleteBedModal()
    {
        $this->showDeleteBedModal = false;
        $this->deletingBedId = null;
    }

    public function deleteBed()
    {
        $bed = \App\Models\Bed::find($this->deletingBedId);
        if (!$bed || $bed->room_id !== $this->selectedRoom->id) {
            $this->closeDeleteBedModal();
            return;
        }

        if ($bed->status === 'Occupied') {
            \Filament\Notifications\Notification::make()
                ->title('Error')
                ->body('Cannot delete occupied bed')
                ->danger()
                ->send();
            $this->closeDeleteBedModal();
            return;
        }

        $bed->delete();
        $this->selectedRoom = Room::with('beds')->find($this->selectedRoom->id);
        $this->closeDeleteBedModal();
        \Filament\Notifications\Notification::make()
            ->title('Success')
            ->body('Bed deleted successfully')
            ->success()
            ->send();
    }
}
