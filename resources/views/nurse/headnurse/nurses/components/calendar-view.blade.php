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
                <h3 class="text-lg font-semibold text-slate-800 mb-1">
                    Nurses Scheduled for <span x-text="formatDateDisplay(selectedDate)"></span>
                </h3>
                <p class="text-sm text-slate-500 mb-4">
                    <span x-text="scheduledNurses.dayOfWeek"></span>
                </p>

                <div class="overflow-x-auto">
                    <table class="table-enterprise">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Badge ID</th>
                                <th>Name</th>
                                <th>Shift</th>
                                <th>License</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-if="scheduledNurses.nurses.length > 0">
                                <template x-for="nurse in scheduledNurses.nurses" :key="nurse.id">
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
                                            <div class="flex flex-col">
                                                <span class="badge-enterprise bg-emerald-50 text-emerald-700 border-emerald-200" x-text="nurse.schedule_name"></span>
                                                <span class="text-xs text-slate-500 mt-1" x-text="`${nurse.start_time} - ${nurse.end_time}`"></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-sm text-slate-600" x-text="nurse.license_number"></span>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                            <template x-if="scheduledNurses.nurses.length === 0">
                                <tr>
                                    <td colspan="5" class="text-center py-10">
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
