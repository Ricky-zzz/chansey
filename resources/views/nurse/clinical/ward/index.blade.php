@extends('layouts.clinic')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- PATIENT LIST -->
    <div class="card-enterprise p-6">
        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Station Roster</h2>
                <p class="text-sm text-slate-500 font-medium mt-0.5">
                    {{ $nurse->station->station_name ?? 'Unassigned Station' }}
                </p>
            </div>

            <!-- SEARCH -->
            <form action="{{ route('nurse.clinical.ward.index') }}" method="GET" class="flex w-full md:w-96">
                <input type="text" name="search" class="input-enterprise join-item w-full rounded-r-none"
                       placeholder="Find Patient or Bed..."
                       value="{{ request('search') }}">
                <button type="submit" class="btn-enterprise-primary rounded-l-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </button>
            </form>
        </div>

        <div class="border-t border-slate-200 pt-5"></div>

        <div class="overflow-x-auto">
            <table class="table-enterprise">
                <!-- Head -->
                <thead>
                    <tr>
                        <th class="w-32">Bed / Room</th>
                        <th>Patient Identity</th>
                        <th>Physician</th>
                        <th>Clinical Snapshot</th>
                        @if($isHeadNurse)
                        <th>Assigned Nurses</th>
                        @endif
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <!-- Body -->
                <tbody>
                    @forelse($patients as $admission)
                    <tr>

                        <!-- 1. LOCATION -->
                        <td>
                            <div class="flex flex-col">
                                <span class="font-mono font-bold text-sm text-emerald-700">
                                    @if($admission->bed?->bed_code)
                                    {{ $admission->bed->bed_code }}
                                    @else
                                    <span class="italic text-slate-400">Outpatient</span>
                                    @endif
                                </span>
                                <span class="text-xs text-slate-500">
                                    {{ $admission->bed?->room->room_type ?? 'Waiting Area' }}
                                </span>
                            </div>
                        </td>

                        <!-- 2. PATIENT -->
                        <td>
                            <div class="font-semibold text-sm text-slate-800">
                                {{ $admission->patient->getFullNameAttribute() }}
                            </div>
                            <div class="font-medium text-xs text-emerald-600">
                                {{ $admission->patient->patient_unique_id }}
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ $admission->patient->sex }} &middot;
                                {{ $admission->patient->getAgeAttribute() }} yrs old
                            </div>
                        </td>

                        <!-- 3. DOCTOR -->
                        <td>
                            <div class="font-medium text-sm text-slate-700">
                                Dr. {{ $admission->attendingPhysician->getFullNameAttribute() }}
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ $admission->attendingPhysician->specialization }}
                            </div>
                        </td>

                        <!-- 4. SNAPSHOT -->
                        <td class="max-w-xs">
                            <!-- Show Tags if Emergency -->
                            @if($admission->admission_type === 'Emergency')
                                <span class="badge badge-error badge-xs text-white mb-1">Emergency</span>
                            @endif

                            <div class="text-sm font-medium text-slate-600 truncate">
                                {{ $admission->initial_diagnosis ?? 'Pending Diagnosis' }}
                            </div>
                            <div class="text-xs text-slate-400 italic truncate">
                                "{{ $admission->truncatedChiefComplaint() }}"
                            </div>
                        </td>

                        @if($isHeadNurse)
                        <!-- 5. ASSIGNED NURSES -->
                        <td>
                            @forelse($admission->patient->patientLoads as $load)
                                <div class="flex items-center gap-2 mb-2 text-xs">
                                    <span class="font-medium text-slate-700">
                                        {{ $load->nurse->first_name }} {{ $load->nurse->last_name }}
                                    </span>
                                    <span class="badge badge-sm" style="background-color:
                                        @if($load->acuity->value === 'Severe') #dc2626
                                        @elseif($load->acuity->value === 'High') #f59e0b
                                        @elseif($load->acuity->value === 'Moderate') #0ea5e9
                                        @else #10b981
                                        @endif;
                                        color: white;">
                                        {{ $load->acuity->value }}
                                    </span>
                                    <span class="text-slate-500">{{ $load->score }}</span>
                                </div>
                            @empty
                                <span class="italic text-slate-400 text-xs">No nurses assigned</span>
                            @endforelse
                            @if($admission->patient->patientLoads->count() > 0 || true)
                                <button @click="viewNurses({{ $admission->patient->id }})"
                                    class="btn btn-xs btn-ghost mt-1">
                                    View Details
                                </button>
                            @endif
                        </td>
                        @endif

                        <!-- {{ $isHeadNurse ? '6' : '5' }}. ACTION -->
                        <td class="text-right">
                            <div class="flex flex-col gap-2 items-end">
                                <a href="{{ route('nurse.clinical.ward.show', $admission->id) }}"
                                   class="btn-enterprise-primary gap-2 text-xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                    Open Chart
                                </a>
                                @if($isHeadNurse)
                                <button @click="assignNurse({{ $admission->patient->id }}, '{{ $admission->patient->getFullNameAttribute() }}')"
                                    class="btn btn-outline btn-sm gap-2 text-xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                    Assign Nurse
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $isHeadNurse ? '6' : '5' }}" class="text-center py-12">
                            <div class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                <span class="text-slate-400 font-medium">No active patients found.</span>
                                <span class="text-slate-400 text-sm mt-1">Your station is currently empty.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="p-4 border-t border-slate-200">
            {{ $patients->links() }}
        </div>
    </div>

    @if($isHeadNurse)
    <!-- PATIENT LOAD MANAGER -->
    <div x-data="patientLoadManager()" @keydown.escape="closeAllModals">

        <!-- VIEW NURSES MODAL -->
        <div x-show="viewNursesOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
            <div @click.away="viewNursesOpen = false" class="bg-white rounded-lg shadow-lg w-96 max-h-96 overflow-y-auto p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-slate-800">Assigned Nurses</h3>
                    <button @click="viewNursesOpen = false" class="text-slate-400 hover:text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div x-show="!nursesLoading">
                    <div x-show="assignedNurses.length === 0" class="text-center py-4">
                        <p class="text-slate-400 text-sm">No nurses assigned yet</p>
                    </div>
                    <div x-show="assignedNurses.length > 0">
                        <div class="space-y-3">
                            <template x-for="nurse in assignedNurses" :key="nurse.id">
                                <div class="flex justify-between items-start p-3 bg-slate-50 rounded-lg">
                                    <div class="flex-1">
                                        <p class="font-semibold text-slate-800" x-text="nurse.nurse_name"></p>
                                        <p class="text-xs text-slate-500" x-text="'ID: ' + nurse.employee_id"></p>
                                        <div class="mt-2 flex gap-2">
                                            <span class="badge badge-sm" :style="'background-color: ' + getAcuityColor(nurse.acuity)" x-text="nurse.acuity"></span>
                                            <span class="badge badge-sm badge-ghost" x-text="'Score: ' + nurse.score"></span>
                                        </div>
                                        <p x-show="nurse.description" class="text-xs text-slate-600 mt-2 italic" x-text="nurse.description"></p>
                                    </div>
                                    <div class="flex gap-1 ml-2">
                                        <button @click="editNurseAssignment(nurse)" class="btn btn-xs btn-ghost">Edit</button>
                                        <button @click="removeNurseAssignment(nurse.id)" class="btn btn-xs btn-ghost btn-error">Remove</button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div x-show="nursesLoading" class="flex justify-center py-4">
                    <span class="loading loading-spinner loading-sm"></span>
                </div>
            </div>
        </div>

        <!-- ASSIGN NURSE MODAL -->
        <div x-show="assignNurseOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
            <div @click.away="assignNurseOpen = false" class="bg-white rounded-lg shadow-lg w-96 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-slate-800">
                        <span x-show="!editingId">Assign Nurse to Patient</span>
                        <span x-show="editingId">Update Nurse Assignment</span>
                    </h3>
                    <button @click="closeAllModals" class="text-slate-400 hover:text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitPatientLoad">
                    <!-- Patient Names (display only) -->
                    <div class="form-control mb-4">
                        <label class="label"><span class="label-text font-semibold">Patient</span></label>
                        <input type="text" :value="selectedPatientName" disabled class="input input-bordered bg-slate-100" />
                    </div>

                    <!-- Nurse Dropdown (only show when creating) -->
                    <div class="form-control mb-4" x-show="!editingId">
                        <label class="label"><span class="label-text font-semibold">Nurse <span class="text-error">*</span></span></label>
                        <select x-model="formData.nurse_id" class="select select-bordered" required>
                            <option value="">Select a nurse...</option>
                            <template x-for="n in availableNurses" :key="n.id">
                                <option :value="n.id" x-text="n.first_name + ' ' + n.last_name + ' (' + n.employee_id + ')'"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Nurse Display (only show when editing) -->
                    <div class="form-control mb-4" x-show="editingId">
                        <label class="label"><span class="label-text font-semibold">Nurse</span></label>
                        <input type="text" :value="editingNurseName" disabled class="input input-bordered bg-slate-100" />
                    </div>

                    <!-- Acuity Level -->
                    <div class="form-control mb-4">
                        <label class="label"><span class="label-text font-semibold">Acuity Level <span class="text-error">*</span></span></label>
                        <select x-model="formData.acuity" class="select select-bordered" required>
                            <option value="">Select acuity...</option>
                            <option value="Severe">Severe (Score: 4)</option>
                            <option value="High">High (Score: 3)</option>
                            <option value="Moderate">Moderate (Score: 2)</option>
                            <option value="Low">Low (Score: 1)</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div class="form-control mb-4">
                        <label class="label"><span class="label-text font-semibold">Description (Additional Notes)</span></label>
                        <textarea x-model="formData.description" class="textarea textarea-bordered"
                            placeholder="Any additional notes or instructions..."></textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2 pt-4">
                        <button type="submit" class="btn btn-primary flex-1" :disabled="submitting">
                            <span x-show="!submitting">Save Assignment</span>
                            <span x-show="submitting"><span class="loading loading-spinner loading-sm"></span>Saving...</span>
                        </button>
                        <button type="button" @click="closeAllModals" class="btn btn-ghost flex-1">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
    @endif
