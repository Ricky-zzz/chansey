<div>
    <div class="fi-page-header px-4 py-4 mb-3">
        <div class="fi-page-header-top flex items-center justify-between">
            <h1 class="text-3xl font-bold">
                Supervisors
            </h1>
            <div class="flex gap-2">
                @foreach ($this->getCachedHeaderActions() as $action)
                    {{ $action }}
                @endforeach
            </div>
        </div>
    </div>

    <div class="fi-page-body">
        @if ($this->selectedSupervisorId && $this->supervisorStats)
            <!-- Stats Display -->
            <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h3 class="text-lg font-semibold text-blue-900">
                    {{ $this->supervisorStats['name'] }}
                </h3>
                <p class="text-sm text-blue-700 mt-2">
                    Unit: <strong>{{ $this->supervisorStats['unit_name'] }}</strong>
                </p>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div class="bg-white p-3 rounded">
                        <p class="text-xs text-gray-500">Stations</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $this->supervisorStats['station_count'] }}</p>
                    </div>
                    <div class="bg-white p-3 rounded">
                        <p class="text-xs text-gray-500">Total Nurses</p>
                        <p class="text-2xl font-bold text-green-600">{{ $this->supervisorStats['nurse_count'] }}</p>
                    </div>
                </div>
                @if ($this->supervisorStats['stations']->count())
                    <div class="mt-4 border-t border-blue-200 pt-4">
                        <h4 class="font-semibold text-blue-900 mb-2">Stations Under This Unit:</h4>
                        <div class="space-y-2">
                            @foreach ($this->supervisorStats['stations'] as $station)
                                <div class="flex justify-between items-center bg-white p-2 rounded border border-blue-100">
                                    <span class="text-sm font-medium">{{ $station->station_name }}</span>
                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">
                                        {{ $station->nurses->count() }} nurse(s)
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{ $this->table }}
    </div>
</div>
