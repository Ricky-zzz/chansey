@extends('layouts.layout')

@section('content')
<!-- 1. THE SEARCH HERO -->
<div class="card-enterprise mb-8">
    <div class="p-8 text-center">
        <h1 class="text-3xl font-bold text-slate-800 mb-1">Patient Admission</h1>
        <p class="text-slate-500 text-sm mb-6">Process new walk-ins, emergency arrivals, or scheduled procedures.</p>

        <form action="{{ route('nurse.admitting.patients.index') }}" method="GET">
            <div class="flex flex-col md:flex-row gap-2 max-w-3xl mx-auto w-full">
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" class="input-enterprise w-full pl-10 h-12 text-base" placeholder="Scan QR or Search (Last Name, PID, DOB)..." required />
                </div>
                <button type="submit" class="btn-enterprise-primary h-12 px-8 text-base">Search</button>
            </div>
        </form>

        <div class="mt-4 text-sm text-slate-500">
            Patient not found?
            <a href="{{ route('nurse.admitting.patients.create') }}" class="text-emerald-600 font-semibold hover:text-emerald-700 transition-colors">Register New Patient</a>
        </div>
    </div>
</div>

<!-- 2. BED AVAILABILITY CARDS -->
<div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-bold text-slate-800">Bed Availability</h2>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach($stats as $type => $data)
    <div class="stat-card">
        <div class="flex justify-between items-start">
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wide">{{ $type }}</div>
                <div class="text-2xl font-bold text-slate-800 mt-1">
                    {{ $data['total'] - $data['available'] }} <span class="text-sm font-normal text-slate-400">/ {{ $data['total'] }}</span>
                </div>
                <div class="text-xs text-slate-500 mt-1">{{ $data['available'] }} available</div>
            </div>
            <div class="p-2.5 rounded-lg {{ $data['bg_icon'] }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-2a2 2 0 00-2-2H9a2 2 0 00-2 2v2m7-2a2 2 0 11-4 0v2m-5-2a2 2 0 11-4 0v2" />
                </svg>
            </div>
        </div>
        <div class="badge-enterprise {{ $data['badge_class'] }} mt-3 text-xs">
            {{ $data['badge_text'] }}
        </div>
    </div>
    @endforeach

</div>

<!-- 3. RECENT ACTIVITY TABLE -->
<h2 class="text-lg font-bold text-slate-800 mb-4">Recent Admissions</h2>
<div class="card-enterprise">
    <div class="overflow-x-auto">
        <table class="table-enterprise">
            <thead>
                <tr>
                    <th>PID</th>
                    <th>Patient Name</th>
                    <th>Admission Type</th>
                    <th>Status</th>
                    <th>Time</th>
                    <th>Assigned Bed</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentAdmissions as $admission)
                <tr class="hover">
                    <td class="font-mono text-emerald-700 font-semibold">{{ $admission->patient->patient_unique_id }}</td>

                    <td>
                        <div class="font-bold">{{ $admission->patient->getFullNameAttribute() }}</div>
                    </td>
                    <td>
                        @php
                        $badgeClass = match($admission->admission_type) {
                        'Emergency' => 'bg-red-50 text-red-700 border border-red-100',
                        'Inpatient' => 'bg-blue-50 text-blue-700 border border-blue-100',
                        'Outpatient' => 'bg-cyan-50 text-cyan-700 border border-cyan-100',
                        default => 'bg-slate-100 text-slate-600 border border-slate-200'
                        };
                        @endphp
                            <div class="badge-enterprise {{ $badgeClass }}">{{ $admission->admission_type }}</div>
                    </td>

                    <td>
                        @if($admission->status === 'Discharged')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold text-gray-500 bg-gray-100 border border-gray-200">Discharged</span>
                        @elseif($admission->status === 'Cleared')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold text-yellow-800 bg-yellow-50 border border-yellow-200">Ready to Go</span>
                        @else
                                @if($admission->admission_type === 'Outpatient')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold text-white bg-emerald-600">Active Consultation</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold text-white bg-emerald-600">Admitted</span>
                                @endif
                        @endif
                    </td>

                    <!-- Time -->
                    <td class="text-sm font-medium text-gray-500">
                        {{ $admission->created_at->diffForHumans() }}
                    </td>

                    <!-- Bed -->
                    <td>
                        @if($admission->bed)
                        <span class="font-bold text-slate-700">{{ $admission->bed->bed_code }}</span>
                        @else
                        <span class="text-xs text-red-600">Outpatient/Waiting</span>
                        @endif
                    </td>

                    <!-- Action Button -->
                    <th>
                        <a href="{{ route('nurse.admitting.admissions.show', $admission->id) }}" class="btn-enterprise-primary text-xs px-3 py-1">
                            View
                        </a>
                    </th>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-gray-500">No recent admissions found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
