@extends('layouts.layout')

@section('content')
<div class="py-4">
    <div class="container max-w-4xl mx-auto px-4">
        <!-- Header Card -->
        <div class="mb-6 card-enterprise p-4 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-slate-800 mb-1">My Attendance</h1>
                <p class="text-sm text-slate-500">View your daily time records and attendance history</p>
            </div>
            <button onclick="dtr_report_modal.showModal()" class="btn-enterprise-secondary inline-flex items-center gap-2 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Print DTR Report
            </button>
        </div>

        <!-- Assigned Schedule Card -->
        @if ($dateSchedules && $dateSchedules->count() > 0)
            <div class="mb-6 card-enterprise border-l-4 border-l-sky-500 p-4">
                <h2 class="text-base font-bold text-slate-800 mb-4">Scheduled Dates This Month</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach ($dateSchedules as $schedule)
                        <div class="bg-slate-50 rounded-lg p-3 border border-slate-200">
                            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">{{ \Carbon\Carbon::parse($schedule->date)->format('l, M d') }}</div>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-sm font-mono font-bold text-emerald-600">{{ $schedule->start_shift }}</span>
                                <span class="text-xs text-slate-400">—</span>
                                <span class="text-sm font-mono font-bold text-red-600">{{ $schedule->end_shift }}</span>
                            </div>
                            @if ($schedule->assignment)
                                <div class="text-xs text-slate-500 mt-1">{{ $schedule->assignment }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="mb-6 card-enterprise border-l-4 border-l-amber-500 p-4">
                <p class="text-sm text-amber-700 font-medium">No date schedules assigned for this month. Contact your Head Nurse.</p>
            </div>
        @endif

        <!-- Month Navigation Card -->
        <div class="mb-6 card-enterprise p-4">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-base font-bold text-slate-800">{{ $monthName }}</h2>
                <!-- Month/Year Picker -->
                <form method="GET" action="{{ route('dtr.my-dtr') }}" class="flex gap-2">
                    <select name="month" class="select-enterprise text-sm">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $m == $currentMonth ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromFormat('m', $m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                    <select name="year" class="select-enterprise text-sm">
                        @for ($y = $currentYear - 2; $y <= $currentYear; $y++)
                            <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                    <button type="submit" class="btn-enterprise-primary text-sm">
                        View
                    </button>
                </form>
            </div>
            <div class="flex flex-wrap gap-2">
                <div class="inline-flex items-center px-3 py-1.5 rounded-lg bg-emerald-50 border border-emerald-200">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></span>
                    <span class="font-semibold text-sm text-emerald-700">On Time</span>
                </div>
                <div class="inline-flex items-center px-3 py-1.5 rounded-lg bg-amber-50 border border-amber-200">
                    <span class="inline-block w-2 h-2 rounded-full bg-amber-500 mr-1.5"></span>
                    <span class="font-semibold text-sm text-amber-700">Late</span>
                </div>
                <div class="inline-flex items-center px-3 py-1.5 rounded-lg bg-red-50 border border-red-200">
                    <span class="inline-block w-2 h-2 rounded-full bg-red-500 mr-1.5"></span>
                    <span class="font-semibold text-sm text-red-700">Incomplete</span>
                </div>
                <div class="inline-flex items-center px-3 py-1.5 rounded-lg bg-sky-50 border border-sky-200">
                    <span class="inline-block w-2 h-2 rounded-full bg-sky-400 mr-1.5"></span>
                    <span class="font-semibold text-sm text-sky-600">Unscheduled</span>
                </div>
            </div>
        </div>

        <!-- Calendar Card -->
        <div class="mb-6 card-enterprise overflow-hidden">
            <!-- Day Headers -->
            <div class="grid grid-cols-7 gap-0 bg-slate-100 border-b border-slate-200">
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                    <div class="p-2 text-center font-semibold text-xs text-slate-600">
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
                        } elseif ($record->status === 'Unscheduled') {
                            $bgColor = 'bg-sky-50';
                            $badge = '<span class="inline-block px-2 py-1 text-xs rounded-md bg-sky-500 text-white font-semibold">Unscheduled</span>';
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
                                <span class="text-blue-600 font-mono font-bold text-xs">{{ $record->formatted_time_in }}</span>
                            </div>
                            @if ($record->time_out)
                                <div>
                                    <span class="font-semibold text-xs text-gray-600">Out:</span>
                                    <span class="text-blue-600 font-mono font-bold text-xs">{{ $record->formatted_time_out }}</span>
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
                                — {{ $record->formatted_time_out }}
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
        $unscheduledDays = collect($dtrMap)->where('status', 'Unscheduled')->count();
    @endphp

    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-3">
        <div class="card-enterprise border-l-4 border-l-sky-500 p-4">
            <div class="text-slate-500 text-xs font-semibold uppercase tracking-wide">Total Records</div>
            <div class="text-3xl font-bold text-sky-600 mt-2">{{ $totalRecords }}</div>
            <div class="mt-1 text-xs text-slate-400">This month</div>
        </div>

        <div class="card-enterprise border-l-4 border-l-emerald-500 p-4">
            <div class="text-slate-500 text-xs font-semibold uppercase tracking-wide">On Time</div>
            <div class="text-3xl font-bold text-emerald-600 mt-2">{{ $presentDays }}</div>
            <div class="mt-1 text-xs text-slate-400">Days present</div>
        </div>

        <div class="card-enterprise border-l-4 border-l-amber-500 p-4">
            <div class="text-slate-500 text-xs font-semibold uppercase tracking-wide">Late Days</div>
            <div class="text-3xl font-bold text-amber-600 mt-2">{{ $lateDays }}</div>
            <div class="mt-1 text-xs text-slate-400">Tardy records</div>
        </div>

        <div class="card-enterprise border-l-4 border-l-violet-500 p-4">
            <div class="text-slate-500 text-xs font-semibold uppercase tracking-wide">Total Hours</div>
            <div class="text-3xl font-bold text-violet-600 mt-2">{{ number_format($totalHours, 1) }}h</div>
            <div class="mt-1 text-xs text-slate-400">Hours worked</div>
        </div>
    </div>

    <!-- Detailed Records Table -->
    @if ($totalRecords > 0)
        <div class="mt-6">
            <h3 class="text-base font-bold text-slate-800 mb-3">Detailed Records</h3>
            <div class="card-enterprise overflow-hidden">
                <table class="table-enterprise">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Total Hours</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dtrMap as $date => $record)
                            <tr>
                                <td class="font-medium">{{ $record->formatted_date }}</td>
                                <td class="font-mono font-semibold">{{ $record->formatted_time_in }}</td>
                                <td class="font-mono font-semibold">
                                    @if ($record->time_out)
                                        {{ $record->formatted_time_out }}
                                    @else
                                        <span class="badge-enterprise bg-amber-50 text-amber-700 border-amber-200">Pending</span>
                                    @endif
                                </td>
                                <td class="font-mono font-semibold">{{ number_format($record->total_hours, 2) }}h</td>
                                <td>
                                    @if ($record->status === 'Present')
                                        <span class="badge-enterprise bg-emerald-50 text-emerald-700 border-emerald-200">✓ Time</span>
                                    @elseif ($record->status === 'Late')
                                        <span class="badge-enterprise bg-amber-50 text-amber-700 border-amber-200">⚠ Late</span>
                                    @elseif ($record->status === 'Incomplete')
                                        <span class="badge-enterprise bg-red-50 text-red-700 border-red-200">✕ Inc</span>
                                    @elseif ($record->status === 'Unscheduled')
                                        <span class="badge-enterprise bg-sky-50 text-sky-700 border-sky-200">○ Unsched</span>
                                    @else
                                        <span class="badge-enterprise bg-slate-50 text-slate-700 border-slate-200">{{ $record->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="mt-6 card-enterprise p-8 text-center">
            <p class="text-sm text-slate-500 font-medium">No attendance records for this month yet.</p>
        </div>
    @endif
</div>

<!-- DTR Report Modal -->
<dialog id="dtr_report_modal" class="modal">
    <div class="modal-enterprise">
        <form method="dialog">
            <button class="absolute right-4 top-4 text-slate-400 hover:text-slate-600 transition-colors">✕</button>
        </form>

        <h3 class="text-lg font-bold text-slate-800 mb-1">Generate DTR Report</h3>
        <p class="text-sm text-slate-500 mb-4">Select date range for your DTR report. This will generate a PDF for payroll.</p>

        <form action="{{ route('dtr.my-dtr-report') }}" method="POST" target="_blank" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Date From</label>
                    <input type="date" name="date_from" required class="input-enterprise w-full" />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Date To</label>
                    <input type="date" name="date_to" required class="input-enterprise w-full" />
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-200">
                <button type="button" onclick="dtr_report_modal.close()" class="btn-enterprise-secondary">Cancel</button>
                <button type="submit" class="btn-enterprise-primary inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Generate PDF
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>
@endsection
