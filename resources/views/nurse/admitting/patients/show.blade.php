@extends('layouts.layout')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 md:mb-6 gap-2 md:gap-4">
        <div class="text-xs md:text-sm lg:text-lg breadcrumbs text-slate-900 font-bold">
            <ul>
                <li><a href="{{ route('nurse.admitting.dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('nurse.admitting.patients.index') }}">Patients</a></li>
                <li class=" text-primary">{{ $patient->last_name }}, {{ $patient->first_name }}</li>
            </ul>
        </div>

        <div class="join w-full md:w-auto">
            <a href="{{ route('nurse.admitting.patients.edit', $patient) }}" class="btn btn-accent join-item btn-sm md:btn-lg text-white">Edit Profile</a>           
            <a href="#" class="btn btn-secondary join-item btn-sm md:btn-lg gap-2 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                New Admission
            </a>
        </div>
    </div>
    <div class="divider text-sm md:text-lg font-bold text-neutral">Patient Demographics</div>

    <div class="card bg-base-100 shadow-xl border border-base-200 mb-8">
        <div class="card-body p-3 md:p-6">
            <div class="md:hidden flex flex-col items-center text-center mb-6 pb-6 border-b border-gray-200">
                <div class="avatar placeholder mb-3">
                    <div class="bg-neutral text-neutral-content rounded-full w-20 h-20 flex items-center justify-center">
                        <span class="text-2xl font-bold">{{ strtoupper(substr($patient->first_name, 0, 1)) }}{{ strtoupper(substr($patient->last_name, 0, 1)) }}</span>
                    </div>
                </div>
                <h2 class="text-xl font-bold text-neutral">
                    {{ $patient->last_name }}, {{ $patient->first_name }}
                </h2>
                @if($patient->middle_name)
                    <p class="text-xs font-semibold text-gray-700 mt-1">{{ $patient->middle_name }}</p>
                @endif
                <div class="mt-3 w-full">
                    <p class="text-xs text-gray-700 uppercase font-semibold mb-1">Patient ID</p>
                    <p class="text-sm font-mono font-bold text-white bg-secondary px-2 py-1 rounded border border-neutral">{{ $patient->patient_unique_id }}</p>
                </div>
            </div>

            <!-- Desktop and Mobile: Main Content -->
            <div class="hidden md:flex flex-col md:flex-row gap-3 md:gap-6 lg:gap-8 items-start">
                <div class="shrink-0 pt-2 md:pt-4 lg:pt-8">
                    <div class="avatar placeholder">
                        <div class="bg-neutral text-neutral-content rounded-full w-16 h-16 md:w-28 md:h-28 lg:w-32 lg:h-32 flex items-center justify-center">
                            <span class="text-lg md:text-3xl lg:text-5xl font-bold">{{ strtoupper(substr($patient->first_name, 0, 1)) }}{{ strtoupper(substr($patient->last_name, 0, 1)) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Patient Info Grid -->
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4 lg:gap-6">
                    
                    <!-- Name & ID Section -->
                    <div class="space-y-2 md:space-y-4 lg:space-y-6">
                        <div>
                            <h2 class="text-lg md:text-2xl lg:text-4xl font-bold text-neutral mb-1 md:mb-2 lg:mb-4">
                                {{ $patient->last_name }}, {{ $patient->first_name }}
                            </h2>
                            @if($patient->middle_name)
                                <p class="text-xs md:text-xs lg:text-sm font-semibold text-gray-700 mb-1 md:mb-2 lg:mb-4">Middle Name: {{ $patient->middle_name }}</p>
                            @endif
                            <div class="pt-1 md:pt-2 lg:pt-4 border-t border-gray-200">
                                <p class="text-xs text-gray-700 uppercase font-semibold mb-0.5 md:mb-1">Patient ID</p>
                                <p class="text-xs md:text-sm lg:text-xl font-mono font-bold text-white bg-secondary px-1 md:px-2 lg:px-3 py-0.5 md:py-1 lg:py-2 rounded border border-neutral">{{ $patient->patient_unique_id }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information Section -->
                    <div class="space-y-2 md:space-y-4 lg:space-y-6">
                        <div>
                            <h3 class="text-xs md:text-sm lg:text-lg text-secondary uppercase font-bold tracking-widest mb-2 md:mb-3 lg:mb-5 pb-1 border-b border-gray-200">Personal Information</h3>
                            <div class="space-y-1 md:space-y-2 lg:space-y-4">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Age</p>
                                    <p class="text-xs md:text-sm lg:text-base font-semibold text-neutral">{{ $patient->age }} years old</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Sex</p>
                                    <p class="text-xs md:text-sm lg:text-base font-semibold text-neutral">{{ $patient->sex }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Civil Status</p>
                                    <p class="text-xs md:text-sm lg:text-base font-semibold text-neutral">{{ $patient->civil_status }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Date of Birth</p>
                                    <p class="text-xs md:text-sm lg:text-base font-semibold text-neutral">{{ \Carbon\Carbon::parse($patient->date_of_birth)->format('F d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="space-y-2 md:space-y-4 lg:space-y-6">
                        <div>
                            <h3 class="text-xs md:text-sm lg:text-lg text-secondary uppercase font-bold tracking-widest mb-2 md:mb-3 lg:mb-5 pb-1 border-b border-gray-200">Contact Information</h3>
                            <div class="space-y-1 md:space-y-2 lg:space-y-4">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Phone Number</p>
                                    <p class="text-xs md:text-sm lg:text-base font-semibold text-neutral break-all">{{ $patient->contact_number }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Permanent Address</p>
                                    <p class="text-xs md:text-sm lg:text-base font-semibold text-neutral line-clamp-2">{{ $patient->address_permanent }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Present Address</p>
                                    <p class="text-xs md:text-sm lg:text-base font-semibold text-neutral line-clamp-2">{{ $patient->address_present ?? 'Same as permanent' }}</p>
                                </div>
                                <div class="pt-1 border-t border-rose-100">
                                    <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Emergency Contact</p>
                                    <p class="text-xs md:text-sm lg:text-base font-semibold text-rose-600 break-all">{{ $patient->emergency_contact_name }} : {{ $patient->emergency_contact_number }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
            <!-- Mobile: Additional Info Sections -->
            <div class="md:hidden space-y-4">
                <!-- Personal Information Section -->
                <div class="space-y-2">
                    <div>
                        <h3 class="text-xs text-secondary uppercase font-bold tracking-widest mb-2 pb-1 border-b border-gray-200">Personal Information</h3>
                        <div class="space-y-1">
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Age</p>
                                <p class="text-xs font-semibold text-neutral">{{ $patient->age }} years old</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Sex</p>
                                <p class="text-xs font-semibold text-neutral">{{ $patient->sex }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Civil Status</p>
                                <p class="text-xs font-semibold text-neutral">{{ $patient->civil_status }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Date of Birth</p>
                                <p class="text-xs font-semibold text-neutral">{{ \Carbon\Carbon::parse($patient->date_of_birth)->format('F d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="space-y-2">
                    <div>
                        <h3 class="text-xs text-secondary uppercase font-bold tracking-widest mb-2 pb-1 border-b border-gray-200">Contact Information</h3>
                        <div class="space-y-1">
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Phone Number</p>
                                <p class="text-xs font-semibold text-neutral break-all">{{ $patient->contact_number }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Permanent Address</p>
                                <p class="text-xs font-semibold text-neutral line-clamp-2">{{ $patient->address_permanent }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Present Address</p>
                                <p class="text-xs font-semibold text-neutral line-clamp-2">{{ $patient->address_present ?? 'Same as permanent' }}</p>
                            </div>
                            <div class="pt-1 border-t border-rose-100">
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Emergency Contact</p>
                                <p class="text-xs font-semibold text-rose-600 break-all">{{ $patient->emergency_contact_name }} : {{ $patient->emergency_contact_number }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="divider text-sm md:text-lg font-bold text-neutral">Clinical History</div>

    <div class="card bg-base-100 shadow-xl border border-base-200">
        <div class="card-body p-2 md:p-6">
            <div class="overflow-x-auto">
                <table class="table table-sm md:table-lg">
                    <thead class="bg-neutral text-white">
                        <tr>
                            <th class="text-xs md:text-base font-bold">Date</th>
                            <th class="text-xs md:text-base font-bold">Admission ID</th>
                            <th class="text-xs md:text-base font-bold">Type</th>
                            <th class="text-xs md:text-base font-bold">Attending Physician</th>
                            <th class="text-xs md:text-base font-bold">Status</th>
                            <th class="text-xs md:text-base font-bold">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patient->admissions as $admission)
                        <tr class="hover:bg-base-200 border-b border-gray-200">
                            <td>
                                <div class="font-bold text-xs md:text-base">{{ $admission->admission_date->format('M d, Y') }}</div>
                                <div class="text-xs md:text-sm text-gray-600">{{ $admission->admission_date->format('h:i A') }}</div>
                            </td>
                            
                            <td class="font-mono text-xs md:text-md font-semibold text-neutral">{{ $admission->admission_number }}</td>
                            
                            <td>
                                <div class="badge badge-sm md:badge-lg {{ $admission->admission_type === 'Emergency' ? 'badge-error text-white' : 'badge-info' }} font-semibold">
                                    {{ $admission->admission_type }}
                                </div>
                            </td>

                            <td>
                                <div class="text-xs md:text-base text-neutral">Dr. {{ $admission->attendingPhysician->last_name ?? 'Unassigned' }}</div>
                                <div class="text-xs text-gray-600">{{ $admission->attendingPhysician->specialization ?? 'N/A' }}</div>
                            </td>

                            <td>
                                @if($admission->status === 'Admitted')
                                    <span class="badge badge-sm md:badge-lg badge-success gap-2 font-semibold">
                                        <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="badge badge-sm md:badge-lg badge-neutral font-semibold">Discharged</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('nurse.admitting.admissions.show', $admission->patient_id) }}" class="btn btn-xs md:btn-sm btn-primary text-white gap-1 md:gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    View <span class="hidden sm:block">Admission</span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-400 italic text-lg">
                                No admission history found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection