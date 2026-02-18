@extends('layouts.clinic')

@section('content')
<div class="max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="card-enterprise p-5 mb-6">
        <h2 class="text-xl font-bold text-slate-800">{{ $title }}</h2>
        <p class="text-sm text-slate-500 mt-0.5">Create endorsements for incoming nurses or view your assigned endorsements</p>
    </div>

    {{-- Tabs --}}
    <div class="tabs tabs-bordered card-enterprise rounded-none border-b-0">
        <input type="radio" name="endorsement_tabs" class="tab" aria-label="My Created Endorsements" checked="checked" />
        <div class="tab-content p-6">
            {{-- MY CREATED ENDORSEMENTS --}}
            <div>
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-2">Endorsements I've Created</h3>
                    <p class="text-sm text-slate-500">Drafts and submitted endorsements. Add notes to correct any errors.</p>
                </div>

                @if($myCreatedEndorsments->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-slate-600 font-medium">No endorsements created yet</p>
                        <p class="text-sm text-slate-500 mt-1">Create your first endorsement from a patient's chart</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="table-enterprise">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Incoming Nurse</th>
                                    <th>Created</th>
                                    <th>Status</th>
                                    <th>Views</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($myCreatedEndorsments as $endorsment)
                                <tr>
                                    {{-- PATIENT --}}
                                    <td>
                                        <div class="font-semibold text-slate-800">
                                            {{ $endorsment->admission->patient->first_name ?? 'N/A' }}
                                            {{ $endorsment->admission->patient->last_name ?? '' }}
                                        </div>
                                        <div class="text-xs text-slate-500">Adm: {{ $endorsment->admission->admission_number ?? 'N/A' }}</div>
                                    </td>

                                    {{-- INCOMING NURSE --}}
                                    <td>
                                        <div class="text-sm text-slate-700">{{ $endorsment->incomingNurse->user->name ?? 'N/A' }}</div>
                                    </td>

                                    {{-- CREATED DATE --}}
                                    <td>
                                        <div class="text-sm text-slate-700">{{ $endorsment->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-slate-500">{{ $endorsment->created_at->format('h:i A') }}</div>
                                    </td>

                                    {{-- STATUS --}}
                                    <td>
                                        @if($endorsment->isLocked())
                                            <span class="badge-enterprise bg-emerald-50 text-emerald-700 border border-emerald-200 gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                                Submitted
                                            </span>
                                        @else
                                            <span class="badge-enterprise bg-amber-50 text-amber-700 border border-amber-200 gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" clip-rule="evenodd" />
                                                </svg>
                                                Draft
                                            </span>
                                        @endif
                                    </td>

                                    {{-- VIEWS --}}
                                    <td>
                                        @if($endorsment->viewers->count() > 0)
                                            <button type="button"
                                                    class="badge-enterprise bg-blue-50 text-blue-700 border border-blue-200 cursor-pointer hover:bg-blue-100"
                                                    onclick="openViewersModal({{ $endorsment->id }}, {{ json_encode($endorsment->viewers->map(fn($v) => ['name' => $v->user->name ?? 'Unknown', 'viewed_at' => $v->viewed_at->format('M d, Y h:i A')])->values()) }})">
                                                Viewed {{ $endorsment->viewers->count() }}x
                                            </button>
                                        @else
                                            <span class="text-xs text-slate-400">Not viewed</span>
                                        @endif
                                    </td>

                                    {{-- ACTIONS --}}
                                    <td class="text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('nurse.clinical.endorsments.show', $endorsment->id) }}"
                                               class="btn btn-sm btn-ghost text-sky-600 hover:bg-sky-50 normal-case h-8 min-h-0"
                                               title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION --}}
                    <div class="mt-4">
                        {{ $myCreatedEndorsments->links() }}
                    </div>
                @endif
            </div>
        </div>

        <input type="radio" name="endorsement_tabs" class="tab" aria-label="My Incoming Endorsements" />
        <div class="tab-content p-6">
            {{-- MY INCOMING ENDORSEMENTS --}}
            <div>
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-2">Endorsements for Me</h3>
                    <p class="text-sm text-slate-500">Submitted endorsements assigned to you. View and add observations.</p>
                </div>

                @if($myIncomingEndorsments->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-slate-600 font-medium">No incoming endorsements</p>
                        <p class="text-sm text-slate-500 mt-1">You'll see endorsements here when assigned to receive them</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="table-enterprise">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>From Nurse</th>
                                    <th>Submitted</th>
                                    <th>Viewed</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($myIncomingEndorsments as $endorsment)
                                <tr>
                                    {{-- PATIENT --}}
                                    <td>
                                        <div class="font-semibold text-slate-800">
                                            {{ $endorsment->admission->patient->first_name ?? 'N/A' }}
                                            {{ $endorsment->admission->patient->last_name ?? '' }}
                                        </div>
                                        <div class="text-xs text-slate-500">Adm: {{ $endorsment->admission->admission_number ?? 'N/A' }}</div>
                                    </td>

                                    {{-- FROM NURSE --}}
                                    <td>
                                        <div class="text-sm text-slate-700">{{ $endorsment->outgoingNurse->name ?? 'N/A' }}</div>
                                    </td>

                                    {{-- SUBMITTED DATE --}}
                                    <td>
                                        <div class="text-sm text-slate-700">{{ $endorsment->submitted_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-slate-500">{{ $endorsment->submitted_at->format('h:i A') }}</div>
                                    </td>

                                    {{-- VIEWED --}}
                                    <td>
                                        @if($endorsment->viewers->count() > 0)
                                            <span class="badge-enterprise bg-blue-50 text-blue-700 border border-blue-200">
                                                Viewed {{ $endorsment->viewers->count() }}x
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400">Not viewed</span>
                                        @endif
                                    </td>

                                    {{-- ACTIONS --}}
                                    <td class="text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('nurse.clinical.endorsments.show', $endorsment->id) }}"
                                               class="btn btn-sm btn-ghost text-sky-600 hover:bg-sky-50 normal-case h-8 min-h-0"
                                               title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION --}}
                    <div class="mt-4">
                        {{ $myIncomingEndorsments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

<!-- VIEWERS MODAL -->
<dialog id="viewers_modal" class="modal">
    <div class="modal-box modal-enterprise w-11/12 max-w-lg">
        <h3 class="font-semibold text-lg text-slate-800 mb-4">Who Viewed This Endorsement</h3>

        <div class="overflow-x-auto max-h-96">
            <table class="table-enterprise table-sm">
                <thead>
                    <tr>
                        <th>Viewer Name</th>
                        <th>Viewed At</th>
                    </tr>
                </thead>
                <tbody id="viewers_tbody">
                    <!-- Populated by JavaScript -->
                </tbody>
            </table>
        </div>

        <div class="modal-action">
            <button type="button" class="btn-enterprise-secondary" onclick="viewers_modal.close()">Close</button>
        </div>
    </div>
</dialog>

<script>
function openViewersModal(endorsmentId, viewers) {
    // Populate the table
    const tbody = document.getElementById('viewers_tbody');
    tbody.innerHTML = '';

    viewers.forEach(viewer => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="text-sm text-slate-700">${viewer.name}</td>
            <td class="text-sm text-slate-700">${viewer.viewed_at}</td>
        `;
        tbody.appendChild(tr);
    });

    // Open modal
    document.getElementById('viewers_modal').showModal();
}
</script>

@endsection
