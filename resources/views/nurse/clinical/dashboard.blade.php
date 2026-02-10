@extends('layouts.clinic')

@section('content')

    <!-- 1. CLINICAL HEADER & CONTEXT -->
    <div class="flex flex-col md:flex-row justify-between items-end mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Clinical Console</h1>
            <p class="text-sm text-slate-500 mt-1">Overview of your station workload and patient roster.</p>
        </div>

        <!-- Station Badge -->
        <div class="card-enterprise flex items-center gap-3 px-4 py-3 max-w-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="text-emerald-600 shrink-0 w-5 h-5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-2a2 2 0 00-2-2H9a2 2 0 00-2 2v2m7-2a2 2 0 11-4 0v2m-5-2a2 2 0 11-4 0v2"></path></svg>
            <div>
                <div class="text-[11px] text-slate-400 uppercase tracking-wider font-semibold">Assigned Station</div>
                <div class="text-base font-bold text-slate-800">
                    {{ $station->station_name ?? 'Floating / All Stations' }}
                </div>
            </div>
        </div>
    </div>

    <!-- 2. STATION WORKLOAD STATS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

        <!-- Total Patients (My Load) -->
        <div class="stat-card">
            <div class="flex justify-between items-center">
                <div>
                    <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">My Active Patients</div>
                    <div class="text-3xl font-bold text-slate-800 mt-1">{{ $totalPatients }}</div>
                </div>
                <div class="p-2.5 bg-emerald-50 rounded-lg text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
            </div>
        </div>

        <!-- New Arrivals (Last 24h) -->
        <div class="stat-card">
            <div class="flex justify-between items-center">
                <div>
                    <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">New Arrivals (24h)</div>
                    <div class="text-3xl font-bold text-slate-800 mt-1">{{ $newArrivals }}</div>
                </div>
                <div class="p-2.5 bg-sky-50 rounded-lg text-sky-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
        </div>

        <!-- Emergency Cases (High Alert) -->
        <div class="stat-card">
            <div class="flex justify-between items-center">
                <div>
                    <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Emergency Cases</div>
                    <div class="text-3xl font-bold text-slate-800 mt-1">{{ $emergencyCases }}</div>
                </div>
                <div class="p-2.5 bg-red-50 rounded-lg text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. THE "ROUNDING" LIST (Sorted by Bed) -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-slate-800">Current In-Patients</h2>
    </div>

    <div class="card-enterprise overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-enterprise">
                <thead>
                    <tr>
                        <th class="w-32">Bed</th>
                        <th>Patient Details</th>
                        <th>Attending Physician</th>
                        <th>Initial Diagnosis</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeAdmissions as $admission)
                    <tr>
                        <td>
                            <div class="font-bold text-sm text-emerald-700 font-mono">
                                @if($admission->bed?->bed_code)
                                {{ $admission->bed->bed_code }}
                                @else
                                <span class="italic text-slate-400">Outpatient</span>
                                @endif
                            </div>
                        </td>

                        <!-- PATIENT IDENTITY -->
                        <td>
                            <div class="font-semibold text-sm text-slate-800">
                                {{ $admission->patient->getFullNameAttribute() }}
                            </div>
                            <div class="font-medium text-xs text-emerald-600">
                                {{ $admission->patient->patient_unique_id }}
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ $admission->patient->sex }} &middot;
                                {{ $admission->patient->getAgeAttribute() }} yrs old
                            </div>
                        </td>

                        <!-- DOCTOR -->
                        <td>
                            <span class="text-sm font-medium text-slate-700">
                                Dr. {{ $admission->attendingPhysician->getFullNameAttribute() }}
                            </span>
                        </td>

                        <!-- CLINICAL SNAPSHOT -->
                        <td class="max-w-xs">
                            @if($admission->admission_type === 'Emergency')
                                <span class="badge badge-error badge-xs mr-1">ER</span>
                            @endif
                            <span class="text-sm text-slate-500">
                                {{ Str::limit($admission->initial_diagnosis ?? $admission->chief_complaint, 50) }}
                            </span>
                        </td>

                        <!-- ACTION: OPEN CHART -->
                        <td class="text-right">
                            <a href="{{ route('nurse.clinical.ward.show', $admission->id) }}" class="btn-enterprise-primary gap-2 text-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Open Chart
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10">
                            <div class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                <span class="text-slate-400 font-medium text-sm">No active patients in this station.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
