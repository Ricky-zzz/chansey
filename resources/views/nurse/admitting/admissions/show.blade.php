@extends('layouts.layout')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- 1. BREADCRUMBS & HEADER -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>

            <h2 class="text-xl font-bold text-slate-800">
                Admission Details
                @if($admission->status === 'Admitted')
                <span class="badge-enterprise bg-emerald-50 text-emerald-700 border border-emerald-200 align-middle ml-2">Active</span>
                @else
                <span class="badge-enterprise bg-slate-100 text-slate-600 border border-slate-200 align-middle ml-2">{{ $admission->status }}</span>
                @endif
            </h2>
            <div class="flex items-center gap-1.5 text-xs md:text-sm text-slate-500 mt-1">
                <a href="{{ route('nurse.admitting.dashboard') }}" class="hover:text-emerald-600">Dashboard</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('nurse.admitting.patients.index') }}" class="hover:text-emerald-600">Patients</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('nurse.admitting.patients.show',$admission->patient->id ) }}" class="text-emerald-600 font-medium">{{ $admission->patient->getFullNameAttribute() }}</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('nurse.admitting.admissions.index') }}" class="hover:text-emerald-600">Admissions</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-slate-800 font-semibold">{{ $admission->admission_number }}</span>
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('nurse.admitting.admissions.edit', $admission) }}" class="btn-enterprise-info text-xs px-3 py-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>
                <span>Edit</span>
            </a>

            <a href="{{ route('admission.report', $admission->id) }}" target="_blank" class="btn-enterprise-secondary text-xs px-3 py-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Report</span>
            </a>
        </div>
    </div>

    <!-- 2. TOP ROW: PATIENT CONTEXT & LOCATION -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        <!-- Patient Context Card -->
        <div class="card-enterprise lg:col-span-2">
            <div class="flex flex-row gap-4 lg:gap-6 items-center p-4">
                <div class="shrink-0">
                    <div class="bg-emerald-100 text-emerald-700 rounded-lg w-14 h-14 lg:w-24 lg:h-24 flex items-center justify-center">
                        <span class="text-base lg:text-3xl font-bold">{{ strtoupper(substr($admission->patient->first_name, 0, 1)) }}{{ strtoupper(substr($admission->patient->last_name, 0, 1)) }}</span>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-base lg:text-2xl font-bold text-slate-800 truncate">
                        <a href="{{ route('nurse.admitting.patients.show', $admission->patient_id) }}" class="hover:text-emerald-600 transition-colors">
                            {{ $admission->patient->last_name }}, {{ $admission->patient->first_name }}
                        </a>
                    </h3>
                    <div class="text-sm text-slate-500 font-mono mb-1 truncate">{{ $admission->patient->patient_unique_id }}</div>
                    <div class="flex gap-1.5 flex-wrap">
                        <span class="badge-enterprise bg-slate-100 text-slate-600">{{ $admission->patient->age }} yrs</span>
                        <span class="badge-enterprise bg-slate-100 text-slate-600">{{ $admission->patient->sex }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Location & Doctor Card -->
        <div class="card-enterprise">
            <div class="p-4">
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
            <div class="card-enterprise">
                <div class="p-5">
                    <h3 class="text-xs uppercase font-bold text-slate-500 tracking-wider mb-4">Initial Vital Signs</h3>
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
                            <div class="text-xs text-slate-500">RR</div>
                            <div class="font-bold text-lg">{{ $admission->initial_vitals['rr'] ?? '--' }}</div>
                        </div>
                        <div class="p-2 bg-slate-50 rounded-lg">
                            <div class="text-xs text-slate-500">O2 Sat</div>
                            <div class="font-bold text-lg text-primary">{{ $admission->initial_vitals['o2'] ?? '--' }}%</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- B. CLINICAL DETAILS -->
            <div class="card-enterprise">
                <div class="p-5">
                    <h3 class="text-base font-bold text-slate-800 mb-4">Clinical Information</h3>

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
                            <span class="badge-enterprise bg-red-50 text-red-700 border border-red-100">{{ $allergy }}</span>
                            @endforeach
                        </div>
                        @else
                        <span class="text-sm text-slate-500">No known allergies recorded.</span>
                        @endif
                    </div>

                    <!-- Medication History -->
                    <div class="mt-4 pt-4 border-t border-slate-200">
                        <div class="text-xs font-bold text-slate-500 uppercase mb-2">Medication History</div>
                        @if(!empty($admission->medication_history))
                        <div class="flex flex-wrap gap-2">
                            @foreach($admission->medication_history as $medication)
                            <span class="badge-enterprise bg-blue-50 text-blue-700 border border-blue-100">{{ $medication }}</span>
                            @endforeach
                        </div>
                        @else
                        <span class="text-sm text-slate-500">No medications recorded.</span>
                        @endif
                    </div>

                    <!-- Past Medical History -->
                    <div class="mt-4 pt-4 border-t border-slate-200">
                        <div class="text-xs font-bold text-slate-500 uppercase mb-3">Past Medical History</div>
                        @if(!empty($admission->past_medical_history) && count($admission->past_medical_history) > 0)
                        <div class="overflow-x-auto">
                            <table class="table table-sm table-zebra w-full bg-white border border-slate-200 rounded-lg">
                                <thead class="bg-slate-100 border-b border-slate-200">
                                    <tr>
                                        <th class="text-left text-xs font-bold text-slate-700">Type</th>
                                        <th class="text-left text-xs font-bold text-slate-700">Description</th>
                                        <th class="text-left text-xs font-bold text-slate-700">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($admission->past_medical_history as $record)
                                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                                        <td class="text-sm font-semibold text-slate-700">{{ $record['type'] ?? 'N/A' }}</td>
                                        <td class="text-sm text-slate-600">{{ $record['description'] ?? 'N/A' }}</td>
                                        <td class="text-sm text-slate-600">{{ $record['date'] ?? 'N/A' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <span class="text-sm text-slate-500">No past medical history recorded.</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- C. UPLOADED DOCUMENTS -->
            <div class="card-enterprise">
                <div class="p-5">
                    <h3 class="text-base font-bold text-slate-800 mb-4">Attached Documents</h3>

                    @if($admission->files->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="table-enterprise text-sm">
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
                                        <a href="{{ route('document.view', $file->id) }}" target="_blank" class="btn-enterprise-primary text-xs px-2 py-0.5">
                                            View File
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="flex items-center gap-3 p-4 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-amber-600 shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>No documents uploaded for this admission.</span>
                    </div>
                    @endif
                </div>
            </div>

        </div>
        <div class="lg:col-span-1 space-y-6">

            <div class="card-enterprise">
                <div class="p-5">
                    <h3 class="text-base font-bold text-emerald-700 mb-4">Financial Info</h3>

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

                        <div class="border-t border-slate-200 my-4"></div>

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
