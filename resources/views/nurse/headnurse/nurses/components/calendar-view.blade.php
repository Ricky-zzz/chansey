{{-- Calendar View --}}
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    {{-- Calendar --}}
    <div class="lg:col-span-1">
        <div class="card-enterprise p-4">
            <div class="flex justify-between items-center mb-4">
                <button @click="previousMonth()" class="btn-enterprise-secondary px-2 py-1 text-sm">←</button>
                <h3 class="font-semibold text-slate-800">
                    <span x-text="currentMonth.toLocaleString('default', { month: 'long', year: 'numeric' })"></span>
                </h3>
                <button @click="nextMonth()" class="btn-enterprise-secondary px-2 py-1 text-sm">→</button>
            </div>

            {{-- Weekday headers --}}
            <div class="grid grid-cols-7 gap-1 mb-2">
                <template x-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']">
                    <div class="text-center text-xs font-semibold text-slate-500" x-text="day"></div>
                </template>
            </div>

            {{-- Calendar days --}}
            <div class="grid grid-cols-7 gap-1">
                <template x-for="day in calendarDays" :key="day.date">
                    <button
                        @click="selectDate(day.date)"
                        :class="{
                            'bg-slate-100 text-slate-400 cursor-not-allowed': !day.isCurrentMonth,
                            'bg-emerald-500 text-white font-semibold': isDateSelected(day.date),
                            'hover:bg-slate-100': day.isCurrentMonth && !isDateSelected(day.date),
                            'text-slate-800': day.isCurrentMonth
                        }"
                        :disabled="!day.isCurrentMonth"
                        class="p-2 text-sm rounded transition-colors text-center"
                        x-text="day.day"
                    ></button>
                </template>
            </div>
        </div>
    </div>

    {{-- Scheduled Nurses Results --}}
    <div class="lg:col-span-3">
        <template x-if="selectedDate && !loadingScheduledNurses">
            <div class="card-enterprise p-4">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800">
                            Nurses Scheduled for <span x-text="formatDateDisplay(selectedDate)"></span>
                        </h3>
                        <p class="text-sm text-slate-500">
                            <span x-text="scheduledNurses.dayOfWeek"></span>
                        </p>
                    </div>
                    <button
                        @click="openAssignDateScheduleModalForDate()"
                        class="btn-enterprise-primary inline-flex items-center gap-2 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Assign Nurse
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="table-enterprise">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Badge ID</th>
                                <th>Name</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Assignment</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-if="scheduledNurses.nurses.length > 0">
                                <template x-for="nurse in scheduledNurses.nurses" :key="nurse.dateschedule_id">
                                    <tr>
                                        <td>
                                            <div class="avatar">
                                                <div class="w-10 rounded-full">
                                                    <template x-if="nurse.profile_image_path">
                                                        <img :src="`{{ asset('storage') }}/${nurse.profile_image_path}`" :alt="nurse.first_name">
                                                    </template>
                                                    <template x-if="!nurse.profile_image_path">
                                                        <img :src="`https://ui-avatars.com/api/?name=${nurse.first_name}+${nurse.last_name}&color=7F9CF5&background=EBF4FF`" :alt="nurse.first_name">
                                                    </template>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="font-semibold font-mono text-slate-700" x-text="nurse.employee_id"></span>
                                        </td>
                                        <td>
                                            <div class="font-semibold text-slate-800" x-text="`${nurse.last_name}, ${nurse.first_name}`"></div>
                                            <div class="text-xs text-slate-400" x-text="nurse.license_number"></div>
                                        </td>
                                        <td>
                                            <span class="text-slate-600" x-text="nurse.start_time"></span>
                                        </td>
                                        <td>
                                            <span class="text-slate-600" x-text="nurse.end_time"></span>
                                        </td>
                                        <td>
                                            <span class="text-sm" :class="nurse.assignment ? 'text-slate-600' : 'text-slate-400'" x-text="nurse.assignment || '—'"></span>
                                        </td>
                                        <td class="text-right">
                                            <div class="flex gap-1 justify-end">
                                                <button
                                                    @click="openEditDateScheduleModal(nurse)"
                                                    class="btn-enterprise-secondary text-xs px-2 py-1 inline-flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit
                                                </button>
                                                <button
                                                    @click="deleteSchedule(nurse.dateschedule_id)"
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
                            <template x-if="scheduledNurses.nurses.length === 0">
                                <tr>
                                    <td colspan="7" class="text-center py-10">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-slate-300">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            <p class="text-sm text-slate-500">No nurses scheduled for this date.</p>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>

        {{-- Loading State --}}
        <template x-if="loadingScheduledNurses">
            <div class="card-enterprise p-12 flex flex-col items-center gap-3">
                <svg class="w-8 h-8 text-emerald-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-sm text-slate-500">Loading scheduled nurses...</p>
            </div>
        </template>

        {{-- No Date Selected --}}
        <template x-if="!selectedDate && !loadingScheduledNurses">
            <div class="card-enterprise p-12 flex flex-col items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-slate-300">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <p class="text-sm text-slate-500">Select a date to view scheduled nurses.</p>
            </div>
        </template>
    </div>
</div>
