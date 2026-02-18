<div x-show="viewNursePatientsOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
    <div @click.away="viewNursePatientsOpen = false" class="bg-white rounded-lg shadow-lg w-full md:w-2xl max-h-screen md:max-h-[500px] overflow-y-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-slate-800">Assigned Patients</h3>
            <button @click="viewNursePatientsOpen = false" class="text-slate-400 hover:text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div x-show="!nursePatientsLoading">
            <!-- Patient Ratio Display -->
            <div class="mb-4 text-center">
                <div class="text-3xl font-bold text-emerald-600" x-text="nursePatientsRatio"></div>
                <p class="text-sm text-slate-500">Patient-to-Nurse Ratio</p>
            </div>

            <div x-show="assignedPatients.length === 0" class="text-center py-4">
                <p class="text-slate-400 text-sm">No patients assigned yet</p>
            </div>

            <div x-show="assignedPatients.length > 0">
                <div class="space-y-3">
                    <template x-for="patient in assignedPatients" :key="patient.id">
                        <div class="flex justify-between items-start p-3 bg-slate-50 rounded-lg border-l-4" :style="'border-left-color: ' + getAcuityColor(patient.acuity)">
                            <div class="flex-1">
                                <p class="font-semibold text-slate-800" x-text="patient.patient_name"></p>
                                <p class="text-xs text-slate-500" x-text="'ID: ' + patient.patient_id_code"></p>
                                <div class="mt-2 flex gap-3 items-center">
                                    <span class="text-sm font-semibold" :style="'color: ' + getAcuityColor(patient.acuity)" x-text="patient.acuity"></span>
                                    <span class="text-sm text-slate-600" x-text="'Score: ' + patient.score"></span>
                                </div>
                                <p x-show="patient.description" class="text-xs text-slate-600 mt-2 italic" x-text="patient.description"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div x-show="nursePatientsLoading" class="flex justify-center py-4">
            <span class="loading loading-spinner loading-sm"></span>
        </div>
    </div>
</div>
