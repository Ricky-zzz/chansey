<div x-show="viewNursePatientsOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
    <div @click.away="viewNursePatientsOpen = false" class="bg-white rounded-xl shadow-lg w-full max-w-4xl max-h-[85vh] flex flex-col mx-4">

        <!-- Header -->
        <div class="flex justify-between items-start px-6 py-4 border-b border-slate-200">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Assigned Patients</h3>
                <p class="text-sm text-slate-500 mt-0.5" x-text="selectedNurseForPatients?.name ?? ''"></p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Ratio Display -->
                <div class="text-center px-4 py-1 bg-emerald-50 border border-emerald-200 rounded-lg">
                    <div class="text-xl font-bold text-emerald-600" x-text="nursePatientsRatio"></div>
                    <p class="text-xs text-slate-500">Patient : Nurse</p>
                </div>
                <button @click="viewNursePatientsOpen = false" class="text-slate-400 hover:text-slate-600 p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="overflow-y-auto flex-1 p-6" x-show="!nursePatientsLoading">
            <div x-show="assignedPatients.length === 0" class="text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                <p class="text-slate-400 text-sm font-medium">No patients assigned yet</p>
            </div>
            <div x-show="assignedPatients.length > 0">
                <table class="table-enterprise w-full">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Patient ID</th>
                            <th>Acuity</th>
                            <th>Score</th>
                            <th>Notes</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="patient in assignedPatients" :key="patient.id">
                            <tr>
                                <td class="font-semibold text-slate-800" x-text="patient.patient_name"></td>
                                <td class="font-mono text-xs text-slate-500" x-text="patient.patient_id_code"></td>
                                <td>
                                    <span class="badge-enterprise text-white text-xs px-2 py-0.5"
                                        :style="'background-color: ' + getAcuityColor(patient.acuity)"
                                        x-text="patient.acuity"></span>
                                </td>
                                <td class="text-sm font-medium text-slate-600" x-text="patient.score"></td>
                                <td class="text-xs text-slate-500 italic max-w-[160px] truncate" x-text="patient.description || '—'"></td>
                                <td class="text-right">
                                    <div class="flex gap-1 justify-end">
                                        <button @click="editPatientAssignment(patient)"
                                            class="btn-enterprise-secondary text-xs px-2 py-1 inline-flex items-center gap-1 h-7 min-h-0">Edit</button>
                                        <button @click="removePatientAssignment(patient.id)"
                                            class="btn-enterprise-danger text-xs px-2 py-1 inline-flex items-center gap-1 h-7 min-h-0">Remove</button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="nursePatientsLoading" class="flex justify-center py-10">
            <span class="loading loading-spinner loading-md text-emerald-600"></span>
        </div>

        <!-- Footer -->
        <div class="px-6 py-3 border-t border-slate-200 flex justify-between items-center bg-slate-50 rounded-b-xl">
            <p class="text-xs text-slate-400">Click a row's Edit to update acuity or notes.</p>
            <button @click="openAssignPatientModal(selectedNurseForPatients)"
                class="btn-enterprise-primary text-xs px-3 py-1.5 inline-flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Assign Patient
            </button>
        </div>
    </div>
</div>
