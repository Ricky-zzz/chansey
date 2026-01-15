@extends('layouts.physician')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="bg-white border border-base-300 rounded-xl p-6 shadow">
        
        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-800">My Patient List</h2>
                <div class="badge badge-primary badge-outline mt-1">Active Rounds</div>
            </div>

            <!-- SEARCH -->
            <form action="{{ route('physician.mypatients.index') }}" method="GET" class="join w-full md:w-96">
                <input type="text" name="search" class="input input-bordered join-item w-full" placeholder="Search Patient..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary join-item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </button>
            </form>
        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead class="bg-slate-800 text-white">
                    <tr>
                        <th>Location</th> <!-- Critical for rounds -->
                        <th>Patient</th>
                        <th>Type</th>
                        <th>Case Details</th>
                        <th>Admission Date</th>
                        <th class="text-right">Chart</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($myPatients as $admission)
                    <tr class="hover">
                        <!-- Location -->
                        <td>
                            <div class="font-bold text-slate-700">
                                {{ $admission->station->station_name ?? 'Floating' }}
                            </div>
                            <div class="badge badge-neutral font-mono text-xs">
                                {{ $admission->bed->bed_code ?? 'Outpatient' }}
                            </div>
                        </td>

                        <!-- Patient -->
                        <td>
                            <div class="font-bold text-lg text-primary">
                                {{ $admission->patient->last_name }}, {{ $admission->patient->first_name }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $admission->patient->sex }} • {{ $admission->patient->age }} yo
                            </div>
                        </td>

                        <!-- Type -->
                        <td>
                            @php
                                $typeColors = [
                                    'Inpatient' => 'badge-primary',
                                    'Outpatient' => 'badge-success',
                                    'Emergency' => 'badge-error',
                                ];
                                $badgeClass = $typeColors[$admission->admission_type] ?? 'badge-ghost';
                            @endphp
                            <span class="badge {{ $badgeClass }} badge-sm">{{ $admission->admission_type }}</span>
                        </td>

                        <!-- Diagnosis -->
                        <td class="max-w-xs">
                            <div class="font-medium text-slate-700">
                                {{ Str::limit($admission->initial_diagnosis ?? 'Pending Diagnosis', 40) }}
                            </div>
                            <div class="text-xs text-gray-500 italic truncate">
                                "{{ $admission->chief_complaint }}"
                            </div>
                        </td>

                        <!-- Date -->
                        <td>
                            <div class="font-medium">{{ $admission->admission_date->format('M d') }}</div>
                            <div class="text-xs text-gray-500">{{ $admission->admission_date->diffForHumans() }}</div>
                        </td>

                        <!-- Action -->
                        <td class="text-right">
                            <a href="{{ route('physician.mypatients.show', $admission->id) }}" class="btn btn-sm btn-primary gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                Open Chart
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-400 italic">
                            No active patients assigned to you.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="mt-4">
            {{ $myPatients->links() }}
        </div>

    </div>
</div>
@endsection