</div>

<script>
@if($isHeadNurse)
function patientLoadManager() {
    return {
        viewNursesOpen: false,
        assignNurseOpen: false,
        nursesLoading: false,
        submitting: false,

        selectedPatientId: null,
        selectedPatientName: '',
        assignedNurses: [],

        editingId: null,
        editingNurseName: '',

        formData: {
            patient_id: null,
            nurse_id: '',
            acuity: '',
            description: '',
        },

        availableNurses: @json($availableNurses),

        closeAllModals() {
            this.viewNursesOpen = false;
            this.assignNurseOpen = false;
            this.resetForm();
        },

        viewNurses(patientId) {
            this.selectedPatientId = patientId;
            this.nursesLoading = true;
            this.viewNursesOpen = true;

            fetch(`{{ route('nurse.headnurse.patient-loads.getNurses', '') }}/${patientId}`)
                .then(response => response.json())
                .then(data => {
                    this.assignedNurses = data.nurses;
                    this.nursesLoading = false;
                })
                .catch(error => {
                    console.error('Error fetching nurses:', error);
                    this.nursesLoading = false;
                    this.showToast('Error loading nurses', 'error');
                });
        },

        assignNurse(patientId, patientName) {
            this.selectedPatientId = patientId;
            this.selectedPatientName = patientName;
            this.formData.patient_id = patientId;
            this.editingId = null;
            this.editingNurseName = '';
            this.assignNurseOpen = true;
        },

        editNurseAssignment(nurse) {
            this.editingId = nurse.id;
            this.editingNurseName = nurse.nurse_name;
            this.formData.nurse_id = nurse.nurse_id;
            this.formData.acuity = nurse.acuity;
            this.formData.description = nurse.description || '';
            this.viewNursesOpen = false;
            this.assignNurseOpen = true;
        },

        removeNurseAssignment(assignmentId) {
            if (!confirm('Are you sure you want to remove this assignment?')) return;

            fetch(`{{ route('nurse.headnurse.patient-loads.destroy', '') }}/${assignmentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                this.showToast(data.message, 'success');
                this.viewNurses(this.selectedPatientId);
            })
            .catch(error => {
                console.error('Error removing assignment:', error);
                this.showToast('Error removing assignment', 'error');
            });
        },

        submitPatientLoad() {
            this.submitting = true;

            const isEdit = this.editingId !== null;
            const method = isEdit ? 'PUT' : 'POST';
            const url = isEdit
                ? `{{ route('nurse.headnurse.patient-loads.update', '') }}/${this.editingId}`
                : `{{ route('nurse.headnurse.patient-loads.store') }}`;

            const payload = {
                patient_id: this.formData.patient_id,
                ...(!isEdit && { nurse_id: this.formData.nurse_id }),
                acuity: this.formData.acuity,
                description: this.formData.description,
            };

            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload),
            })
            .then(response => response.json())
            .then(data => {
                this.submitting = false;
                this.showToast(data.message, 'success');
                this.closeAllModals();
                // Refresh the page or reload the nurses list
                location.reload();
            })
            .catch(error => {
                console.error('Error submitting form:', error);
                this.submitting = false;
                this.showToast('Error saving assignment', 'error');
            });
        },

        resetForm() {
            this.formData = {
                patient_id: null,
                nurse_id: '',
                acuity: '',
                description: '',
            };
            this.editingId = null;
            this.editingNurseName = '';
        },

        getAcuityColor(acuity) {
            const colors = {
                'Severe': '#dc2626',
                'High': '#f59e0b',
                'Moderate': '#0ea5e9',
                'Low': '#10b981',
            };
            return colors[acuity] || '#6b7280';
        },

        showToast(message, type = 'success') {
            const bgColor = type === 'success' ? 'bg-emerald-500' : 'bg-red-500';
            const icon = type === 'success'
                ? '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>'
                : '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>';

            const toast = document.createElement('div');
            toast.className = `alert alert-${type === 'success' ? 'success' : 'error'} flex items-center gap-3 fixed bottom-4 right-4 max-w-sm z-50`;
            toast.innerHTML = `${icon}<span>${message}</span>`;
            document.body.appendChild(toast);

            setTimeout(() => toast.remove(), 3000);
        }
    };
}
@endif
</script>
@endsection
