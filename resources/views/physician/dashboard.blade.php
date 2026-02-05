@extends('layouts.physician')

@section('content')

    <!-- 1. HEADER: DOCTOR CONTEXT -->
    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800">Physician's Deck</h1>
            <div class="text-sm text-slate-500">Welcome back, Dr. {{ $physician->last_name }}</div>
        </div>

        <!-- Department Badge -->
        <div class="alert bg-white shadow-sm border border-slate-200 max-w-sm">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-50 rounded text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                </div>
                <div>
                    <div class="text-xs text-slate-400 uppercase tracking-widest font-bold">Department</div>
                    <div class="text-lg font-black text-slate-800">
                        {{ $physician->department->name }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. STATS ROW -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

        <!-- Total Load -->
        <div class="card bg-base-100 shadow-md border-l-8 border-indigo-500">
            <div class="card-body p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-bold text-gray-400 uppercase">My Active Census</div>
                        <div class="text-4xl font-black text-slate-800">{{ $myTotalPatients }}</div>
                    </div>
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- New / 24h -->
        <div class="card bg-base-100 shadow-md border-l-8 border-emerald-500">
            <div class="card-body p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-bold text-gray-400 uppercase">New Admissions (24h)</div>
                        <div class="text-4xl font-black text-slate-800">{{ $newReferrals }}</div>
                    </div>
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- ER Cases -->
        <div class="card bg-base-100 shadow-md border-l-8 border-rose-500">
            <div class="card-body p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-bold text-gray-400 uppercase">Emergency / Critical</div>
                        <div class="text-4xl font-black text-slate-800">{{ $emergencyCases }}</div>
                    </div>
                    <div class="p-3 bg-rose-50 text-rose-600 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. PATIENT LIST (Rounds View) -->
    <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-2">
        <span>Rounds List</span>
        <span class="badge badge-neutral text-xs font-normal">Sorted by Location</span>
    </h2>

    <div class="card bg-base-100 shadow-xl border border-base-200">
        <div class="overflow-x-auto">
            <table class="table table-lg">
                <thead class="bg-neutral text-neutral-content font-bold uppercase text-xs">
                    <tr>
                        <th>Location</th> <!-- Key difference for doctors -->
                        <th>Patient Name</th>
                        <th>Type</th>
                        <th>Case Details</th>
                        <th>Admission Date</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($myPatients as $admission)
                    <tr class="hover">
                        <td>
                            <div class="font-bold text-slate-700">
                                {{ $admission->station->station_name ?? 'Floating' }}
                            </div>
                            <div class="badge badge-outline text-xs font-mono bg-white">
                                {{ $admission->bed->bed_code ?? 'Outpatient' }}
                            </div>
                        </td>

                        <!-- Identity -->
                        <td>
                            <div class="font-bold text-lg text-primary">
                                {{ $admission->patient->last_name }}, {{ $admission->patient->first_name }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $admission->patient->sex }} • {{ $admission->patient->age }} yrs
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

                        <!-- Case: Doctors care about Diagnosis, not just "Admitted" -->
                        <td class="max-w-xs">
                            <div class="font-semibold text-slate-700 text-sm">
                                {{ $admission->initial_diagnosis ?? 'Pending Diagnosis' }}
                            </div>
                            <div class="text-xs text-gray-500 truncate italic">
                                "{{ $admission->truncatedChiefComplaint() }}"
                            </div>
                            @if($admission->admission_type === 'Emergency')
                                <span class="badge badge-error badge-xs text-white mt-1">Emergency</span>
                            @endif
                        </td>

                        <!-- Time -->
                        <td>
                            <div class="text-sm font-bold">{{ $admission->admission_date->format('M d') }}</div>
                            <div class="text-xs text-gray-500">{{ $admission->admission_date->diffForHumans() }}</div>
                        </td>

                        <!-- Action -->
                        <td class="text-right">
                            <!-- Link to Physician's View of Patient (To be built next) -->
                            <a href="{{ route('physician.mypatients.show', $admission->id) }}" class="px-3 py-1.5 text-sm bg-blue-600 text-white hover:bg-blue-700 rounded-lg transition-colors inline-flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                View Chart
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-400">
                            You have no active patients assigned.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
