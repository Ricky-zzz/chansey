@extends('layouts.physician')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="card-enterprise p-6">

        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800">My Patient List</h2>
                <p class="text-sm text-slate-500">List of all Active Patients</p>
            </div>

            <!-- SEARCH -->
            <form action="{{ route('physician.mypatients.index') }}" method="GET" class="flex w-full md:w-96">
                <input type="text" name="search" class="input-enterprise rounded-r-none w-full" placeholder="Search Patient..." value="{{ request('search') }}">
                <button type="submit" class="btn-enterprise-primary rounded-l-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </button>
            </form>
        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="table-enterprise">
                <thead>
                    <tr>
                        <th>Location</th> <!-- Critical for rounds -->
                        <th>Patient</th>
                        <th>Type</th>
                        <th>Case Details</th>
                        <th>Encounter Date</th>
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
                            <div class="badge-enterprise bg-slate-100 text-slate-600 font-mono text-xs">
                                {{ $admission->bed->bed_code ?? 'none' }}
                            </div>
                        </td>

                        <!-- Patient -->
                        <td>
                            <div class="font-bold text-sm text-emerald-700">
                                {{ $admission->patient->getFullNameAttribute() }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $admission->patient->sex }} • {{ $admission->patient->age }} yo
                            </div>
                        </td>

                        <!-- Type -->
                        <td>
                            @php
                                $typeColors = [
                                    'Inpatient' => 'bg-blue-50 text-blue-700 border border-blue-200',
                                    'Outpatient' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                    'Emergency' => 'bg-red-50 text-red-700 border border-red-200',
                                ];
                                $badgeClass = $typeColors[$admission->admission_type] ?? 'bg-slate-100 text-slate-600';
                            @endphp
                            <span class="badge-enterprise {{ $badgeClass }} text-xs">{{ $admission->admission_type }}</span>
                        </td>

                        <!-- Diagnosis -->
                        <td class="max-w-xs">
                            <div class="font-medium text-slate-700">
                                {{ $admission->initial_diagnosis }}
                            </div>
                            <div class="text-xs text-gray-500 italic truncate">
                                "{{ $admission->truncatedChiefComplaint() }}"
                            </div>
                        </td>

                        <!-- Date -->
                        <td>
                            <div class="font-medium">{{ $admission->admission_date->format('M d') }}</div>
                            <div class="text-xs text-gray-500">{{ $admission->admission_date->diffForHumans() }}</div>
                        </td>

                        <!-- Action -->
                        <td class="text-right">
                            <a href="{{ route('physician.mypatients.show', $admission->id) }}" class="btn-enterprise-primary text-xs gap-2">
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
