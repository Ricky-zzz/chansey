@extends('layouts.clinic')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="card-enterprise p-5 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('incident.index') }}" class="btn btn-circle btn-ghost btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Incident Report #{{ $incident->id }}</h2>
                    <p class="text-sm text-slate-500 mt-0.5">{{ $incident->created_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
            </div>

            {{-- Status Badge & Update Button (Head Nurses Only) --}}
            <div class="flex items-center gap-3">
                <div>
                    @if($incident->status === 'resolved')
                        <span class="badge-enterprise badge-success">Resolved</span>
                    @elseif($incident->status === 'investigating')
                        <span class="badge-enterprise badge-warning">Investigating</span>
                    @else
                        <span class="badge-enterprise badge-error">Unresolved</span>
                    @endif
                </div>
                @if($canUpdateStatus && $incident->status !== 'resolved')
                    <button type="button" class="btn btn-sm btn-outline" onclick="document.getElementById('status-update-modal').showModal()">
                        Update Status
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- INCIDENT DETAILS SECTION --}}
    <div class="card-enterprise p-6 mb-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Incident Details</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="detail-group">
                <label class="detail-label">Date & Time</label>
                <p class="detail-value">{{ $incident->time_of_incident->format('F j, Y \a\t g:i A') }}</p>
            </div>

            <div class="detail-group">
                <label class="detail-label">Category</label>
                <p class="detail-value capitalize">{{ str_replace('_', ' ', $incident->incident_category) }}</p>
            </div>

            <div class="detail-group">
                <label class="detail-label">Severity Level</label>
                <div class="mt-1">
                    @if($incident->severity_level === 'Severe')
                        <span class="badge-enterprise badge-error">{{ $incident->severity_level }}</span>
                    @elseif($incident->severity_level === 'High')
                        <span class="badge-enterprise badge-warning">{{ $incident->severity_level }}</span>
                    @elseif($incident->severity_level === 'Moderate')
                        <span class="badge-enterprise badge-info">{{ $incident->severity_level }}</span>
                    @else
                        <span class="badge-enterprise badge-success">{{ $incident->severity_level }}</span>
                    @endif
                </div>
            </div>

            <div class="detail-group">
                <label class="detail-label">Station</label>
                <p class="detail-value">{{ $incident->station->name }}</p>
            </div>

            @if($incident->admission)
                <div class="detail-group">
                    <label class="detail-label">Patient</label>
                    <p class="detail-value">
                        {{ $incident->admission->patient->first_name }} {{ $incident->admission->patient->last_name }}
                        <span class="text-slate-500 text-sm">({{ $incident->admission->admission_number }})</span>
                    </p>
                </div>
            @else
                <div class="detail-group">
                    <label class="detail-label">Patient</label>
                    <p class="detail-value text-slate-400">No patient involved</p>
                </div>
            @endif

            <div class="detail-group">
                <label class="detail-label">Location</label>
                <p class="detail-value">{{ $incident->location_details ?? 'Not recorded' }}</p>
            </div>

            <div class="detail-group">
                <label class="detail-label">Reported By</label>
                <p class="detail-value">{{ $incident->createdBy->name }}</p>
            </div>
        </div>
    </div>

    {{-- NARRATIVE SECTION --}}
    <div class="card-enterprise p-6 mb-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Incident Description</h3>

        <div class="space-y-4">
            @if($incident->what_happened)
                <div>
                    <label class="detail-label">What Happened?</label>
                    <div class="detail-narrative">{{ $incident->what_happened }}</div>
                </div>
            @endif

            @if($incident->how_discovered)
                <div>
                    <label class="detail-label">How Was It Discovered?</label>
                    <div class="detail-narrative">{{ $incident->how_discovered }}</div>
                </div>
            @endif

            @if($incident->action_taken)
                <div>
                    <label class="detail-label">Actions Taken Immediately:</label>
                    <div class="detail-narrative">{{ $incident->action_taken }}</div>
                </div>
            @endif

            @if($incident->narrative)
                <div>
                    <label class="detail-label">Overall Narrative:</label>
                    <div class="detail-narrative">{{ $incident->narrative }}</div>
                </div>
            @endif
        </div>
    </div>

    {{-- PATIENT OUTCOME SECTION --}}
    <div class="card-enterprise p-6 mb-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Patient Outcome</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="detail-group">
                <label class="detail-label">Patient Injured</label>
                <p class="detail-value">
                    @if($incident->injury)
                        <span class="text-red-600 font-semibold">Yes</span>
                    @else
                        <span class="text-green-600 font-semibold">No</span>
                    @endif
                </p>
            </div>

            @if($incident->injury)
                <div class="detail-group">
                    <label class="detail-label">Injury Type</label>
                    <p class="detail-value">{{ $incident->injury_type ?? 'Not recorded' }}</p>
                </div>

                <div class="md:col-span-2">
                    <label class="detail-label">Vitals After Incident</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-2">
                        @php
                            $vitals = $incident->vitals ?? [];
                        @endphp
                        <div class="bg-slate-50 p-3 rounded-lg">
                            <p class="text-xs text-slate-500">BP</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $vitals['bp'] ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-lg">
                            <p class="text-xs text-slate-500">Temperature</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $vitals['temperature'] ?? 'N/A' }}°C</p>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-lg">
                            <p class="text-xs text-slate-500">HR</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $vitals['hr'] ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-lg">
                            <p class="text-xs text-slate-500">PR</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $vitals['pr'] ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-lg">
                            <p class="text-xs text-slate-500">RR</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $vitals['rr'] ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-lg">
                            <p class="text-xs text-slate-500">O2 Saturation</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $vitals['o2'] ?? 'N/A' }}%</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="detail-group">
                <label class="detail-label">Doctor Notified</label>
                <p class="detail-value">
                    @if($incident->doctor_notified)
                        <span class="badge-enterprise badge-success">Yes</span>
                    @else
                        <span class="badge-enterprise">No</span>
                    @endif
                </p>
            </div>

            <div class="detail-group">
                <label class="detail-label">Family Notified</label>
                <p class="detail-value">
                    @if($incident->family_notified)
                        <span class="badge-enterprise badge-success">Yes</span>
                    @else
                        <span class="badge-enterprise">No</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- STAFF INVOLVED SECTION --}}
    @if($incident->involvedStaff->count() > 0)
        <div class="card-enterprise p-6 mb-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Staff Involved <span class="text-slate-500 text-base font-normal">({{ $incident->involvedStaff->count() }})</span></h3>

            <div class="space-y-2">
                @foreach($incident->involvedStaff as $staff)
                    <div class="flex items-start justify-between p-3 bg-slate-50 rounded-lg">
                        <div>
                            <p class="text-sm font-semibold text-slate-800">{{ $staff->name }}</p>
                            <p class="text-xs text-slate-500">{{ $staff->email }}</p>
                        </div>
                        <p class="text-xs font-medium text-slate-600 capitalize">
                            {{ str_replace('_', ' ', $staff->pivot->role_in_incident) }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- WITNESSES SECTION --}}
    @if($incident->witness && count($incident->witness) > 0)
        <div class="card-enterprise p-6 mb-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Other Witnesses <span class="text-slate-500 text-base font-normal">({{ count($incident->witness) }})</span></h3>

            <div class="space-y-2">
                @foreach($incident->witness as $witness)
                    @if(!empty($witness))
                        <div class="p-3 bg-slate-50 rounded-lg">
                            <p class="text-sm font-semibold text-slate-800">{{ $witness }}</p>
                            <p class="text-xs text-slate-500 mt-1">Non-staff witness</p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    {{-- ROOT CAUSE & FOLLOW-UP SECTION --}}
    <div class="card-enterprise p-6 mb-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Root Cause & Follow-up</h3>

        <div class="space-y-4">
            @if($incident->root_cause)
                <div>
                    <label class="detail-label">Root Cause</label>
                    <p class="detail-value capitalize">{{ str_replace('_', ' ', $incident->root_cause) }}</p>
                </div>
            @endif

            @if($incident->follow_up_actions)
                <div>
                    <label class="detail-label">Recommended Follow-up Actions</label>
                    <p class="detail-value">{{ $incident->follow_up_actions }}</p>
                </div>
            @endif

            @if($incident->follow_up_instructions)
                <div>
                    <label class="detail-label">Follow-up Instructions</label>
                    <div class="detail-narrative">{{ $incident->follow_up_instructions }}</div>
                </div>
            @endif
        </div>
    </div>

    {{-- RESOLUTION SECTION --}}
    @if($incident->status === 'resolved')
        <div class="card-enterprise p-6 mb-6 bg-green-50 border border-green-200">
            <h3 class="text-lg font-bold text-green-900 mb-3">Resolution</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="detail-group">
                    <label class="detail-label text-green-900">Resolved By</label>
                    <p class="detail-value">{{ $incident->resolvedBy->name }}</p>
                </div>

                <div class="detail-group">
                    <label class="detail-label text-green-900">Resolution Date</label>
                    <p class="detail-value">{{ $incident->resolved_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- BACK BUTTON --}}
    <div class="mb-6">
        <a href="{{ route('incident.index') }}" class="btn-enterprise-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Incidents
        </a>
    </div>

    {{-- STATUS UPDATE MODAL (Head Nurses Only) --}}
    @if($canUpdateStatus)
        <dialog id="status-update-modal" class="modal">
            <div class="modal-box w-full max-w-md">
                <h3 class="font-bold text-lg mb-4">Update Incident Status</h3>

                <form method="POST" action="{{ route('incident.status-update', $incident) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="label">
                            <span class="label-text font-semibold">Current Status</span>
                        </label>
                        <p class="text-sm text-slate-600 capitalize px-3 py-2 bg-slate-50 rounded-lg">
                            @if($incident->status === 'resolved')
                                <span class="badge-enterprise badge-success">Resolved</span>
                            @elseif($incident->status === 'investigating')
                                <span class="badge-enterprise badge-warning">Investigating</span>
                            @else
                                <span class="badge-enterprise badge-error">Unresolved</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="label">
                            <span class="label-text font-semibold">New Status *</span>
                        </label>
                        <select name="status" required class="select-enterprise w-full">
                            <option value="">-- Select new status --</option>
                            @if($incident->status === 'unresolved')
                                <option value="investigating">Move to Investigating</option>
                                <option value="resolved">Resolve Incident</option>
                            @elseif($incident->status === 'investigating')
                                <option value="unresolved">Back to Unresolved</option>
                                <option value="resolved">Resolve Incident</option>
                            @endif
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3 justify-end">
                        <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('status-update-modal').close()">
                            Cancel
                        </button>
                        <button type="submit" class="btn-enterprise-primary btn-sm">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>

            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
    @endif

</div>

@endsection
