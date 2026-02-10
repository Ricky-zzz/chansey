@extends('layouts.layout')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="card-enterprise p-6">

        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Ready for Discharge</h2>
                <p class="text-sm text-slate-500">These patients have settled their bills. Discharge them to free up beds.</p>
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
                                <div class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold {{ $badgeClass }}">{{ $admission->admission_type }}</div>
                            </td>

                            <td>
                                <div class="font-semibold text-base">{{ $admission->patient->last_name }}, {{ $admission->patient->first_name }}</div>
                            <div class="text-sm text-slate-500 font-mono">{{ $admission->patient->patient_unique_id }}</div>
                            </td>
                            <td>
                                <div class="font-semibold text-sm">{{ $admission->admission_date->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $admission->admission_date->format('h:i A') }}</div>
                            </td>
                            <td>
                                @if($admission->bed)
                                <div class="badge-enterprise text-xs font-bold border border-slate-200">{{ $admission->bed->bed_code }}</div>
                                @else
                                <span class="text-xs italic text-gray-400">No Bed Assigned</span>
                                @endif
                            </td>
                            <td>
                                <div class="font-semibold text-sm">
                                    Dr. {{ $admission->attendingPhysician->last_name ?? 'Unassigned' }}
                                </div>
                            </td>
                            </td>
                            <!-- STATUS BADGE -->
                            <td>
                                @if($admission->status === 'Cleared')
                                <span class="inline-flex items-center gap-2 px-2 py-1 rounded-md text-sm font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                    <span class="w-2 h-2 rounded-full bg-yellow-600 animate-pulse"></span>
                                    Cleared to Go
                                </span>
                                @else
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-sm font-semibold bg-gray-100 text-gray-700 border border-gray-300">
                                    Discharged
                                </span>
                                @endif
                            </td>

                            <!-- ACTION BUTTON -->
                            <td>
                                @if($admission->status === 'Cleared')
                                <form action="{{ route('nurse.admitting.discharge', $admission->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-enterprise-warning text-xs w-full" onclick="return confirm('Confirm patient has physically left the bed?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 md:h-5 md:w-5 inline-block " aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                        </svg>

                                        Confirm Discharge
                                    </button>
                                </form>
                                @else
                                <a href="{{ route('nurse.admitting.admissions.show', $admission->id) }}" class="btn-enterprise-secondary text-xs w-full inline-flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 md:h-5 md:w-5 inline-block mr-2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    <span>View History</span>
                                </a>
                                @endif
                            </td>

                        </tr>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
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
