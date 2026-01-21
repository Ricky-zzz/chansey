@extends('layouts.clinic')

@section('content')

    <!-- 1. CLINICAL HEADER & CONTEXT -->
    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800">Clinical Console</h1>
        </div>

        <!-- Station Badge -->
        <div class="alert bg-white shadow-sm border border-base-200 max-w-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-info shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-2a2 2 0 00-2-2H9a2 2 0 00-2 2v2m7-2a2 2 0 11-4 0v2m-5-2a2 2 0 11-4 0v2"></path></svg>
            <div>
                <div class="text-xs text-slate-400 uppercase tracking-widest font-bold">Assigned Station</div>
                <div class="text-xl font-black text-slate-800">
                    {{ $station->station_name ?? 'Floating / All Stations' }}
                </div>
            </div>
        </div>
    </div>

    <!-- 2. STATION WORKLOAD STATS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

        <!-- Total Patients (My Load) -->
        <div class="card bg-base-100 shadow-md border-l-8 border-primary">
            <div class="card-body p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-bold text-gray-400 uppercase">My Active Patients</div>
                        <div class="text-4xl font-black text-slate-800">{{ $totalPatients }}</div>
                    </div>
                    <div class="p-3 bg-primary/10 rounded-full text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Arrivals (Last 24h) -->
        <div class="card bg-base-100 shadow-md border-l-8 border-secondary">
            <div class="card-body p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-bold text-gray-400 uppercase">New Arrivals (24h)</div>
                        <div class="text-4xl font-black text-slate-800">{{ $newArrivals }}</div>
                    </div>
                    <div class="p-3 bg-secondary/10 rounded-full text-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emergency Cases (High Alert) -->
        <div class="card bg-base-100 shadow-md border-l-8 border-error">
            <div class="card-body p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-bold text-gray-400 uppercase">Emergency Cases</div>
                        <div class="text-4xl font-black text-slate-800">{{ $emergencyCases }}</div>
                    </div>
                    <div class="p-3 bg-error/10 rounded-full text-error">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. THE "ROUNDING" LIST (Sorted by Bed) -->
    <h2 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-2">
        <span>Current In-Patients</span>
    </h2>

    <div class="card bg-base-100 shadow-xl border border-base-200">
        <div class="overflow-x-auto">
            <table class="table table-lg">
                <thead class="bg-neutral text-neutral-content font-bold uppercase text-xs">
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
                    <tr class="hover group">
                        <td>
                            <div class="font-black text-md text-primary font-mono">
                                @if($admission->bed?->bed_code)
                                {{ $admission->bed->bed_code }}
                                @else
                                <span class="italic text-gray-600">Outpatient</span>
                                @endif
                            </div>
                        </td>

                        <!-- PATIENT IDENTITY -->
                        <td>
                            <div class="font-bold text-sm text-slate-800">
                                {{ $admission->patient->getFullNameAttribute() }}
                            </div>
                            <div class="font-bold text-xs text-sky-600">
                                {{ $admission->patient->patient_unique_id }}
                            </div>
                            <div class="text-xs text-gray-500 font-medium">
                                {{ $admission->patient->sex }} •
                                {{ $admission->patient->getAgeAttribute() }} yrs old
                            </div>
                        </td>

                        <!-- DOCTOR -->
                        <td>
                            <div class="flex items-center text-md">
                                <span class=" text-sm font-semibold text-slate-700">
                                    Dr. {{ $admission->attendingPhysician->getFullNameAttribute() }}
                                </span>
                            </div>
                        </td>

                        <!-- CLINICAL SNAPSHOT -->
                        <td class="max-w-xs">
                            @if($admission->admission_type === 'Emergency')
                                <span class="badge badge-error badge-xs mr-1">ER</span>
                            @endif
                            <span class="italic text-gray-600 text-xs">
                                {{ Str::limit($admission->initial_diagnosis ?? $admission->chief_complaint, 50) }}
                            </span>
                        </td>

                        <!-- ACTION: OPEN CHART -->
                        <td class="text-right">
                            <a href="{{ route('nurse.clinical.ward.show', $admission->id) }}" class="btn btn-primary btn-sm gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Open Chart
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10">
                            <div class="flex flex-col items-center opacity-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                <span class="text-gray-400 font-medium">No active patients in this station.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
