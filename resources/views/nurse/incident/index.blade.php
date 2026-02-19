@extends('layouts.clinic')

@section('content')
<div class="max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="card-enterprise p-5 mb-6">
        <h2 class="text-xl font-bold text-slate-800">{{ $title }}</h2>
        <p class="text-sm text-slate-500 mt-0.5">Report and track safety incidents for {{ $station->station_name ?? 'your station' }}</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="stat-card">
            <div class="stat-title text-xs text-slate-500">Total Incidents</div>
            <div class="stat-value text-2xl text-slate-800">{{ $stats['total'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title text-xs text-slate-500">Unresolved</div>
            <div class="stat-value text-2xl text-red-700">{{ $stats['unresolved'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title text-xs text-slate-500">Investigating</div>
            <div class="stat-value text-2xl text-amber-700">{{ $stats['investigating'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title text-xs text-slate-500">My Involvement</div>
            <div class="stat-value text-2xl text-sky-700">{{ $stats['myInvolvement'] }}</div>
        </div>
    </div>

    {{-- Create Report Button --}}
    <div class="mb-6">
        <a href="{{ route('incident.create') }}" class="btn-enterprise-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Report New Incident
        </a>
    </div>

    {{-- Tabs --}}
    <div class="tabs tabs-bordered card-enterprise rounded-none border-b-0">
        <input type="radio" name="incident_tabs" class="tab" aria-label="All Incidents" checked="checked" />
        <div class="tab-content p-6">
            {{-- ALL INCIDENTS --}}
            @if($allIncidents->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                    </svg>
                    <p class="text-slate-600 font-medium">No incidents recorded</p>
                    <p class="text-sm text-slate-500 mt-1">All incidents in your station will appear here</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table-enterprise">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Severity</th>
                                <th>Status</th>
                                <th>Reported By</th>
                                <th>Involved</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allIncidents as $incident)
                            <tr>
                                <td>
                                    <div class="text-sm text-slate-700">{{ $incident->time_of_incident->format('M d, Y') }}</div>
                                    <div class="text-xs text-slate-500">{{ $incident->time_of_incident->format('h:i A') }}</div>
                                </td>
                                <td>
                                    <span class="text-sm text-slate-700 capitalize">{{ str_replace('_', ' ', $incident->incident_category) }}</span>
                                </td>
                                <td>
                                    <span class="badge-enterprise {{ match($incident->severity_level) {
                                        'Severe' => 'bg-red-100 text-red-700 border-red-200',
                                        'High' => 'bg-orange-100 text-orange-700 border-orange-200',
                                        'Moderate' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'Low' => 'bg-green-100 text-green-700 border-green-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200'
                                    } }}">
                                        {{ $incident->severity_level }}
                                    </span>
                                </td>
                                <td>
                                    @if($incident->isResolved())
                                        <span class="badge-enterprise bg-emerald-50 text-emerald-700 border-emerald-200">Resolved</span>
                                    @elseif($incident->isUnderInvestigation())
                                        <span class="badge-enterprise bg-amber-50 text-amber-700 border-amber-200">Investigating</span>
                                    @else
                                        <span class="badge-enterprise bg-red-50 text-red-700 border-red-200">Unresolved</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-sm text-slate-700">{{ $incident->createdBy->name ?? 'Unknown' }}</div>
                                </td>
                                <td>
                                    <span class="text-sm font-medium text-slate-700">{{ $incident->getInvolvedStaffCount() }} staff</span>
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('incident.show', $incident) }}" class="btn btn-sm btn-ghost text-sky-600 hover:bg-sky-50 normal-case h-8 min-h-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $allIncidents->links() }}
                </div>
            @endif
        </div>

        <input type="radio" name="incident_tabs" class="tab" aria-label="Reported by Me" />
        <div class="tab-content p-6">
            {{-- REPORTED BY ME --}}
            @if($myReports->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                    </svg>
                    <p class="text-slate-600 font-medium">No reports yet</p>
                    <p class="text-sm text-slate-500 mt-1">Incidents you report will appear here</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table-enterprise">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Severity</th>
                                <th>Status</th>
                                <th>Involved</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($myReports as $incident)
                            <tr>
                                <td>
                                    <div class="text-sm text-slate-700">{{ $incident->time_of_incident->format('M d, Y') }}</div>
                                    <div class="text-xs text-slate-500">{{ $incident->time_of_incident->format('h:i A') }}</div>
                                </td>
                                <td>
                                    <span class="text-sm text-slate-700 capitalize">{{ str_replace('_', ' ', $incident->incident_category) }}</span>
                                </td>
                                <td>
                                    <span class="badge-enterprise {{ match($incident->severity_level) {
                                        'Severe' => 'bg-red-100 text-red-700 border-red-200',
                                        'High' => 'bg-orange-100 text-orange-700 border-orange-200',
                                        'Moderate' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'Low' => 'bg-green-100 text-green-700 border-green-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200'
                                    } }}">
                                        {{ $incident->severity_level }}
                                    </span>
                                </td>
                                <td>
                                    @if($incident->isResolved())
                                        <span class="badge-enterprise bg-emerald-50 text-emerald-700 border-emerald-200">Resolved</span>
                                    @elseif($incident->isUnderInvestigation())
                                        <span class="badge-enterprise bg-amber-50 text-amber-700 border-amber-200">Investigating</span>
                                    @else
                                        <span class="badge-enterprise bg-red-50 text-red-700 border-red-200">Unresolved</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-sm font-medium text-slate-700">{{ $incident->getInvolvedStaffCount() }} staff</span>
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('incident.show', $incident) }}" class="btn btn-sm btn-ghost text-sky-600 hover:bg-sky-50 normal-case h-8 min-h-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $myReports->links() }}
                </div>
            @endif
        </div>

        <input type="radio" name="incident_tabs" class="tab" aria-label="I'm Involved" />
        <div class="tab-content p-6">
            {{-- I'M INVOLVED --}}
            @if($myInvolvement->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                    </svg>
                    <p class="text-slate-600 font-medium">Not involved in any incidents</p>
                    <p class="text-sm text-slate-500 mt-1">Incidents where you're listed as involved will appear here</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table-enterprise">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Severity</th>
                                <th>Status</th>
                                <th>Reported By</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($myInvolvement as $incident)
                            <tr>
                                <td>
                                    <div class="text-sm text-slate-700">{{ $incident->time_of_incident->format('M d, Y') }}</div>
                                    <div class="text-xs text-slate-500">{{ $incident->time_of_incident->format('h:i A') }}</div>
                                </td>
                                <td>
                                    <span class="text-sm text-slate-700 capitalize">{{ str_replace('_', ' ', $incident->incident_category) }}</span>
                                </td>
                                <td>
                                    <span class="badge-enterprise {{ match($incident->severity_level) {
                                        'Severe' => 'bg-red-100 text-red-700 border-red-200',
                                        'High' => 'bg-orange-100 text-orange-700 border-orange-200',
                                        'Moderate' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'Low' => 'bg-green-100 text-green-700 border-green-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200'
                                    } }}">
                                        {{ $incident->severity_level }}
                                    </span>
                                </td>
                                <td>
                                    @if($incident->isResolved())
                                        <span class="badge-enterprise bg-emerald-50 text-emerald-700 border-emerald-200">Resolved</span>
                                    @elseif($incident->isUnderInvestigation())
                                        <span class="badge-enterprise bg-amber-50 text-amber-700 border-amber-200">Investigating</span>
                                    @else
                                        <span class="badge-enterprise bg-red-50 text-red-700 border-red-200">Unresolved</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-sm text-slate-700">{{ $incident->createdBy->name ?? 'Unknown' }}</div>
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('incident.show', $incident) }}" class="btn btn-sm btn-ghost text-sky-600 hover:bg-sky-50 normal-case h-8 min-h-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $myInvolvement->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

