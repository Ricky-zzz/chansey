<div x-show="assignNurseModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
    <div @click.away="assignNurseModalOpen = false" class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Assign Nurse to Patient</h3>
                <p class="text-sm text-slate-500" x-text="selectedPatientName"></p>
            </div>
            <button @click="assignNurseModalOpen = false" class="text-slate-400 hover:text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form @submit.prevent="submitPatientLoadForm" class="space-y-4">
            <!-- Nurse Selection -->
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold text-slate-700">Select Nurse <span class="text-red-600">*</span></span>
                </label>
                <select x-model="patientLoadForm.nurse_id" class="input-enterprise" required>
                    <option value="">Choose a nurse...</option>
                    <template x-for="nurse in availableNurses" :key="nurse.id">
                        <option :value="nurse.id" x-text="nurse.first_name + ' ' + nurse.last_name + ' (' + nurse.employee_id + ')'"></option>
                    </template>
                </select>
            </div>

            <!-- Acuity Level -->
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold text-slate-700">Acuity Level</span>
                </label>
                <select x-model="patientLoadForm.acuity" class="input-enterprise" required>
                    <option value="">Select acuity...</option>
                    <option value="Severe">Severe (Score: 4)</option>
                    <option value="High">High (Score: 3)</option>
                    <option value="Moderate">Moderate (Score: 2)</option>
                    <option value="Low">Low (Score: 1)</option>
                </select>
            </div>

            <!-- Description -->
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold text-slate-700">Instructions / Notes</span>
                </label>
                <textarea x-model="patientLoadForm.description" class="input-enterprise resize-none" rows="6" placeholder="Add any special instructions or notes..."></textarea>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-2 pt-4">
                <button type="button" @click="assignNurseModalOpen = false" class="btn btn-outline flex-1">
                    Cancel
                </button>
                <button type="submit" class="btn-enterprise-primary flex-1">
                    Assign Nurse
                </button>
            </div>
        </form>
    </div>
</div>
