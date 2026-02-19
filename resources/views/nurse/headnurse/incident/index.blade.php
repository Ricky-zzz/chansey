@extends('layouts.clinic')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="card-enterprise p-5 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Incident Reports</h2>
                <p class="text-sm text-slate-500 mt-0.5">Monitor and manage safety incidents in your station</p>
            </div>
            <a href="{{ route('nurse.incidents.create') }}" class="btn-enterprise-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Report New Incident
            </a>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="stat-card">
            <p class="stat-label">Total Incidents</p>
            <p class="stat-value">{{ $totalCount }}</p>
            <p class="stat-detail">This month</p>
        </div>
        <div class="stat-card stat-card-error">
            <p class="stat-label">Unresolved</p>
            <p class="stat-value">{{ $unresolvedCount }}</p>
            <p class="stat-detail">Awaiting review</p>
        </div>
        <div class="stat-card stat-card-warning">
            <p class="stat-label">Investigating</p>
            <p class="stat-value">{{ $investigatingCount }}</p>
            <p class="stat-detail">In progress</p>
        </div>
        <div class="stat-card stat-card-error">
            <p class="stat-label">Severe</p>
            <p class="stat-value">{{ $severeCount }}</p>
            <p class="stat-detail">High priority</p>
        </div>
    </div>

    {{-- TABS --}}
    <div class="card-enterprise p-0 overflow-hidden">
        <div class="tabs tabs-bordered">
            <input type="radio" name="incident_tabs" class="tab" label="All Incidents" checked />
            <div class="tab-content p-6 space-y-4">
                @if($allIncidents->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="table-enterprise w-full">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Category</th>
                                    <th>Severity</th>
                                    <th>Status</th>
                                    <th>Reported By</th>
                                    <th>Station</th>
                                    <th>Involved</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allIncidents as $incident)
                                    <tr class="hover">
                                        <td class="text-sm">{{ $incident->time_of_incident->format('M d') }}</td>
                                        <td class="text-sm capitalize">{{ str_replace('_', ' ', $incident->incident_category) }}</td>
                                        <td>
                                            @if($incident->severity_level === 'Severe')
                                                <span class="badge-enterprise badge-error">{{ $incident->severity_level }}</span>
                                            @elseif($incident->severity_level === 'High')
                                                <span class="badge-enterprise badge-warning">{{ $incident->severity_level }}</span>
                                            @elseif($incident->severity_level === 'Moderate')
                                                <span class="badge-enterprise badge-info">{{ $incident->severity_level }}</span>
                                            @else
                                                <span class="badge-enterprise badge-success">{{ $incident->severity_level }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($incident->status === 'resolved')
                                                <span class="badge-enterprise badge-success">Resolved</span>
                                            @elseif($incident->status === 'investigating')
                                                <span class="badge-enterprise badge-warning">Investigating</span>
                                            @else
                                                <span class="badge-enterprise badge-error">Unresolved</span>
                                            @endif
                                        </td>
                                        <td class="text-sm">{{ $incident->createdBy->name }}</td>
                                        <td class="text-sm">{{ $incident->station->name }}</td>
                                        <td class="text-center">
                                            <button class="link link-primary text-xs" onclick="showInvolved({{ $incident->id }})">
                                                {{ $incident->involvedStaff->count() }}
                                            </button>
                                        </td>
                                        <td>
                                            <a href="{{ route('nurse.incidents.show', $incident) }}" class="link link-primary text-xs">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $allIncidents->links() }}
                @else
                    <p class="text-center py-8 text-slate-500">No incidents reported yet</p>
                @endif
            </div>

            <input type="radio" name="incident_tabs" class="tab" label="Reported by Me" />
            <div class="tab-content p-6 space-y-4">
                @if($myReports->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="table-enterprise w-full">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Category</th>
                                    <th>Severity</th>
                                    <th>Status</th>
                                    <th>Station</th>
                                    <th>Involved</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($myReports as $incident)
                                    <tr class="hover">
                                        <td class="text-sm">{{ $incident->time_of_incident->format('M d') }}</td>
                                        <td class="text-sm capitalize">{{ str_replace('_', ' ', $incident->incident_category) }}</td>
                                        <td>
                                            @if($incident->severity_level === 'Severe')
                                                <span class="badge-enterprise badge-error">{{ $incident->severity_level }}</span>
                                            @elseif($incident->severity_level === 'High')
                                                <span class="badge-enterprise badge-warning">{{ $incident->severity_level }}</span>
                                            @elseif($incident->severity_level === 'Moderate')
                                                <span class="badge-enterprise badge-info">{{ $incident->severity_level }}</span>
                                            @else
                                                <span class="badge-enterprise badge-success">{{ $incident->severity_level }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($incident->status === 'resolved')
                                                <span class="badge-enterprise badge-success">Resolved</span>
                                            @elseif($incident->status === 'investigating')
                                                <span class="badge-enterprise badge-warning">Investigating</span>
                                            @else
                                                <span class="badge-enterprise badge-error">Unresolved</span>
                                            @endif
                                        </td>
                                        <td class="text-sm">{{ $incident->station->name }}</td>
                                        <td class="text-center">
                                            <button class="link link-primary text-xs" onclick="showInvolved({{ $incident->id }})">
                                                {{ $incident->involvedStaff->count() }}
                                            </button>
                                        </td>
                                        <td>
                                            <a href="{{ route('nurse.incidents.show', $incident) }}" class="link link-primary text-xs">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $myReports->links() }}
                @else
                    <p class="text-center py-8 text-slate-500">You haven't reported any incidents</p>
                @endif
            </div>

            <input type="radio" name="incident_tabs" class="tab" label="I'm Involved" />
            <div class="tab-content p-6 space-y-4">
                @if($myInvolvement->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="table-enterprise w-full">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Category</th>
                                    <th>Severity</th>
                                    <th>Status</th>
                                    <th>Reported By</th>
                                    <th>Station</th>
                                    <th>My Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($myInvolvement as $incident)
                                    <tr class="hover">
                                        <td class="text-sm">{{ $incident->time_of_incident->format('M d') }}</td>
                                        <td class="text-sm capitalize">{{ str_replace('_', ' ', $incident->incident_category) }}</td>
                                        <td>
                                            @if($incident->severity_level === 'Severe')
                                                <span class="badge-enterprise badge-error">{{ $incident->severity_level }}</span>
                                            @elseif($incident->severity_level === 'High')
                                                <span class="badge-enterprise badge-warning">{{ $incident->severity_level }}</span>
                                            @elseif($incident->severity_level === 'Moderate')
                                                <span class="badge-enterprise badge-info">{{ $incident->severity_level }}</span>
                                            @else
                                                <span class="badge-enterprise badge-success">{{ $incident->severity_level }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($incident->status === 'resolved')
                                                <span class="badge-enterprise badge-success">Resolved</span>
                                            @elseif($incident->status === 'investigating')
                                                <span class="badge-enterprise badge-warning">Investigating</span>
                                            @else
                                                <span class="badge-enterprise badge-error">Unresolved</span>
                                            @endif
                                        </td>
                                        <td class="text-sm">{{ $incident->createdBy->name }}</td>
                                        <td class="text-sm">{{ $incident->station->name }}</td>
                                        <td class="text-xs capitalize">
                                            @php
                                                $myRole = $incident->involvedStaff->where('id', auth()->id())->first()?->pivot->role_in_incident;
                                            @endphp
                                            {{ str_replace('_', ' ', $myRole ?? 'N/A') }}
                                        </td>
                                        <td>
                                            <a href="{{ route('nurse.incidents.show', $incident) }}" class="link link-primary text-xs">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $myInvolvement->links() }}
                @else
                    <p class="text-center py-8 text-slate-500">You're not involved in any reported incidents</p>
                @endif
            </div>
        </div>
    </div>

