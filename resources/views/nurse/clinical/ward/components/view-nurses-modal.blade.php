<div x-show="viewNursesModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
    <div @click.away="viewNursesModalOpen = false" class="bg-white rounded-lg shadow-lg w-full md:w-2xl max-h-screen md:max-h-[500px] overflow-y-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Assigned Nurses</h3>
                <p class="text-sm text-slate-500" x-text="selectedPatientName"></p>
            </div>
            <button @click="viewNursesModalOpen = false" class="text-slate-400 hover:text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div x-show="!viewNursesLoading">
            <div x-show="assignedNurses.length === 0" class="text-center py-6">
                <p class="text-slate-400 text-sm">No nurses assigned yet</p>
            </div>

            <div x-show="assignedNurses.length > 0" class="space-y-3">
                <template x-for="nurse in assignedNurses" :key="nurse.id">
                    <div class="p-3 bg-slate-50 rounded-lg border-l-4" :style="'border-left-color: ' + getAcuityColor(nurse.acuity)">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <p class="font-semibold text-slate-800" x-text="nurse.nurse_name"></p>
                                <p class="text-sm text-slate-500" x-text="'ID: ' + nurse.employee_id"></p>
                                <div class="mt-2 flex gap-3 items-center">
                                    <span class="text-sm font-semibold" :style="'color: ' + getAcuityColor(nurse.acuity)" x-text="nurse.acuity"></span>
                                    <span class="text-sm text-slate-600" x-text="'Score: ' + nurse.score"></span>
                                </div>
                                <p x-show="nurse.description" class="text-sm text-slate-600 mt-2 italic" x-text="nurse.description"></p>
                            </div>
                            <template x-if="isHeadNurse">
                                <button @click="editPatientLoad(nurse.id, nurse.acuity, nurse.description)"
                                    class="btn btn-xs btn-ghost" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                            </template>
                        </div>
                        <p x-show="nurse.description" class="text-xs text-slate-600 mt-2 italic" x-text="nurse.description"></p>
                    </div>
                </template>
            </div>
        </div>

        <div x-show="viewNursesLoading" class="flex justify-center py-4">
            <span class="loading loading-spinner loading-sm"></span>
        </div>
    </div>
</div>
