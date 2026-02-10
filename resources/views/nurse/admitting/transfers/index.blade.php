@extends('layouts.layout')

@section('content')
<div class="max-w-7xl mx-auto" x-data="transfersManager()">

    <div class="card-enterprise p-6">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Pending Transfers</h2>
                <p class="text-sm text-slate-500">Requests from Clinical Stations</p>
            </div>
        </div>

        <div class="overflow-x-auto">
                <table class="table-enterprise">
                    <thead>
                        <tr>
                            <th>Admission # | Type</th>
                            <th>Patient</th>
                            <th>Current Location</th>
                            <th></th>
                            <th>Requested Destination</th>
                            <th>Requested By</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                        <tr class="hover text-neutral">
                            <!-- Admission # | Type -->
                            <td class="text-base font-mono font-semibold text-slate-700">
                                {{ $req->admission->admission_number }} |
                                <div class="badge-enterprise bg-sky-50 text-sky-700 border border-sky-200 text-xs">{{ $req->admission->admission_type }}</div>
                            </td>

                            <!-- Patient -->
                            <td>
                                <div class="font-bold">{{ $req->admission->patient->last_name }}, {{ $req->admission->patient->first_name }}</div>
                                <div class="text-xs text-gray-500 font-mono">{{ $req->admission->patient->patient_unique_id }}</div>
                            </td>

                            <!-- FROM -->
                            <td>
                                <div class="font-mono font-bold text-lg">
                                    {{ $req->admission->bed?->bed_code ?? 'No Room' }}
                                </div>
                                @if(!$req->admission->bed)
                                    <div class="text-xs text-gray-400">Outpatient</div>
                                @endif
                            </td>

                            <!-- ARROW -->
                            <td class="text-center text-emerald-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </td>

                            <!-- TO (Target) -->
                            <td>
                                <div class="font-mono font-bold text-lg text-emerald-700">
                                    {{ $req->targetBed->bed_code }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $req->targetBed->room->room_type }}
                                </div>
                            </td>

                            <!-- Requestor -->
                            <td class="text-xs">
                                {{ $req->requestor->name }}
                                <br>
                                <button type="button" @click="openRemarksModal('{{ addslashes($req->remarks) }}')" class="text-xs underline text-emerald-600 hover:text-emerald-800 mt-1 cursor-pointer">
                                    View Remarks
                                </button>
                            </td>

                            <!-- Actions -->
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- DECLINE BUTTON (Modal Trigger) -->
                                    <button onclick="document.getElementById('decline_modal_{{ $req->id }}').showModal()"
                                            class="btn-enterprise-danger text-xs">
                                        Decline
                                    </button>

                                    <!-- APPROVE FORM -->
                                    <form action="{{ route('nurse.admitting.transfers.approve', $req->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-enterprise-primary text-xs" onclick="return confirm('Confirm transfer to {{ $req->targetBed->bed_code }}?')">
                                            Approve
                                        </button>
                                    </form>
                                </div>

                                <!-- DECLINE MODAL (Unique ID per row) -->
                                <dialog id="decline_modal_{{ $req->id }}" class="modal text-left">
                                    <div class="modal-enterprise">
                                        <h3 class="font-bold text-lg text-red-700">Decline Transfer</h3>
                                        <p class="py-4 text-sm text-slate-600">Please state the reason for rejection.</p>
                                        <form action="{{ route('nurse.admitting.transfers.decline', $req->id) }}" method="POST">
                                            @csrf
                                            <textarea name="remarks" class="textarea-enterprise w-full" placeholder="Reason (e.g. Bed unavailable)..." required></textarea>
                                            <div class="flex justify-end gap-3 mt-4">
                                                <button type="button" class="btn-enterprise-secondary" onclick="document.getElementById('decline_modal_{{ $req->id }}').close()">Cancel</button>
                                                <button type="submit" class="btn-enterprise-danger">Confirm Decline</button>
                                            </div>
                                        </form>
                                    </div>
                                </dialog>

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-gray-400 italic">
                                No pending transfer requests.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="p-4 border-t border-slate-200">
                {{ $requests->links() }}
            </div>

    </div>
</div>

<script>
function transfersManager() {
    return {
        remarksText: '',
        openRemarksModal(remarks) {
            document.getElementById('remarks_content').textContent = remarks;
            document.getElementById('remarks_modal').showModal();
        }
    }
}
</script>

<!-- REMARKS MODAL -->
<dialog id="remarks_modal" class="modal">
    <div class="modal-enterprise">
        <h3 class="font-bold text-lg text-slate-800 mb-4">Transfer Remarks</h3>
        <p id="remarks_content" class="text-sm text-slate-600"></p>
        <div class="flex justify-end mt-4">
            <form method="dialog">
                <button class="btn-enterprise-secondary">Close</button>
            </form>
        </div>
    </div>
</dialog>

@endsection
