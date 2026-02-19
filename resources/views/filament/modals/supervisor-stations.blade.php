<div class="space-y-4">
    <div class="bg-blue-50 dark:bg-blue-950 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
        <h3 class="font-semibold text-blue-900 dark:text-blue-100">{{ $supervisor->first_name }} {{ $supervisor->last_name }}</h3>
        <p class="text-sm text-blue-700 dark:text-blue-300">Supervisor - {{ $supervisor->unit->name ?? 'N/A' }}</p>
    </div>

    <div>
        <h4 class="font-medium text-gray-900 dark:text-white mb-3">Stations Under This Unit</h4>

        @forelse($stations as $station)
            <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $station->station_name }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $station->station_code ?? 'N/A' }} | Floor: {{ $station->floor_location ?? 'N/A' }}</p>
                </div>
                <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900 px-3 py-1 text-sm font-medium text-green-800 dark:text-green-200">
                    {{ $station->nurses->count() ?? 0 }} staff
                </span>
            </div>
        @empty
            <div class="text-center py-8">
                <p class="text-gray-500 dark:text-gray-400">No stations assigned to this unit yet.</p>
            </div>
        @endforelse
    </div>
</div>
