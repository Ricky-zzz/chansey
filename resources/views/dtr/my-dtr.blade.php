@extends('layouts.layout')

@section('content')
<div class="py-4">
    <div class="container max-w-4xl mx-auto px-4">
        <!-- Header Card -->
        <div class="mb-6 bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900 mb-1">My Attendance</h1>
            <p class="text-sm text-gray-600">View your daily time records and attendance history</p>
        </div>

        <!-- Month Navigation Card -->
        <div class="mb-6 bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-bold text-gray-900">{{ $monthName }}</h2>
                <!-- Month/Year Picker -->
                <form method="GET" action="{{ route('dtr.my-dtr') }}" class="flex gap-2">
                    <select name="month" class="px-3 py-1.5 rounded-lg border border-gray-300 text-sm font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $m == $currentMonth ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromFormat('m', $m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                    <select name="year" class="px-3 py-1.5 rounded-lg border border-gray-300 text-sm font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @for ($y = $currentYear - 2; $y <= $currentYear; $y++)
                            <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                    <button type="submit" class="px-4 py-1.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors">
                        View
                    </button>
                </form>
            </div>
            <div class="flex flex-wrap gap-2">
                <div class="inline-flex items-center px-3 py-1.5 rounded-lg bg-green-100 border border-green-300">
                    <span class="inline-block w-2 h-2 rounded-full bg-green-500 mr-1.5"></span>
                    <span class="font-semibold text-sm text-green-800">On Time</span>
                </div>
                <div class="inline-flex items-center px-3 py-1.5 rounded-lg bg-yellow-100 border border-yellow-300">
                    <span class="inline-block w-2 h-2 rounded-full bg-yellow-500 mr-1.5"></span>
                    <span class="font-semibold text-sm text-yellow-800">Late</span>
                </div>
                <div class="inline-flex items-center px-3 py-1.5 rounded-lg bg-red-100 border border-red-300">
                    <span class="inline-block w-2 h-2 rounded-full bg-red-500 mr-1.5"></span>
                    <span class="font-semibold text-sm text-red-800">Incomplete</span>
                </div>
                <div class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-100 border border-gray-300">
                    <span class="inline-block w-2 h-2 rounded-full bg-gray-500 mr-1.5"></span>
                    <span class="font-semibold text-sm text-gray-800">Rest Day</span>
                </div>
            </div>
        </div>

        <!-- Calendar Card -->
        <div class="mb-6 bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
            <!-- Day Headers -->
            <div class="grid grid-cols-7 gap-0 bg-slate-300 border-b border-black-200">
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                    <div class="p-2 text-center font-bold text-xs text-gray-700">
                        {{ $day }}
                    </div>
                @endforeach
            </div>

            <!-- Calendar Days -->
            <div class="grid grid-cols-7 gap-0 bg-white">
            {{-- Empty cells before first day --}}
            @for ($i = 0; $i < $startingDayOfWeek; $i++)
                <div class="p-2 min-h-24 bg-gray-50 border border-gray-100"></div>
            @endfor

            {{-- Days of month --}}
            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $date = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
                    $record = $dtrMap[$date] ?? null;

                    // Determine status color
                    $bgColor = 'bg-white';
                    $badge = '';

                    if ($record) {
                        if ($record->status === 'Present') {
                            $bgColor = 'bg-green-50';
                            $badge = '<span class="inline-block px-2 py-1 text-xs rounded-md bg-green-600 text-white font-semibold">On Time</span>';
                        } elseif ($record->status === 'Late') {
                            $bgColor = 'bg-yellow-50';
                            $badge = '<span class="inline-block px-2 py-1 text-xs rounded-md bg-yellow-600 text-white font-semibold">Late</span>';
                        } elseif ($record->status === 'Incomplete') {
                            $bgColor = 'bg-red-50';
                            $badge = '<span class="inline-block px-2 py-1 text-xs rounded-md bg-red-600 text-white font-semibold">Incomplete</span>';
                        }
                    }
                @endphp

                <div class="p-2 min-h-24 border border-gray-100 {{ $bgColor }} hover:shadow-md transition-shadow duration-200 cursor-pointer group relative">
                    <!-- Date Number -->
                    <div class="text-lg font-bold text-gray-900 mb-1">{{ $day }}</div>

                    @if ($record)
                        <!-- Status Badge -->
                        <div class="mb-2">
                            {!! $badge !!}
                        </div>

                        <!-- Time Details -->
                        <div class="text-xs text-gray-700 space-y-0.5">
                            <div>
                                <span class="font-semibold text-xs text-gray-600">In:</span>
                                <span class="text-blue-600 font-mono font-bold text-xs">{{ $record->time_in->format('H:i') }}</span>
                            </div>
                            @if ($record->time_out)
                                <div>
                                    <span class="font-semibold text-xs text-gray-600">Out:</span>
                                    <span class="text-blue-600 font-mono font-bold text-xs">{{ $record->time_out->format('H:i') }}</span>
                                </div>
                                <div>
                                    <span class="font-semibold text-xs text-gray-600">Hrs:</span>
                                    <span class="text-emerald-600 font-mono font-bold text-xs">{{ number_format($record->total_hours, 2) }}h</span>
                                </div>
                            @else
                                <div class="text-orange-600 font-semibold text-xs">Pending Time Out</div>
                            @endif
                        </div>
                    @else
                        <div class="text-xs text-gray-400 font-medium">No record</div>
                    @endif

                    <!-- Tooltip on Hover -->
                    @if ($record)
                        <div class="absolute hidden group-hover:block bottom-full right-0 mb-2 bg-gray-900 text-white text-xs rounded-lg p-3 whitespace-nowrap z-10 border border-gray-700 shadow-lg">
                            {{ $record->formatted_date_time }}
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

    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-3">
        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500 border  hover:shadow-md transition-shadow">
            <div class="text-gray-600 text-xs font-bold uppercase tracking-wide">Total Records</div>
            <div class="text-3xl font-bold text-blue-600 mt-2">{{ $totalRecords }}</div>
            <div class="mt-1 text-xs text-gray-500">This month</div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-green-500 border  hover:shadow-md transition-shadow">
            <div class="text-gray-600 text-xs font-bold uppercase tracking-wide">On Time</div>
            <div class="text-3xl font-bold text-green-600 mt-2">{{ $presentDays }}</div>
            <div class="mt-1 text-xs text-gray-500">Days present</div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-yellow-500 border  hover:shadow-md transition-shadow">
            <div class="text-gray-600 text-xs font-bold uppercase tracking-wide">Late Days</div>
            <div class="text-3xl font-bold text-yellow-600 mt-2">{{ $lateDays }}</div>
            <div class="mt-1 text-xs text-gray-500">Tardy records</div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-purple-500 border  hover:shadow-md transition-shadow">
            <div class="text-gray-600 text-xs font-bold uppercase tracking-wide">Total Hours</div>
            <div class="text-3xl font-bold text-purple-600 mt-2">{{ number_format($totalHours, 1) }}h</div>
            <div class="mt-1 text-xs text-gray-500">Hours worked</div>
        </div>
    </div>

    <!-- Detailed Records Table -->
    @if ($totalRecords > 0)
        <div class="mt-6">
            <h3 class="text-lg font-bold text-gray-900 mb-3">Detailed Records</h3>
            <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
                <table class="w-full text-sm">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Time In</th>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Time Out</th>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Total Hours</th>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($dtrMap as $date => $record)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-2 text-xs text-gray-800 font-medium">{{ $record->formatted_date }}</td>
                                <td class="px-4 py-2 text-xs text-gray-800 font-mono font-bold ">{{ $record->time_in->format('H:i') }}</td>
                                <td class="px-4 py-2 text-xs text-gray-800 font-mono font-bold ">
                                    @if ($record->time_out)
                                        {{ $record->time_out->format('H:i') }}
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-orange-100 text-orange-700">Pending</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-xs text-gray-800 font-mono font-bold ">{{ number_format($record->total_hours, 2) }}h</td>
                                <td class="px-4 py-2">
                                    @if ($record->status === 'Present')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">✓ Time</span>
                                    @elseif ($record->status === 'Late')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">⚠ Late</span>
                                    @elseif ($record->status === 'Incomplete')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">✕ Inc</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700">{{ $record->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="mt-6 bg-white rounded-lg shadow-sm p-8 text-center border border-gray-200">
            <p class="text-sm text-gray-600 font-medium">No attendance records for this month yet.</p>
        </div>
    @endif
</div>
@endsection
