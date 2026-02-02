@extends('layouts.layout')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- 1. BREADCRUMBS & HEADER -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>

            <h2 class="text-xl font-black text-slate-800">
                Admission Details
                @if($admission->status === 'Admitted')
                <span class="badge badge-success text-white align-middle ml-2">Active</span>
                @else
                <span class="badge badge-neutral text-white align-middle ml-2">{{ $admission->status }}</span>
                @endif
            </h2>
            <div class="text-xs md:text-sm lg:text-md breadcrumbs text-slate-900 font-bold">
                <ul>
                    <li><a href="{{ route('nurse.admitting.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('nurse.admitting.patients.index') }}">Patients</a></li>
                    <li class=" text-primary"><a href="{{ route('nurse.admitting.patients.show',$admission->patient->id ) }}">{{ $admission->patient->getFullNameAttribute() }} </a></li>
                    <li><a href="{{ route('nurse.admitting.admissions.index') }}">Admissions</a></li>
                    <li class="font-bold text-primary">{{ $admission->admission_number }}</li>
                </ul>
            </div>
        </div>

        <a href="{{ route('nurse.admitting.admissions.edit', $admission) }}" class="inline-flex items-center gap-2 text-white bg-blue-500 btn btn-lg px-4 py-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
            </svg>
            <span>Edit Details</span>
        </a>
    </div>

    <!-- 2. TOP ROW: PATIENT CONTEXT & LOCATION -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        <!-- Patient Context Card -->
        <div class="card bg-white shadow-sm border border-slate-200 lg:col-span-2">
            <div class="card-body flex-row gap-4 lg:gap-6 items-center p-3 lg:p-4">
                <div class="avatar placeholder shrink-0">
                    <div class="bg-neutral text-neutral-content rounded-full w-14 h-14 lg:w-28 lg:h-28 flex items-center justify-center">
                        <span class="text-base lg:text-4xl font-bold">{{ strtoupper(substr($admission->patient->first_name, 0, 1)) }}{{ strtoupper(substr($admission->patient->last_name, 0, 1)) }}</span>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-base lg:text-3xl font-bold text-slate-700 truncate">
                        <a href="{{ route('nurse.admitting.patients.show', $admission->patient_id) }}" class="link link-hover">
                            {{ $admission->patient->last_name }}, {{ $admission->patient->first_name }}
                        </a>
                    </h3>
                    <div class=" text-sm lg:text-base text-slate-600 font-mono mb-1 lg:mb-2 truncate">{{ $admission->patient->patient_unique_id }}</div>
                    <div class="flex gap-2 flex-wrap">
                        <span class="badge badge-xs lg:badge-sm badge-ghost">{{ $admission->patient->age }} yrs</span>
                        <span class="badge badge-xs lg:badge-sm badge-ghost">{{ $admission->patient->sex }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Location & Doctor Card -->
        <div class="card bg-white shadow-sm border border-slate-200">
            <div class="card-body p-4">
                @if($admission->status !== 'Admitted')
                <h4 class="uppercase font-bold text-sm tracking-widest text-red-500 mb-2">Last location</h4>
                @else
                <h4 class="uppercase font-bold text-sm tracking-widest text-blue-500 mb-2">Current location</h4>
                @endif

                <div class="mb-2">
                    <div class="text-xs text-slate-600 uppercase font-semibold">Station</div>
                    <div class="text-md font-bold text-slate-700">{{ $admission->station->station_name ?? 'N/A' }}</div>
                </div>

                <div class="mb-3">
                    <div class="text-xs text-slate-600 uppercase font-semibold">Room</div>
                    <div class="text-md font-extrabold text-slate-800">
                        @if($admission->bed)
                        {{ $admission->bed->bed_code }}
                        @else
                        <span class="text-sm text-gray-400">Outpatient / Waiting</span>
                        @endif
                    </div>
                </div>

                <div class="mb-1">
                    <div class="text-xs text-slate-600 uppercase font-semibold">Attending physician</div>
                    <div class="text-sm font-extrabold text-slate-800">Dr. {{ $admission->attendingPhysician->last_name ?? 'None' }}, {{ $admission->attendingPhysician->first_name ?? 'None' }}</div>
                    <div class="text-sm text-slate-700">{{ $admission->attendingPhysician->specialization ?? '' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- LEFT COLUMN: CLINICAL DATA (2/3 width) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- A. VITALS SNAPSHOT -->
            <div class="card bg-base-100 shadow-sm border border-slate-200">
                <div class="card-body p-6">
                    <h3 class="card-title text-sm uppercase font-bold mb-4">Initial Vital Signs</h3>
                    <div class="grid grid-cols-3 md:grid-cols-6 gap-4 text-center">
                        <div class="p-2 bg-slate-50 rounded-lg">
                            <div class="text-xs text-slate-500">Temp</div>
                            <div class="font-bold text-lg">{{ $admission->initial_vitals['temp'] ?? '--' }}°C</div>
                        </div>
                        <div class="p-2 bg-slate-50 rounded-lg">
                            <div class="text-xs text-slate-500">BP</div>
                            <div class="font-bold text-lg">{{ $admission->initial_vitals['bp'] ?? '--' }}</div>
                        </div>
                        <div class="p-2 bg-slate-50 rounded-lg">
                            <div class="text-xs text-slate-500">HR</div>
                            <div class="font-bold text-lg">{{ $admission->initial_vitals['hr'] ?? '--' }}</div>
                        </div>
                        <div class="p-2 bg-slate-50 rounded-lg">
                            <div class="text-xs text-slate-500">PR</div>
                            <div class="font-bold text-lg">{{ $admission->initial_vitals['pr'] ?? '--' }}</div>
                        </div>
                        <div class="p-2 bg-slate-50 rounded-lg">
                            <div class="text-xs text-slate-500">O2 Sat</div>
                            <div class="font-bold text-lg text-primary">{{ $admission->initial_vitals['o2'] ?? '--' }}%</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- B. CLINICAL DETAILS -->
            <div class="card bg-base-100 shadow-sm border border-slate-200">
                <div class="card-body">
                    <h3 class="card-title text-lg mb-4">Clinical Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <div class="text-xs font-bold text-slate-500 uppercase">Admission Type</div>
                            <div class="font-medium">{{ $admission->admission_type }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-slate-500 uppercase">Mode of Arrival</div>
                            <div class="font-medium">{{ $admission->mode_of_arrival }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-slate-500 uppercase">Date of Admission</div>
                            <div class="font-medium">{{ $admission->admission_date->format('F d, Y h:i A') }}</div>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="mb-4">
                        <div class="text-xs font-bold text-slate-500 uppercase mb-1">Chief Complaint</div>
                        <p class="p-3 bg-slate-50 rounded-md border border-slate-100 text-slate-700 italic">
                            "{{ $admission->chief_complaint }}"
                        </p>
                    </div>

                    <div class="mb-4">
                        <div class="text-xs font-bold text-slate-500 uppercase mb-1">Initial Diagnosis</div>
                        <p class="font-medium">{{ $admission->initial_diagnosis ?? 'Pending diagnosis' }}</p>
                    </div>

                    <!-- Allergies -->
                    <div>
                        <div class="text-xs font-bold text-slate-500 uppercase mb-2">Known Allergies</div>
                        @if(!empty($admission->known_allergies))
                        <div class="flex flex-wrap gap-2">
                            @foreach($admission->known_allergies as $allergy)
                            <span class="badge badge-error text-white font-bold">{{ $allergy }}</span>
                            @endforeach
                        </div>
                        @else
                        <span class="text-sm text-slate-500">No known allergies recorded.</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- C. UPLOADED DOCUMENTS -->
            <div class="card bg-base-100 shadow-sm border border-slate-200">
                <div class="card-body">
                    <h3 class="card-title text-lg mb-4">Attached Documents</h3>

                    @if($admission->files->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Document Type</th>
                                    <th>Filename</th>
                                    <th>Uploaded By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($admission->files as $file)
                                <tr>
                                    <td class="font-bold text-slate-700">
                                        {{ $file->document_type }}
                                    </td>
                                    <td class="font-mono text-xs text-slate-500 max-w-[150px] truncate">
                                        {{ $file->file_name }}
                                    </td>
                                    <td class="text-xs">
                                        {{ $file->uploader->name ?? 'System' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('document.view', $file->id) }}" target="_blank" class="btn btn-xs btn-outline btn-primary">
                                            View File
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="alert alert-warning text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>No documents uploaded for this admission.</span>
                    </div>
                    @endif
                </div>
            </div>

        </div>
        <div class="lg:col-span-1 space-y-6">

            <div class="card bg-base-100 shadow-sm border border-slate-200">
                <div class="card-body">
                    <h3 class="card-title text-primary text-lg mb-4">Financial Info</h3>

                    @if($admission->billingInfo)
                    <div class="space-y-4">
                        <div>
                            <div class="text-xs font-bold text-slate-500 uppercase">Payment Method</div>
                            <div class="font-bold text-xl">{{ $admission->billingInfo->payment_type }}</div>
                        </div>

                        @if($admission->billingInfo->primary_insurance_provider)
                        <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <div class="text-xs font-bold text-blue-500 uppercase">Insurance</div>
                            <div class="font-bold text-blue-900">{{ $admission->billingInfo->primary_insurance_provider }}</div>
                            <div class="text-xs text-blue-700 mt-1">Policy: {{ $admission->billingInfo->policy_number }}</div>
                            <div class="text-xs text-blue-700">Approval: {{ $admission->billingInfo->approval_code ?? 'Pending' }}</div>
                        </div>
                        @endif

                        <div class="divider"></div>

                        <div>
                            <div class="text-xs font-bold text-slate-500 uppercase">Guarantor</div>
                            <div class="font-medium">{{ $admission->billingInfo->guarantor_name ?? 'Self' }}</div>
                            <div class="text-xs text-slate-500">{{ $admission->billingInfo->guarantor_relationship ?? 'Patient' }}</div>
                            <div class="text-xs text-slate-500 mt-1">{{ $admission->billingInfo->guarantor_contact ?? '' }}</div>
                        </div>
                    </div>
                    @else
                    <div class="text-sm text-slate-500 italic">No billing info recorded.</div>
                    @endif
                </div>
            </div>

            <!-- Audit Info (Bottom Right) -->
            <div class="text-xs text-slate-500 px-3 py-2 bg-white rounded border border-slate-200 font-mono">
                <div>Admitted By: <span class="font-medium text-rose-600">{{ $admission->admittingClerk->name ?? 'Unknown' }}</span></div>
                <div>Date: <span class="font-medium text-rose-600"> {{ $admission->created_at->format('M d, Y h:i A') }}</span></div>
            </div>

            @if($admission->status !== 'Admitted')
            <div class="text-xs text-slate-500 px-3 py-2 bg-white rounded border border-slate-200 font-mono">
                <div>Discharged Date: <span class="font-medium text-rose-600"> {{ $admission->discharge_date?->format('M d, Y h:i A') ?? 'N/A' }}</span></div>
            </div>
            @endif

        </div>

    </div>
</div>
@endsection