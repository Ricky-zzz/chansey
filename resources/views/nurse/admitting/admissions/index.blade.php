@extends('layouts.layout')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="card-enterprise p-6">

        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Admission Registry</h2>
            </div>

            <div class="flex gap-2 w-full md:w-auto">
                <form action="{{ route('nurse.admitting.admissions.index') }}" method="GET" class="flex w-full md:w-96">
                    <input type="text" name="search" class="input-enterprise rounded-r-none w-full"
                        placeholder="Search Admission #, Patient Name, or PID..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn-enterprise-primary rounded-l-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table-enterprise">
                <thead>
                    <tr>
                            <th>Admission # | Type</th>
                            <th>Patient</th>
                            <th>Date / Time</th>
                            <th>Room-Bed</th>
                            <th>Doctor</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($admissions as $admission)
                        <tr class="hover text-neutral">
                            <td class="text-base font-mono font-semibold text-black">
                                {{ $admission->admission_number }} |
                                @php
                                    $badgeClass = match($admission->admission_type) {
                                        'Inpatient' => 'bg-blue-50 text-blue-700 border border-blue-100',
                                        'Outpatient' => 'bg-orange-50 text-orange-700 border border-orange-100',
                                        'Emergency' => 'bg-red-50 text-red-700 border border-red-100',
                                        'Transfer' => 'bg-stone-50 text-stone-700 border border-stone-100',
                                        default => 'bg-gray-50 text-gray-600 border border-gray-200'
                                    };
                                @endphp
                                <div class="badge-enterprise {{ $badgeClass }}">{{ $admission->admission_type }}</div>
                            </td>

                            <td>
                                <div class="font-semibold text-sm text-slate-800">{{ $admission->patient->getFullNameAttribute()}}</div>
                                <div class="text-xs text-slate-500 font-mono">{{ $admission->patient->patient_unique_id }}</div>
                            </td>
                            <td>
                                <div class="font-semibold text-sm text-slate-700">{{ $admission->admission_date->format('M d, Y') }}</div>
                                <div class="text-xs text-slate-500">{{ $admission->admission_date->format('h:i A') }}</div>
                            </td>
                            <td>
                                @if($admission->bed)
                                <div class="badge-enterprise border border-slate-200 text-slate-700">{{ $admission->bed->bed_code }}</div>
                                @else
                                <span class="text-xs italic text-slate-400">No Bed Assigned</span>
                                @endif
                            </td>
                            <td>
                                <div class="font-semibold text-sm">
                                    Dr. {{ $admission->attendingPhysician->last_name ?? 'Unassigned' }}
                                </div>
                            </td>
                            </td>
                            <td>
                                @if($admission->status === 'Admitted')
                                <span class="badge-enterprise bg-emerald-50 text-emerald-700 border border-emerald-200 gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Active
                                </span>
                                @else
                                <span class="badge-enterprise text-slate-600 bg-slate-100 border border-slate-200">
                                    {{ ucfirst($admission->status) }}
                                </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('nurse.admitting.admissions.show', $admission->id) }}" class="btn-enterprise-primary text-xs px-3 py-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    View
                                </a>
                            </td>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-400 italic text-sm">
                                No admissions found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-200">
                {{ $admissions->links() }}
            </div>

    </div>
</div>
@endsection
