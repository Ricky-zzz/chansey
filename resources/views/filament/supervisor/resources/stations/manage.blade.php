@php
    use Filament\Support\Facades\FilamentIcon;
@endphp

<div class="fi-page" wire:key="manage-station-{{ $station->id }}">
    <!-- Breadcrumb -->
    <div class="px-6 py-4">
        <div class="flex items-center space-x-2 text-sm">
            <a href="{{ route('filament.supervisor.resources.stations.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                Stations
            </a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-900 font-medium">{{ $station->station_name }}</span>
        </div>
    </div>

    <!-- Page Header -->
    <div class="fi-page-header px-6 py-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $station->station_name }}</h1>
                <p class="text-gray-500 text-sm mt-1">Manage station details and resources</p>
            </div>
            <a href="{{ route('filament.supervisor.resources.stations.index') }}" class="px-4 py-2 text-gray-700 hover:text-gray-900">
                ← Back
            </a>
        </div>
    </div>

    <!-- Station Header Info -->
    <div class="fi-page-body px-6">
        <div class="grid grid-cols-3 gap-4 p-4 bg-blue-50 rounded-lg border border-blue-200 mb-6">
            <div>
                <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide">Station Code</p>
                <p class="text-lg font-semibold text-blue-900 mt-1">{{ $station->station_code }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide">Floor Location</p>
                <p class="text-lg font-semibold text-blue-900 mt-1">{{ $station->floor_location }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide">Station Head</p>
                <p class="text-lg font-semibold text-blue-900 mt-1">
                    @if($stationHead)
                        {{ $stationHead->first_name }} {{ $stationHead->last_name }}
                    @else
                        <span class="text-gray-400">Not Assigned</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Rooms Management Section -->
        @if(!$showManageBedsModal)
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Rooms</h2>
                <button type="button" wire:click="openCreateRoomModal" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition">
                    + Create Room
                </button>
            </div>

            @if($rooms->count())
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Room</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Type</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Capacity</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Beds</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Price/Night</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($rooms as $room)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $room->room_number }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-600">{{ $room->room_type }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-600">{{ $room->capacity }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-600"><strong>{{ $room->beds->count() }}</strong></td>
                                    <td class="px-6 py-3 text-sm text-gray-600">₱{{ number_format($room->price_per_night ?? 0, 2) }}</td>
                                    <td class="px-6 py-3 text-sm space-x-2">
                                        <button wire:click="openManageBedsModal({{ $room->id }})" type="button" class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 transition text-xs font-medium">
                                            Manage Beds
                                        </button>
                                        <button wire:click="openEditRoomModal({{ $room->id }})" type="button" class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition text-xs font-medium">
                                            Edit
                                        </button>
                                        <button wire:click="confirmDeleteRoom({{ $room->id }})" type="button" class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition text-xs font-medium">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <div class="text-gray-400 text-sm">
                        <p class="font-medium">No rooms yet</p>
                        <p class="text-xs">Create your first room to manage beds and assignments</p>
                    </div>
                </div>
            @endif
        </div>
        @endif

        <!-- Manage Beds View (Full Layer) -->
        @if($showManageBedsModal && $selectedRoom)
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <!-- Breadcrumb -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                <div class="flex items-center space-x-3">
                    <button wire:click="closeManageBedsModal" class="px-3 py-1 text-gray-600 hover:text-gray-900 text-sm">← Back to Rooms</button>
                    <span class="text-gray-400">|</span>
                    <h2 class="text-lg font-semibold text-gray-900">Manage Beds - Room {{ $selectedRoom->room_number }}</h2>
                </div>
            </div>

            <!-- Room Info Header -->
            <div class="px-6 py-4 bg-blue-50 border-b border-gray-200">
                <div class="grid grid-cols-4 gap-4">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide">Room Number</p>
                        <p class="text-lg font-semibold text-blue-900 mt-1">{{ $selectedRoom->room_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide">Room Type</p>
                        <p class="text-lg font-semibold text-blue-900 mt-1">{{ $selectedRoom->room_type }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide">Capacity</p>
                        <p class="text-lg font-semibold text-blue-900 mt-1">{{ $selectedRoom->capacity }} Beds</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide">Price/Night</p>
                        <p class="text-lg font-semibold text-blue-900 mt-1">₱{{ number_format($selectedRoom->price_per_night ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Beds Management Content -->
            <div class="px-6 py-6">
                <div class="space-y-6">
                    <!-- Beds Table -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Beds ({{ $selectedRoom->beds->count() }}/{{ $selectedRoom->capacity }})</h3>
                            <form wire:submit.prevent="createBed" class="flex gap-2">
                                <input type="text" wire:model="bed_code" placeholder="e.g. B101-1"
                                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm">+ Add Bed</button>
                            </form>
                        </div>
                        @error('bed_code') <span class="text-red-600 text-sm block mb-3">{{ $message }}</span> @enderror

                        @if($selectedRoom->beds->count())
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                                @foreach($selectedRoom->beds as $bed)
                                    <div class="p-3 border rounded-lg @if($bed->status === 'Occupied') bg-red-50 border-red-300 @else bg-green-50 border-green-300 @endif">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-semibold text-gray-900">{{ $bed->bed_code }}</span>
                                            @if($bed->status !== 'Occupied')
                                                <button wire:click="confirmDeleteBed({{ $bed->id }})" type="button" class="text-red-600 hover:text-red-900 text-sm">✕</button>
                                            @endif
                                        </div>
                                        <span class="inline-block px-2 py-1 rounded text-xs font-medium @if($bed->status === 'Occupied') bg-red-100 text-red-700 @else bg-green-100 text-green-700 @endif">
                                            {{ $bed->status ?? 'Available' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="px-6 py-12 text-center bg-gray-50 rounded-lg">
                                <p class="text-gray-500 text-sm">No beds created yet. Add the first bed above.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>

    <!-- Create Room Modal -->
    @if($showCreateRoomModal)
        <div class="fixed inset-0 bg-black/20 z-40" wire:click="closeCreateRoomModal"></div>
        <div class="fixed inset-0 z-50 overflow-y-auto pointer-events-none flex items-center justify-center" wire:key="create-room-modal">
            <div class="pointer-events-auto inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full mx-4">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Create New Room</h3>
                    <form wire:submit.prevent="createRoom" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Room Number</label>
                            <input type="text" wire:model.live="room_number" placeholder="e.g. 101, 102A"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @error('room_number') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Room Type</label>
                            <select wire:model.live="room_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Type</option>
                                <option value="Single">Single</option>
                                <option value="Double">Double</option>
                                <option value="Ward">Ward</option>
                            </select>
                            @error('room_type') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Capacity (# of Beds)</label>
                            <input type="number" wire:model.live="capacity" placeholder="e.g. 1, 2, 4" min="1"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @error('capacity') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price per Night (₱)</label>
                            <input type="number" wire:model.live="price_per_night" placeholder="e.g. 2500.00" step="0.01" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @error('price_per_night') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex gap-3 mt-6">
                            <button type="button" wire:click="closeCreateRoomModal" class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 font-medium">Cancel</button>
                            <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">Create Room</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Room Modal -->
    @if($showEditRoomModal)
        <div class="fixed inset-0 bg-black/20 z-40" wire:click="closeEditRoomModal"></div>
        <div class="fixed inset-0 z-50 overflow-y-auto pointer-events-none flex items-center justify-center" wire:key="edit-room-modal">
            <div class="pointer-events-auto inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full mx-4">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Edit Room</h3>
                    <form wire:submit.prevent="updateRoom" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Room Number</label>
                            <input type="text" wire:model.live="edit_room_number" placeholder="e.g. 101, 102A"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @error('edit_room_number') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Room Type</label>
                            <select wire:model.live="edit_room_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Type</option>
                                <option value="Single">Single</option>
                                <option value="Double">Double</option>
                                <option value="Ward">Ward</option>
                            </select>
                            @error('edit_room_type') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Capacity (# of Beds)</label>
                            <input type="number" wire:model.live="edit_capacity" placeholder="e.g. 1, 2, 4" min="1"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @error('edit_capacity') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price per Night (₱)</label>
                            <input type="number" wire:model.live="edit_price_per_night" placeholder="e.g. 2500.00" step="0.01" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @error('edit_price_per_night') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex gap-3 mt-6">
                            <button type="button" wire:click="closeEditRoomModal" class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 font-medium">Cancel</button>
                            <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">Update Room</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Room Confirmation Modal -->
    @if($showDeleteRoomModal)
        <div class="fixed inset-0 bg-black/20 z-40" wire:click="closeDeleteRoomModal"></div>
        <div class="fixed inset-0 z-50 overflow-y-auto pointer-events-none flex items-center justify-center" wire:key="delete-room-modal">
            <div class="pointer-events-auto inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-md sm:w-full mx-4">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Room</h3>
                            <p class="text-sm text-gray-500 mt-1">Are you sure you want to delete this room? This action cannot be undone.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 mt-6">
                        <button type="button" wire:click="closeDeleteRoomModal" class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 font-medium">Cancel</button>
                        <button type="button" wire:click="deleteRoom" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Bed Confirmation Modal -->
    @if($showDeleteBedModal)
        <div class="fixed inset-0 bg-black/20 z-40" wire:click="closeDeleteBedModal"></div>
        <div class="fixed inset-0 z-50 overflow-y-auto pointer-events-none flex items-center justify-center" wire:key="delete-bed-modal">
            <div class="pointer-events-auto inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-md sm:w-full mx-4">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Bed</h3>
                            <p class="text-sm text-gray-500 mt-1">Are you sure you want to delete this bed? This action cannot be undone.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 mt-6">
                        <button type="button" wire:click="closeDeleteBedModal" class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 font-medium">Cancel</button>
                        <button type="button" wire:click="deleteBed" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
