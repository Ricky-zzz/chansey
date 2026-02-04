@extends('layouts.app')

@section('content')
<div class="container max-w-4xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-2">My Attendance</h1>
        <p class="text-gray-600 dark:text-gray-400">View your daily time records and attendance history</p>
    </div>

    <!-- Month Navigation -->
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $monthName }}</h2>
        <div class="text-sm text-gray-600 dark:text-gray-400">
            <span class="inline-block px-3 py-1 rounded bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 mr-2">
                <span class="font-bold">On Time</span>
            </span>
            <span class="inline-block px-3 py-1 rounded bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 mr-2">
                <span class="font-bold">Late</span>
            </span>
            <span class="inline-block px-3 py-1 rounded bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 mr-2">
                <span class="font-bold">Absent</span>
            </span>
            <span class="inline-block px-3 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                <span class="font-bold">Rest Day</span>
            </span>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <!-- Day Headers -->
        <div class="grid grid-cols-7 gap-0 border-b border-gray-200 dark:border-gray-700">
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="p-4 bg-gray-50 dark:bg-gray-900 text-center font-bold text-gray-700 dark:text-gray-300">
                    {{ $day }}
                </div>
            @endforeach
        </div>

        <!-- Calendar Days -->
        <div class="grid grid-cols-7 gap-0">
            {{-- Empty cells before first day --}}
            @for ($i = 0; $i < $startingDayOfWeek; $i++)
                <div class="p-4 min-h-28 bg-gray-50 dark:bg-gray-700"></div>
            @endfor

            {{-- Days of month --}}
            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $date = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
                    $record = $dtrMap[$date] ?? null;

                    // Determine status color
                    $bgColor = 'bg-white dark:bg-gray-800';
                    $badge = '';

                    if ($record) {
                        if ($record->status === 'Present') {
                            $bgColor = 'bg-green-50 dark:bg-green-900/20';
                            $badge = '<span class="inline-block px-2 py-1 text-xs rounded bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200 font-bold">On Time</span>';
                        } elseif ($record->status === 'Late') {
                            $bgColor = 'bg-yellow-50 dark:bg-yellow-900/20';
                            $badge = '<span class="inline-block px-2 py-1 text-xs rounded bg-yellow-200 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-200 font-bold">Late</span>';
                        } elseif ($record->status === 'Incomplete') {
                            $bgColor = 'bg-red-50 dark:bg-red-900/20';
                            $badge = '<span class="inline-block px-2 py-1 text-xs rounded bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200 font-bold">Incomplete</span>';
                        }
                    }
                @endphp

                <div class="p-4 min-h-28 border border-gray-200 dark:border-gray-700 {{ $bgColor }} hover:shadow-md transition-shadow cursor-pointer group relative">
                    <!-- Date Number -->
                    <div class="text-lg font-bold text-gray-800 dark:text-white mb-2">{{ $day }}</div>

                    @if ($record)
                        <!-- Status Badge -->
                        <div class="mb-2">
                            {!! $badge !!}
                        </div>

                        <!-- Time Details -->
                        <div class="text-xs text-gray-700 dark:text-gray-300 space-y-1">
                            <div>
                                <span class="font-semibold">In:</span>
                                <span class="text-primary font-mono">{{ $record->time_in->format('H:i') }}</span>
                            </div>
                            @if ($record->time_out)
                                <div>
                                    <span class="font-semibold">Out:</span>
                                    <span class="text-primary font-mono">{{ $record->time_out->format('H:i') }}</span>
                                </div>
                                <div>
                                    <span class="font-semibold">Hours:</span>
                                    <span class="text-primary font-mono">{{ number_format($record->total_hours, 2) }}h</span>
                                </div>
                            @else
                                <div class="text-orange-600 dark:text-orange-400 font-semibold">Pending Time Out</div>
                            @endif
                        </div>
                    @else
                        <div class="text-xs text-gray-400 dark:text-gray-600">No record</div>
                    @endif

                    <!-- Tooltip on Hover -->
                    @if ($record)
                        <div class="absolute hidden group-hover:block bottom-full right-0 mb-2 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded p-2 whitespace-nowrap z-10 border border-gray-700">
                            {{ $record->time_in->format('M d, Y H:i') }}
                            @if ($record->time_out)
                                — {{ $record->time_out->format('H:i') }}
                            @endif
                        </div>
                    @endif
                </div>
            @endfor
        </div>
    </div>

    <!-- Summary Statistics -->
    @php
        $totalRecords = count($dtrMap);
        $totalHours = collect($dtrMap)->sum('total_hours');
        $presentDays = collect($dtrMap)->where('status', 'Present')->count();
        $lateDays = collect($dtrMap)->where('status', 'Late')->count();
    @endphp

    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="text-gray-600 dark:text-gray-400 text-sm font-semibold uppercase">Total Records</div>
            <div class="text-3xl font-bold text-gray-800 dark:text-white mt-2">{{ $totalRecords }}</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="text-gray-600 dark:text-gray-400 text-sm font-semibold uppercase">On Time</div>
            <div class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">{{ $presentDays }}</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="text-gray-600 dark:text-gray-400 text-sm font-semibold uppercase">Late Days</div>
            <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-2">{{ $lateDays }}</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="text-gray-600 dark:text-gray-400 text-sm font-semibold uppercase">Total Hours</div>
            <div class="text-3xl font-bold text-purple-600 dark:text-purple-400 mt-2">{{ number_format($totalHours, 1) }}h</div>
        </div>
    </div>

    <!-- Detailed Records Table -->
    @if ($totalRecords > 0)
        <div class="mt-8">
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Detailed Records</h3>
            <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left font-bold text-gray-800 dark:text-white">Date</th>
                            <th class="px-6 py-3 text-left font-bold text-gray-800 dark:text-white">Time In</th>
                            <th class="px-6 py-3 text-left font-bold text-gray-800 dark:text-white">Time Out</th>
                            <th class="px-6 py-3 text-left font-bold text-gray-800 dark:text-white">Total Hours</th>
                            <th class="px-6 py-3 text-left font-bold text-gray-800 dark:text-white">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dtrMap as $date => $record)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-300 font-mono">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-300 font-mono">{{ $record->time_in->format('H:i') }}</td>
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-300 font-mono">
                                    @if ($record->time_out)
                                        {{ $record->time_out->format('H:i') }}
                                    @else
                                        <span class="text-orange-600 dark:text-orange-400 font-semibold">Pending</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-800 dark:text-gray-300 font-mono font-bold">{{ number_format($record->total_hours, 2) }}h</td>
                                <td class="px-6 py-4">
                                    @if ($record->status === 'Present')
                                        <span class="inline-block px-3 py-1 text-xs rounded-full bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200 font-bold">On Time</span>
                                    @elseif ($record->status === 'Late')
                                        <span class="inline-block px-3 py-1 text-xs rounded-full bg-yellow-200 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-200 font-bold">Late</span>
                                    @elseif ($record->status === 'Incomplete')
                                        <span class="inline-block px-3 py-1 text-xs rounded-full bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200 font-bold">Incomplete</span>
                                    @else
                                        <span class="inline-block px-3 py-1 text-xs rounded-full bg-blue-200 dark:bg-blue-800 text-blue-800 dark:text-blue-200 font-bold">{{ $record->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="mt-8 text-center p-8 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <p class="text-gray-600 dark:text-gray-400">No attendance records for this month yet.</p>
        </div>
    @endif
</div>
@endsection
