<div>
    <div class="fi-page-header px-6 py-6 mb-6">
        <div class="fi-page-header-top flex items-center justify-between">
            <h1 class="text-3xl font-bold">
                Staff Nurses
            </h1>
            <div class="flex gap-2">
                @foreach ($this->getCachedHeaderActions() as $action)
                    {{ $action }}
                @endforeach
            </div>
        </div>
    </div>

    <div class="fi-page-body">
        <!-- Filter Section -->
        <div class="mb-6 p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Select Station
            </label>
            <select
                wire:change="$dispatch('station-filter-changed', { stationId: $event.target.value })"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <option value="">-- Choose a Station --</option>
                @foreach ($this->stations as $station)
                    <option value="{{ $station['id'] }}" @if($this->selectedStationId == $station['id']) selected @endif>
                        {{ $station['station_name'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Table only shows when station is selected -->
        @if ($this->selectedStationId)
            {{ $this->table }}
        @else
            <div class="p-8 text-center bg-gray-50 rounded-lg">
                <p class="text-gray-500">Please select a Station to view staff nurses</p>
            </div>
        @endif
    </div>
</div>