</div>

{{-- MODAL FOR INVOLVED STAFF --}}
<dialog id="involvedModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Staff Involved</h3>
        <div id="involvedStaffList"></div>
        <div class="modal-action">
            <form method="dialog">
                <button class="btn">Close</button>
            </form>
        </div>
    </div>
</dialog>

<style>
    .stat-card {
        @apply bg-gradient-to-br from-slate-50 to-slate-100 p-4 rounded-lg border border-slate-200;
    }

    .stat-card-error {
        @apply from-red-50 to-red-100 border-red-200;
    }

    .stat-card-warning {
        @apply from-amber-50 to-amber-100 border-amber-200;
    }

    .stat-label {
        @apply text-xs font-semibold text-slate-600 uppercase tracking-wide;
    }

    .stat-value {
        @apply text-3xl font-bold text-slate-800 my-2;
    }

    .stat-detail {
        @apply text-xs text-slate-500;
    }

    .table-enterprise {
        @apply border-collapse;
    }

    .table-enterprise thead tr {
        @apply border-b-2 border-slate-200;
    }

    .table-enterprise th {
        @apply text-left px-4 py-3 text-xs font-semibold text-slate-700 uppercase tracking-wide;
    }

    .table-enterprise tbody tr {
        @apply border-b border-slate-100 transition-colors;
    }

    .table-enterprise tbody tr.hover:hover {
        @apply bg-slate-50;
    }

    .table-enterprise td {
        @apply px-4 py-3;
    }

    .badge-enterprise {
        @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
    }

    .badge-success {
        @apply bg-emerald-100 text-emerald-800;
    }

    .badge-error {
        @apply bg-red-100 text-red-800;
    }

    .badge-warning {
        @apply bg-amber-100 text-amber-800;
    }

    .badge-info {
        @apply bg-blue-100 text-blue-800;
    }
</style>

<script>
    function showInvolved(incidentId) {
        // In a real app, you'd fetch the involved staff via AJAX
        document.getElementById('involvedModal').showModal();
    }
</script>
@endsection

