@extends('layouts.physician')

@section('content')
<div class="max-w-5xl mx-auto">

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
                                <span class="badge badge-neutral">Done</span>
                            @elseif($app->admission_id)
                                <!-- If we found a matching admission, they are HERE -->
                                <span class="badge badge-success text-white animate-pulse">Admitted</span>
                            @else
                                <span class="badge badge-ghost">Waiting / Approved</span>
                            @endif
                        </td>

                        <!-- ACTION -->
                        <td class="text-right">

                            @if($app->status === 'Completed')
                                <span class="text-xs text-gray-400">Completed</span>

                            @elseif($app->admission_id)
                                <!-- LINK TO CHART -->
                                <a href="{{ route('physician.patients.show', $app->admission_id) }}"
                                   class="btn btn-sm btn-outline btn-primary gap-2">
                                    Open Chart
                                </a>

                            @else
                                <!-- MARK DONE (For Outpatients without Admission) -->
                                <form action="{{ route('physician.appointments.complete', $app->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-ghost text-success">
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
