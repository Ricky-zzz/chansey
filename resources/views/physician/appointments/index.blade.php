@extends('layouts.physician')

@section('content')
<div class="max-w-7xl mx-auto bg-white rounded-lg p-6 shadow-xl border border-slate-200">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-black text-slate-800">Daily Consultations</h2>
            <p class="text-sm text-gray-500">{{ now()->format('l, F d, Y') }}</p>
        </div>
    </div>

    <div class="card bg-white shadow-sm border border-slate-200">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead class="bg-slate-800 text-white uppercase text-xs">
                    <tr>
                        <th class="w-24">Time</th>
                        <th>Patient Name</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $app)
                    <tr class="hover">
                        <!-- TIME -->
                        <td class="font-mono font-bold text-primary">
                            {{ $app->formatted_start_time ?? 'N/A' }}
                        </td>

                        <!-- NAME -->
                        <td>
                            <div class="font-bold">{{ $app->last_name }}, {{ $app->first_name }}</div>
                            <div class="text-xs text-gray-400">{{ $app->contact_number }}</div>
                        </td>

                        <!-- REASON -->
                        <td class="max-w-xs truncate text-sm italic text-gray-600">
                            "{{ $app->purpose }}"
                        </td>

                        <!-- STATUS -->
                        <td>
                            @if($app->status === 'Completed')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold text-gray-600 bg-gray-100 border border-gray-200">Done</span>
                            @elseif($app->admission_id)
                                <!-- If we found a matching admission, they are HERE -->
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold text-white bg-emerald-600 border border-emerald-600 animate-pulse">Active Consultation</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold text-gray-600 bg-gray-50 border border-orange-200">Waiting</span>
                            @endif
                        </td>

                        <!-- ACTION -->
                        <td class="text-right">

                            @if($app->status === 'Completed')
                                <span class="text-xs text-gray-400">Completed</span>

                            @elseif($app->admission_id)
                                <!-- LINK TO CHART -->
                                <a href="{{ route('physician.mypatients.show', $app->admission_id) }}"
                                   class="px-3 py-1.5 text-sm bg-blue-600 text-white hover:bg-blue-700 rounded-lg transition-colors inline-flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                    Open Chart
                                </a>

                            @else
                                <!-- MARK DONE (For Outpatients without Admission) -->
                                <form action="{{ route('physician.appointments.complete', $app->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="px-3 py-1.5 text-sm bg-emerald-600 text-white hover:bg-emerald-700 rounded-lg transition-colors inline-flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        Mark Done
                                    </button>
                                </form>
                            @endif

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-400 italic">
                            No appointments scheduled for today.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
