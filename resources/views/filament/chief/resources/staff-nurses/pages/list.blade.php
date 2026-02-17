<div>
    <div class="fi-page-header px-4 py-4 mb-3">
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
        <div class="mb-6 p-4 bg-white rounded-lg border border-gray-200 shadow-sm space-y-4">
            <!-- Unit Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Select Unit / Building
                </label>
                <select
                    wire:change="$dispatch('unit-filter-changed', { unitId: $event.target.value })"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">-- Choose a Unit --</option>
                    @foreach ($this->units as $unit)
                        <option value="{{ $unit['id'] }}" @if($this->selectedUnitId == $unit['id']) selected @endif>
                            {{ $unit['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Station Filter (only shows if unit selected) -->
            @if ($this->selectedUnitId)
                <div>
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
            @endif
        </div>

        <!-- Header Info (shows when station selected) -->
        @if ($this->selectedStationId && $this->stationInfo)
            <div class="mb-4 p-4 bg-green-50 rounded-lg border border-green-200 grid grid-cols-3 gap-4">
                @php
                    $selectedUnit = collect($this->units)->firstWhere('id', $this->selectedUnitId);
                @endphp

                <div>
                    <p class="text-xs text-gray-600">Unit / Building</p>
                    <p class="font-semibold text-green-900">{{ $selectedUnit['name'] ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-600">Supervisor</p>
                    <p class="font-semibold text-green-900">
                        @if ($this->supervisor)
                            {{ $this->supervisor->first_name }} {{ $this->supervisor->last_name }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-600">Station / Department</p>
                    <p class="font-semibold text-green-900">
                        {{ $this->stationInfo['name'] ?? 'N/A' }}
                        @if ($this->stationInfo['head_nurse'])
                            <span class="text-xs text-green-700 block">
                                Head: {{ $this->stationInfo['head_nurse']->first_name }} {{ $this->stationInfo['head_nurse']->last_name }}
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        @endif

        <!-- Table only shows when station is selected -->
        @if ($this->selectedStationId)
            {{ $this->table }}
        @else
            @if (!$this->selectedUnitId)
                <div class="p-8 text-center bg-gray-50 rounded-lg">
                    <p class="text-gray-500">Please select a Unit first</p>
                </div>
            @else
                <div class="p-8 text-center bg-gray-50 rounded-lg">
                    <p class="text-gray-500">Please select a Station to view staff nurses</p>
                </div>
            @endif
        @endif
    </div>
</div>
