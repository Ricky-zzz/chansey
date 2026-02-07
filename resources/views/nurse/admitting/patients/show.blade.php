@extends('layouts.layout')

@section('content')
    <div class="max-w-7xl mx-auto">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 md:mb-6 gap-2 md:gap-4">
            <div class="text-xs md:text-lg  breadcrumbs text-slate-900 font-bold">
                <ul>
                    <li><a href="{{ route('nurse.admitting.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('nurse.admitting.patients.index') }}">Patients</a></li>
                    <li class=" text-primary">{{ $patient->last_name }}, {{ $patient->first_name }}</li>
                </ul>
            </div>

            <div class="flex gap-2 w-full md:w-auto">
                <a href="{{ route('nurse.admitting.patients.edit', $patient) }}"
                    class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-blue-500 text-white text-sm md:text-base hover:bg-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5h6M6 7v10a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7M6 7h.01" />
                    </svg>
                    <span>Edit Profile</span>
                </a>

                <a href="{{ route('nurse.admitting.admissions.create', $patient) }}"
                    class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-orange-500 text-white text-sm md:text-base hover:bg-orange-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>New Admission</span>
                </a>

                @if($patient->admissions->first())
                <a href="{{ route('patient.print-report', $patient->admissions->first()->id) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-slate-600 text-white text-sm md:text-base hover:bg-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Print Report</span>
                </a>
                @endif
            </div>
        </div>
        <div class="divider text-sm md:text-md font-bold text-neutral">Patient Demographics</div>

        <div class="card bg-base-100 shadow-xl border border-base-200 mb-8">
            <div class="card-body p-3 md:p-6">
                <div class="md:hidden flex flex-col items-center text-center mb-6 pb-6 border-b border-gray-200">
                    <div class="avatar placeholder mb-3">
                        <div
                            class="bg-neutral text-neutral-content rounded-full w-20 h-20 flex items-center justify-center">
                            <span
                                class="text-2xl font-bold">{{ strtoupper(substr($patient->first_name, 0, 1)) }}{{ strtoupper(substr($patient->last_name, 0, 1)) }}</span>
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
                        <p
                            class="text-sm font-mono font-bold text-white bg-secondary px-2 py-1 rounded border border-neutral">
                            {{ $patient->patient_unique_id }}</p>
                    </div>
                </div>

                <!-- Desktop and Mobile: Main Content -->
                <div class="hidden md:flex flex-col md:flex-row gap-3 md:gap-6 items-start">
                    <div class="shrink-0 pt-2 md:pt-4 ">
                        <div class="avatar placeholder">
                            <div
                                class="bg-neutral text-neutral-content rounded-full w-16 h-16 md:w-28 md:h-28  flex items-center justify-center">
                                <span
                                    class="text-md md:text-3xl  font-bold">{{ strtoupper(substr($patient->first_name, 0, 1)) }}{{ strtoupper(substr($patient->last_name, 0, 1)) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Patient Info Grid -->
                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 md:gap-4 ">

                        <!-- Name & ID Section -->
                        <div class="space-y-2 md:space-y-4 ">
                            <div>
                                <h2 class="text-md md:text-2xl  font-bold text-neutral mb-1 md:mb-2 ">
                                    {{ $patient->last_name }}, {{ $patient->first_name }}
                                </h2>
                                @if($patient->middle_name)
                                    <p class="text-xs md:text-xs  font-semibold text-gray-700 mb-1 md:mb-2 ">Middle Name:
                                        {{ $patient->middle_name }}</p>
                                @endif
                                <div class="pt-1 md:pt-2  border-t border-gray-200">
                                    <p class="text-xs text-gray-700 uppercase font-semibold mb-0.5 md:mb-1">Patient ID</p>
                                    <p
                                        class="text-xs md:text-sm  font-mono font-bold text-white bg-secondary px-1 md:px-2  py-0.5 md:py-1  rounded border border-neutral">
                                        {{ $patient->patient_unique_id }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information Section -->
                        <div class="space-y-2 md:space-y-4 ">
                            <div>
                                <h3
                                    class="text-xs md:text-sm md:text-md text-secondary uppercase font-bold tracking-widest mb-2 md:mb-3  pb-1 border-b border-gray-200">
                                    Personal Information</h3>
                                <div class="space-y-1 md:space-y-2 md:space">
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Age</p>
                                        <p class="text-xs md:text-sm font-semibold text-neutral">{{ $patient->age }} years
                                            old</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Sex</p>
                                        <p class="text-xs md:text-sm  font-semibold text-neutral">{{ $patient->sex }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Civil Status</p>
                                        <p class="text-xs md:text-sm  font-semibold text-neutral">
                                            {{ $patient->civil_status }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Date of Birth</p>
                                        <p class="text-xs md:text-sm  font-semibold text-neutral">
                                            {{ $patient->formatted_date_of_birth }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="space-y-2 md:space-y-4 ">
                            <div>
                                <h3
                                    class="text-xs md:text-sm md:text-md text-secondary uppercase font-bold tracking-widest mb-2 md:mb-3  pb-1 border-b border-gray-200">
                                    Contact Information</h3>
                                <div class="space-y-1 md:space-y-2 ">
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Phone Number</p>
                                        <p class="text-xs md:text-sm  font-semibold text-neutral break-all">
                                            {{ $patient->contact_number }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Permanent Address
                                        </p>
                                        <p class="text-xs md:text-sm  font-semibold text-neutral line-clamp-2">
                                            {{ $patient->address_permanent }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Present Address</p>
                                        <p class="text-xs md:text-sm  font-semibold text-neutral line-clamp-2">
                                            {{ $patient->address_present ?? 'Same as permanent' }}</p>
                                    </div>
                                    <div class="pt-1 border-t border-rose-100">
                                        <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Emergency Contact
                                        </p>
                                        <p class="text-xs md:text-sm  font-semibold text-rose-600 break-all">
                                            {{ $patient->emergency_contact_name }} :
                                            {{ $patient->emergency_contact_number }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="md:hidden space-y-4">
                    <!-- Personal Information Section -->
                    <div class="space-y-2">
                        <div>
                            <h3
                                class="text-xs text-secondary uppercase font-bold tracking-widest mb-2 pb-1 border-b border-gray-200">
                                Personal Information</h3>
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
                                    <p class="text-xs font-semibold text-neutral">{{ $patient->formatted_date_of_birth }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="space-y-2">
                        <div>
                            <h3
                                class="text-xs text-secondary uppercase font-bold tracking-widest mb-2 pb-1 border-b border-gray-200">
                                Contact Information</h3>
                            <div class="space-y-1">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Phone Number</p>
                                    <p class="text-xs font-semibold text-neutral break-all">{{ $patient->contact_number }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Permanent Address</p>
                                    <p class="text-xs font-semibold text-neutral line-clamp-2">
                                        {{ $patient->address_permanent }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Present Address</p>
                                    <p class="text-xs font-semibold text-neutral line-clamp-2">
                                        {{ $patient->address_present ?? 'Same as permanent' }}</p>
                                </div>
                                <div class="pt-1 border-t border-rose-100">
                                    <p class="text-xs text-gray-500 uppercase font-semibold mb-0.5">Emergency Contact</p>
                                    <p class="text-xs font-semibold text-rose-600 break-all">
                                        {{ $patient->emergency_contact_name }} : {{ $patient->emergency_contact_number }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="divider text-sm md:text-md font-bold text-neutral">Clinical History</div>

        <div class="card bg-base-100 shadow-xl border border-base-200">
            <div class="card-body p-2 md:p-6">
                <div class="overflow-x-auto">
                    <table class="table table-xs md:table-sm">
                        <thead class="bg-neutral text-white">
                            <tr>
                                <th class="text-xs md:text-sm font-bold">Date</th>
                                <th class="text-xs md:text-sm font-bold">Admission ID</th>
                                <th class="text-xs md:text-sm font-bold">Type</th>
                                <th class="text-xs md:text-sm font-bold">Attending Physician</th>
                                <th class="text-xs md:text-sm font-bold">Status</th>
                                <th class="text-xs md:text-sm font-bold">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admissions as $admission)
                                <tr class="hover:bg-base-200 border-b border-gray-200">
                                    <td>
                                        <div class="font-bold text-xs md:text-sm">
                                            {{ $admission->admission_date->format('M d, Y') }}</div>
                                        <div class="text-xs md:text-xs text-gray-600">
                                            {{ $admission->admission_date->format('h:i A') }}</div>
                                    </td>

                                    <td class="font-mono text-xs md:text-sm font-semibold text-neutral">
                                        {{ $admission->admission_number }}</td>

                                    <td class="text-base font-mono font-semibold text-black">
                                        @php
                                            $badgeClass = match ($admission->admission_type) {
                                                'Inpatient' => 'bg-blue-50 text-blue-700 border border-blue-100',
                                                'Outpatient' => 'bg-orange-50 text-orange-700 border border-orange-100',
                                                'Emergency' => 'bg-red-50 text-red-700 border border-red-100',
                                                'Transfer' => 'bg-stone-50 text-stone-700 border border-stone-100',
                                                default => 'bg-gray-50 text-gray-600 border border-gray-200'
                                            };
                                        @endphp
                                        <div
                                            class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold {{ $badgeClass }}">
                                            {{ $admission->admission_type }}</div>
                                    </td>

                                    <td>
                                        <div class="text-xs md:text-sm text-neutral">Dr.
                                            {{ $admission->attendingPhysician->getFullNameAttribute() ?? 'Unassigned' }}</div>
                                    </td>

                                    <td>
                                        @if($admission->status === 'Discharged')
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold text-gray-500 bg-gray-100 border border-gray-200">Discharged</span>
                                        @elseif($admission->status === 'Cleared')
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold text-yellow-800 bg-yellow-50 border border-yellow-200">Ready
                                                to Go</span>
                                        @else
                                            @if($admission->admission_type === 'Outpatient')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold text-white bg-emerald-600">Active
                                                    Consultation</span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold text-white bg-emerald-600">Admitted</span>
                                            @endif
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('nurse.admitting.admissions.show', $admission->id) }}"
                                            class="btn btn-xs md:btn-xs btn-primary text-white gap-1 md:gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View <span class="hidden sm:block">Admission</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-gray-400 italic text-sm">
                                        No admission history found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $admissions->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
