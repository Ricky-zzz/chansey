@extends('layouts.layout')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- 1. BREADCRUMBS & ACTIONS -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('nurse.admitting.dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('nurse.admitting.patients.index') }}">Patients</a></li>
                <li class="font-bold text-primary">{{ $patient->last_name }}, {{ $patient->first_name }}</li>
            </ul>
        </div>

        <div class="join">
            <!-- EDIT BUTTON (For Typos) -->
            <button class="btn btn-neutral join-item btn-sm">Edit Profile</button>
            
            <!-- RE-ADMIT BUTTON (The "New Visit" logic) -->
            <!-- We will build this route later -->
            <a href="#" class="btn btn-primary join-item btn-sm gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                New Admission
            </a>
        </div>
    </div>

    <!-- 2. PATIENT DEMOGRAPHICS (The Permanent Record) -->
    <div class="card bg-base-100 shadow-xl border border-base-200 mb-8">
        <div class="card-body">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Avatar/Icon -->
                <div class="avatar placeholder">
                    <div class="bg-neutral text-neutral-content rounded-full w-24 h-24">
                        <span class="text-3xl">{{ substr($patient->first_name, 0, 1) }}</span>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800">
                            {{ $patient->last_name }}, {{ $patient->first_name }} {{ $patient->middle_name }}
                        </h2>
                        <div class="badge badge-lg badge-outline mt-2 font-mono">{{ $patient->patient_unique_id }}</div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500 uppercase font-bold">Personal Info</div>
                        <p class="font-medium">{{ $patient->age }} years old</p>
                        <p class="font-medium">{{ $patient->sex }} / {{ $patient->civil_status }}</p>
                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($patient->date_of_birth)->format('F d, Y') }}</p>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500 uppercase font-bold">Contact</div>
                        <p class="font-medium">{{ $patient->contact_number }}</p>
                        <p class="text-sm text-gray-600 truncate">{{ $patient->address_permanent }}</p>
                        <div class="mt-2 text-xs text-error">
                            Emergency: {{ $patient->emergency_contact_name }} ({{ $patient->emergency_contact_number }})
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. ADMISSION HISTORY (The Longitudinal Record) -->
    <div class="divider text-lg font-bold text-slate-500">Clinical History</div>

    <div class="card bg-base-100 shadow-xl border border-base-200">
        <div class="overflow-x-auto">
            <table class="table">
                <thead class="bg-base-200">
                    <tr>
                        <th>Date</th>
                        <th>Admission ID</th>
                        <th>Type</th>
                        <th>Attending Physician</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patient->admissions as $admission)
                    <tr class="hover">
                        <!-- Date -->
                        <td>
                            <div class="font-bold">{{ $admission->admission_date->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $admission->admission_date->format('h:i A') }}</div>
                        </td>
                        
                        <!-- ID -->
                        <td class="font-mono text-xs">{{ $admission->admission_number }}</td>
                        
                        <!-- Type -->
                        <td>
                            <div class="badge {{ $admission->admission_type === 'Emergency' ? 'badge-error text-white' : 'badge-ghost' }}">
                                {{ $admission->admission_type }}
                            </div>
                        </td>

                        <!-- Doctor (Using Relationship) -->
                        <td>
                            Dr. {{ $admission->attendingPhysician->last_name ?? 'Unassigned' }}
                            <br>
                            <span class="text-xs text-gray-500">{{ $admission->attendingPhysician->specialization ?? '' }}</span>
                        </td>

                        <!-- Status -->
                        <td>
                            @if($admission->status === 'Admitted')
                                <span class="badge badge-success gap-2">
                                    <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                                    Active
                                </span>
                            @else
                                <span class="badge badge-neutral">Discharged</span>
                            @endif
                        </td>

                        <!-- ACTION: Drill Down -->
                        <td>
                            <!-- We will build this route next -->
                            <a href="#" class="btn btn-sm btn-ghost border-gray-300">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-400 italic">
                            No admission history found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection