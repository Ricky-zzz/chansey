@extends('layouts.layout')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="bg-white border border-base-300 rounded-xl p-6 shadow">

        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h2 class="text-3xl font-bold text-neutral">Admission Registry</h2>
            </div>

            <div class="flex gap-2 w-full md:w-auto">
                <form action="{{ route('nurse.admitting.admissions.index') }}" method="GET" class="join w-full md:w-96">
                    <input type="text" name="search" class="input input-bordered join-item w-full"
                        placeholder="Search Admission #, Patient Name, or PID..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary join-item">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <div class="card bg-base-100 shadow-xl border border-base-200">
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead class="bg-neutral text-white">
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
                            <td class="text-base font-mono font-semibold text-neutral">
                                {{ $admission->admission_number }} |
                                <div class="badge badge-info badge-sm">{{ $admission->admission_type }}</div>
                            </td>

                            <td>
                                <div class="font-semibold text-base">{{ $admission->patient->last_name }}, {{ $admission->patient->first_name }}</div>
                                <div class="text-sm text-secondary font-mono">{{ $admission->patient->patient_unique_id }}</div>
                            </td>
                            <td>
                                <div class="font-semibold text-sm">{{ $admission->admission_date->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $admission->admission_date->format('h:i A') }}</div>
                            </td>
                            <td>
                                @if($admission->bed)
                                <div class="badge badge-outline font-bold">
                                    {{ $admission->bed->bed_code }}
                                </div>
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
                            <td>
                                @if($admission->status === 'Admitted')
                                <span class="badge badge-sm md:badge badge-success text-success-content gap-2 font-semibold p-2">
                                    <span class="w-2 h-2 rounded-full bg-sky-600 animate-pulse"></span>
                                    Active
                                </span>
                                @else
                                <span class="badge badge-neutral">
                                    {{ ucfirst($admission->status) }}
                                </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('nurse.admitting.admissions.show', $admission->patient_id) }}" class="btn btn-xs md:btn-sm btn-primary text-white gap-1 md:gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    View 
                                </a>
                            </td>
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

            <div class="p-4">
                {{ $admissions->links() }}
            </div>
        </div>

    </div>
</div>
@endsection