{{-- Assign/Edit Date Schedule Modal --}}
<dialog id="date_schedule_modal" class="modal" x-ref="dateScheduleModal">
    <div class="modal-enterprise">
        <form method="dialog">
            <button class="absolute right-4 top-4 text-slate-400 hover:text-slate-600 transition-colors">✕</button>
        </form>

        <h3 class="text-lg font-bold text-slate-800 mb-1" x-text="isEditingSchedule ? 'Edit Date Schedule' : 'Assign New Date Schedule'"></h3>
        <p class="text-sm text-slate-500 mb-4">
            <span x-show="!isEditingSchedule">Assigning to: <span class="font-bold text-emerald-600" x-text="selectedNurse.name"></span></span>
            <span x-show="isEditingSchedule">Editing schedule</span>
        </p>

        <form @submit.prevent="submitDateScheduleForm" class="space-y-4">
            {{-- Date (disabled if editing) --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Date</label>
                <input
                    type="date"
                    x-model="dateScheduleForm.date"
                    :disabled="isEditingSchedule"
                    class="input-enterprise w-full"
                    required>
                <p class="text-xs text-slate-400 mt-1">Select the date for this schedule</p>
            </div>

            {{-- Nurse Dropdown (only show when creating, not editing) --}}
            <template x-if="!isEditingSchedule">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nurse</label>
                    <select x-model="dateScheduleForm.nurse_id" class="select-enterprise w-full" required>
                        <option value="">— Select a nurse —</option>
                        <template x-for="nurse in availableNurses" :key="nurse.id">
                            <option :value="nurse.id"  x-text="`${nurse.last_name}, ${nurse.first_name}`"></option>
                        </template>
                    </select>
                    <p class="text-xs text-slate-400 mt-1">Choose which nurse to assign</p>
                </div>
            </template>

            {{-- Start Time --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Start Time</label>
                <input
                    type="time"
                    x-model="dateScheduleForm.start_shift"
                    class="input-enterprise w-full"
                    required>
                <p class="text-xs text-slate-400 mt-1">When does the shift start?</p>
            </div>

            {{-- End Time --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">End Time</label>
                <input
                    type="time"
                    x-model="dateScheduleForm.end_shift"
                    class="input-enterprise w-full"
                    required>
                <p class="text-xs text-slate-400 mt-1">When does the shift end?</p>
            </div>

            {{-- Assignment (Nurse Type) --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Assignment (Optional)</label>
                <select x-model="dateScheduleForm.assignment" class="select-enterprise w-full">
                    <option value="">— No specific assignment —</option>
                    <template x-for="nurseType in nurseTypes" :key="nurseType.id">
                        <option :value="nurseType.name" x-text="nurseType.name"></option>
                    </template>
                </select>
                <p class="text-xs text-slate-400 mt-1">Assign a specific role or unit (e.g., ER, Dialysis)</p>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-200">
                <button type="button" @click="$refs.dateScheduleModal.close()" class="btn-enterprise-secondary">Cancel</button>
                <button type="submit" class="btn-enterprise-primary inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <span x-text="isEditingSchedule ? 'Save Changes' : 'Assign Schedule'"></span>
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
