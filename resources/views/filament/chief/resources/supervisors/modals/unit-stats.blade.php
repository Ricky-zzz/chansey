<div class="space-y-4">
    <!-- Unit Information -->
    <div class="p-4 bg-sky-50 rounded-lg border border-sky-200">
        <p class="text-sm text-gray-600">Unit / Building</p>
        <p class="text-lg font-bold text-sky-900">{{ $supervisor->unit?->name ?? 'N/A' }}</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 gap-4">
        <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
            <p class="text-xs text-gray-600 mb-1">Total Stations</p>
            <p class="text-3xl font-bold text-blue-600">{{ $stationCount }}</p>
        </div>
        <div class="p-4 bg-green-50 rounded-lg border border-green-200">
            <p class="text-xs text-gray-600 mb-1">Total Nurses</p>
            <p class="text-3xl font-bold text-green-600">{{ $nurseCount }}</p>
        </div>
    </div>

    <!-- Stations Table -->
    @if ($stations->count())
        <div class="border rounded-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Station</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Nurses</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stations as $station)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2 text-sm">{{ $station->station_name }}</td>
                            <td class="px-4 py-2 text-sm">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $station->nurses->count() }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-4 text-center bg-gray-50 rounded-lg">
            <p class="text-gray-500">No stations assigned</p>
        </div>
    @endif
</div>
