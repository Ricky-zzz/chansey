{{-- View Date Schedules Modal --}}
<dialog id="view_schedule_modal" class="modal" x-ref="viewSchedulesModal">
    <div class="modal-enterprise" style="transform: translateY(-15vh);">
        <form method="dialog">
            <button class="absolute right-4 top-4 text-slate-400 hover:text-slate-600 transition-colors">✕</button>
        </form>

        <h3 class="text-lg font-bold text-slate-800 mb-1">Assigned Date Schedules</h3>
        <p class="text-sm text-slate-500 mb-4">
            Viewing schedules for: <span class="font-bold text-emerald-600" x-text="selectedNurse.name"></span>
        </p>

        {{-- Schedules Table --}}
        <div class="overflow-x-auto mb-4">
            <table class="table-enterprise">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Assignment</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-if="nurseSchedules && nurseSchedules.length > 0">
                        <template x-for="schedule in nurseSchedules" :key="schedule.id">
                            <tr>
                                <td>
                                    <span class="font-semibold text-slate-700" x-text="formatDate(schedule.date)"></span>
                                </td>
                                <td>
                                    <span class="text-slate-600" x-text="schedule.start_shift"></span>
                                </td>
                                <td>
                                    <span class="text-slate-600" x-text="schedule.end_shift"></span>
                                </td>
                                <td>
                                    <span class="text-sm" :class="schedule.assignment ? 'text-slate-600' : 'text-slate-400'" x-text="schedule.assignment || '—'"></span>
                                </td>
                                <td class="text-right">
                                    <div class="flex gap-1 justify-end">
                                        <button
                                            @click="openEditScheduleModal(schedule)"
                                            class="btn-enterprise-secondary text-xs px-2 py-1 inline-flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </button>
                                        <button
                                            @click="deleteSchedule(schedule.id)"
                                            class="btn-enterprise-danger text-xs px-2 py-1 inline-flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </template>
                    <template x-if="!nurseSchedules || nurseSchedules.length === 0">
                        <tr>
                            <td colspan="5" class="text-center py-8">
                                <div class="flex flex-col items-center gap-2 text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-slate-300">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <p class="text-sm">No date schedules assigned yet.</p>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="flex justify-between gap-2 pt-4 border-t border-slate-200">
            <button
                @click="openAssignDateScheduleModal(selectedNurse.id)"
                class="btn-enterprise-primary inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Assign New Schedule
            </button>
            <button type="button" @click="$refs.viewSchedulesModal.close()" class="btn-enterprise-secondary">Close</button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
