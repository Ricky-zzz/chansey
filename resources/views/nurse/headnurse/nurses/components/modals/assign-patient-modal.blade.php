{{-- Assign Patient to Nurse Modal --}}
<div x-show="assignPatientOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
    <div @click.away="assignPatientOpen = false" class="bg-white rounded-xl shadow-lg w-full max-w-xl mx-4">

        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b border-slate-200">
            <h3 class="text-lg font-bold text-slate-800">
                <span x-show="!editingPatientLoadId">Assign Patient to Nurse</span>
                <span x-show="editingPatientLoadId">Update Patient Assignment</span>
            </h3>
            <button @click="closeAssignPatientModal" class="text-slate-400 hover:text-slate-600 p-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form @submit.prevent="submitPatientLoadFromNurse" class="p-6 space-y-4">
            <!-- Nurse (display only) -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nurse</label>
                <input type="text" :value="selectedNurseForPatients?.name ?? ''" disabled
                    class="input-enterprise w-full bg-slate-50 text-slate-600" />
            </div>

            <!-- Patient Dropdown (only when creating) -->
            <div x-show="!editingPatientLoadId">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Patient <span class="text-red-500">*</span></label>
                <select x-model="patientLoadForm.patient_id" class="select-enterprise w-full" required>
                    <option value="">Select a patient...</option>
                    <template x-for="p in stationPatients" :key="p.id">
                        <option :value="p.id" x-text="p.name + ' — ' + p.patient_unique_id"></option>
                    </template>
                </select>
            </div>

            <!-- Patient Display (only when editing) -->
            <div x-show="editingPatientLoadId">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Patient</label>
                <input type="text" :value="editingPatientName" disabled
                    class="input-enterprise w-full bg-slate-50 text-slate-600" />
            </div>

            <!-- Acuity Level -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Acuity Level <span class="text-red-500">*</span></label>
                <select x-model="patientLoadForm.acuity" class="select-enterprise w-full" required>
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
                <textarea x-model="patientLoadForm.description" class="textarea-enterprise w-full resize-none" rows="4"
                    placeholder="Any additional notes or care instructions..."></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex gap-2 pt-1">
                <button type="submit" class="btn-enterprise-primary flex-1" :disabled="submittingPatientLoad">
                    <span x-show="!submittingPatientLoad">Save Assignment</span>
                    <span x-show="submittingPatientLoad" class="flex items-center justify-center gap-2">
                        <span class="loading loading-spinner loading-sm"></span>Saving...
                    </span>
                </button>
                <button type="button" @click="closeAssignPatientModal" class="btn-enterprise-secondary flex-1">Cancel</button>
            </div>
        </form>
    </div>
</div>
