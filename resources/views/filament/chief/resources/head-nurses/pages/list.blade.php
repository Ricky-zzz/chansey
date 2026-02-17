<div>
    <div class="fi-page-header px-4 py-4 mb-3">
        <div class="fi-page-header-top flex items-center justify-between">
            <h1 class="text-3xl font-bold">
                Head Nurses
            </h1>
            <div class="flex gap-2">
                @foreach ($this->getCachedHeaderActions() as $action)
                    {{ $action }}
                @endforeach
            </div>
        </div>
    </div>

    <div class="fi-page-body">
        <!-- Unit Filter -->
        <div class="mb-6 p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
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

        <!-- Table Header Info -->
        @if ($this->selectedUnitId)
            <div class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                @php
                    $selectedUnit = collect($this->units)->firstWhere('id', $this->selectedUnitId);
                @endphp
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-600">Unit / Building</p>
                        <p class="font-semibold text-blue-900">{{ $selectedUnit['name'] ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">Unit Supervisor</p>
                        <p class="font-semibold text-blue-900">
                            @if ($this->supervisor)
                                {{ $this->supervisor->first_name }} {{ $this->supervisor->last_name }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{ $this->table }}
        @else
            <div class="p-8 text-center bg-gray-50 rounded-lg">
                <p class="text-gray-500">Please select a Unit to view Head Nurses</p>
            </div>
        @endif
    </div>
</div>
