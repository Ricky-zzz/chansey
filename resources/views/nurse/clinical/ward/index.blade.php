@extends('layouts.clinic')

@section('content')
<div class="max-w-7xl mx-auto" x-data="patientLoadManager()">

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
                            @php $nurseCount = $admission->patient->patientLoads->count(); @endphp
                            @if($nurseCount > 0)
                                <button @click="viewNurses({{ $admission->patient->id }}, '{{ $admission->patient->getFullNameAttribute() }}')"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold hover:bg-emerald-100 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0" /></svg>
                                    {{ $nurseCount }} {{ Str::plural('Nurse', $nurseCount) }}
                                </button>
                            @else
                                <button @click="viewNurses({{ $admission->patient->id }}, '{{ $admission->patient->getFullNameAttribute() }}')"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200 text-slate-400 text-xs italic hover:bg-slate-100 hover:text-slate-500 transition-colors">
                                    No nurses assigned
                                </button>
                            @endif
                        </td>
                        @endif

                        <!-- {{ $isHeadNurse ? '6' : '5' }}. ACTION -->
                        <td class="text-right">
                            <a href="{{ route('nurse.clinical.ward.show', $admission->id) }}"
                               class="btn-enterprise-primary gap-2 text-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                Open Chart
                            </a>
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
    <div @keydown.escape="closeAllModals">

        <!-- VIEW NURSES MODAL -->
        <div x-show="viewNursesOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
            <div @click.away="viewNursesOpen = false" class="bg-white rounded-xl shadow-lg w-full max-w-3xl max-h-[85vh] flex flex-col mx-4">
                <!-- Header -->
                <div class="flex justify-between items-center px-6 py-4 border-b border-slate-200">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Assigned Nurses</h3>
                        <p class="text-sm text-slate-500 mt-0.5" x-text="selectedPatientName"></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="assignNurse(selectedPatientId, selectedPatientName)" class="btn-enterprise-primary text-xs px-3 py-1.5 inline-flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            Assign Nurse
                        </button>
                        <button @click="viewNursesOpen = false" class="text-slate-400 hover:text-slate-600 p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="overflow-y-auto flex-1 p-6" x-show="!nursesLoading">
                    <div x-show="assignedNurses.length === 0" class="text-center py-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0" /></svg>
                        <p class="text-slate-400 text-sm font-medium">No nurses assigned yet</p>
                    </div>
                    <div x-show="assignedNurses.length > 0">
                        <table class="table-enterprise w-full">
                            <thead>
                                <tr>
                                    <th>Nurse</th>
                                    <th>Employee ID</th>
                                    <th>Acuity</th>
                                    <th>Score</th>
                                    <th>Notes</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="nurse in assignedNurses" :key="nurse.id">
                                    <tr>
                                        <td class="font-semibold text-slate-800" x-text="nurse.nurse_name"></td>
                                        <td class="font-mono text-xs text-slate-500" x-text="nurse.employee_id"></td>
                                        <td>
                                            <span class="badge-enterprise text-white text-xs px-2 py-0.5" :style="'background-color: ' + getAcuityColor(nurse.acuity)" x-text="nurse.acuity"></span>
                                        </td>
                                        <td class="text-sm text-slate-600 font-medium" x-text="nurse.score"></td>
                                        <td class="text-xs text-slate-500 italic max-w-[150px] truncate" x-text="nurse.description || '—'"></td>
                                        <td class="text-right">
                                            <div class="flex gap-1 justify-end">
                                                <button @click="editNurseAssignment(nurse)" class="btn-enterprise-secondary text-xs px-2 py-1 inline-flex items-center gap-1 h-7 min-h-0">Edit</button>
                                                <button @click="removeNurseAssignment(nurse.id)" class="btn-enterprise-danger text-xs px-2 py-1 inline-flex items-center gap-1 h-7 min-h-0">Remove</button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div x-show="nursesLoading" class="flex justify-center py-10">
                    <span class="loading loading-spinner loading-md text-emerald-600"></span>
                </div>
            </div>
        </div>

        <!-- ASSIGN NURSE MODAL -->
        <div x-show="assignNurseOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
            <div @click.away="assignNurseOpen = false" class="bg-white rounded-xl shadow-lg w-full max-w-xl mx-4">
                <!-- Header -->
                <div class="flex justify-between items-center px-6 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800">
                        <span x-show="!editingId">Assign Nurse to Patient</span>
                        <span x-show="editingId">Update Nurse Assignment</span>
                    </h3>
                    <button @click="closeAllModals" class="text-slate-400 hover:text-slate-600 p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitPatientLoad" class="p-6 space-y-4">
                    <!-- Patient Names (display only) -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Patient</label>
                        <input type="text" :value="selectedPatientName" disabled class="input-enterprise w-full bg-slate-50 text-slate-600" />
                    </div>

                    <!-- Nurse Dropdown (only show when creating) -->
                    <div x-show="!editingId">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nurse <span class="text-red-500">*</span></label>
                        <select x-model="formData.nurse_id" class="select-enterprise w-full" required>
                            <option value="">Select a nurse...</option>
                            <template x-for="n in availableNurses" :key="n.id">
                                <option :value="n.id" x-text="n.last_name + ', ' + n.first_name + ' (' + n.employee_id + ')'"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Nurse Display (only show when editing) -->
                    <div x-show="editingId">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nurse</label>
                        <input type="text" :value="editingNurseName" disabled class="input-enterprise w-full bg-slate-50 text-slate-600" />
                    </div>

                    <!-- Acuity Level -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Acuity Level <span class="text-red-500">*</span></label>
                        <select x-model="formData.acuity" class="select-enterprise w-full" required>
                            <option value="">Select acuity...</option>
                            <option value="Severe">Severe — Score: 4</option>
                            <option value="High">High — Score: 3</option>
                            <option value="Moderate">Moderate — Score: 2</option>
                            <option value="Low">Low — Score: 1</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Additional Notes</label>
                        <textarea x-model="formData.description" class="textarea-enterprise w-full resize-none" rows="4"
                            placeholder="Any additional notes or care instructions..."></textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2 pt-1">
                        <button type="submit" class="btn-enterprise-primary flex-1" :disabled="submitting">
                            <span x-show="!submitting">Save Assignment</span>
                            <span x-show="submitting" class="flex items-center justify-center gap-2"><span class="loading loading-spinner loading-sm"></span>Saving...</span>
                        </button>
                        <button type="button" @click="closeAllModals" class="btn-enterprise-secondary flex-1">Cancel</button>
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

        viewNurses(patientId, patientName) {
            this.selectedPatientId = patientId;
            this.selectedPatientName = patientName;
            this.nursesLoading = true;
            this.viewNursesOpen = true;

            fetch(`{{ route('nurse.headnurse.patient-loads.getNurses', 0) }}`.replace('/0', `/${patientId}`))
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

            fetch(`{{ route('nurse.headnurse.patient-loads.destroy', 0) }}`.replace('/0', `/${assignmentId}`), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                this.showToast(data.message, 'success');
                this.viewNurses(this.selectedPatientId, this.selectedPatientName);
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
                ? `{{ route('nurse.headnurse.patient-loads.update', 0) }}`.replace('/0', `/${this.editingId}`)
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
                // Refresh the page to see updates
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
            const toastClass = type === 'success' ? 'toast-enterprise-success' : 'toast-enterprise-error';
            const titleText = type === 'success' ? 'Success!' : 'Error!';
            const iconColor = type === 'success' ? 'text-emerald-600' : 'text-red-600';
            const successIcon = type === 'success'
                ? '<svg xmlns="http://www.w3.org/2000/svg" class="shrink-0 h-5 w-5 ' + iconColor + '" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                : '<svg xmlns="http://www.w3.org/2000/svg" class="shrink-0 h-5 w-5 ' + iconColor + '" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m8-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
            const closeIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
            const closeColor = type === 'success' ? 'text-emerald-500 hover:text-emerald-700' : 'text-red-500 hover:text-red-700';

            const toastContainer = document.createElement('div');
            toastContainer.className = 'toast toast-top toast-end z-50';

            const toastContent = document.createElement('div');
            toastContent.className = `${toastClass} flex items-center gap-3 px-4 py-3`;
            toastContent.innerHTML = `
                ${successIcon}
                <div class="text-sm">
                    <span class="font-semibold">${titleText}</span> ${message}
                </div>
                <button class="ml-2 ${closeColor}">${closeIcon}</button>
            `;

            toastContainer.appendChild(toastContent);
            document.body.appendChild(toastContainer);

            const closeBtn = toastContent.querySelector('button');
            closeBtn.addEventListener('click', () => toastContainer.remove());

            setTimeout(() => toastContainer.remove(), 3000);
        }
    };
}
@endif
</script>
@endsection
