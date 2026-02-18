@extends('layouts.clinic')

@section('content')
<div class="max-w-6xl mx-auto" x-data="nurseScheduleManager()">

    {{-- Header --}}
    @include('nurse.headnurse.nurses.components.header', ['title' => $title])

    {{-- Tab Navigation --}}
    <div class="card-enterprise mb-6">
        <div class="flex border-b border-slate-200">
            <button
                @click="currentView = 'table'"
                :class="{
                    'border-b-2 border-emerald-500 text-emerald-600': currentView === 'table',
                    'text-slate-600 hover:text-slate-800': currentView !== 'table'
                }"
                class="flex-1 px-4 py-3 font-semibold transition-colors text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2m6-2v2M9 5h12a2 2 0 012 2v12a2 2 0 01-2 2H9a2 2 0 01-2-2V7a2 2 0 012-2zm0 5h12m0 0H9" />
                </svg>
                Station Nurses
            </button>
            <button
                @click="currentView = 'calendar'"
                :class="{
                    'border-b-2 border-emerald-500 text-emerald-600': currentView === 'calendar',
                    'text-slate-600 hover:text-slate-800': currentView !== 'calendar'
                }"
                class="flex-1 px-4 py-3 font-semibold transition-colors text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Calendar View
            </button>
        </div>
    </div>

    {{-- Station Nurses Tab --}}
    <div x-show="currentView === 'table'" x-transition>
        @include('nurse.headnurse.nurses.components.nurses-table', ['nurses' => $nurses])
    </div>

    {{-- Calendar Tab --}}
    <div x-show="currentView === 'calendar'" x-transition>
        @include('nurse.headnurse.nurses.components.calendar-view')
    </div>

    {{-- Modals --}}
    @include('nurse.headnurse.nurses.components.modals.schedule-modal', ['schedules' => $schedules])
    @include('nurse.headnurse.nurses.components.modals.single-dtr-modal')
    @include('nurse.headnurse.nurses.components.modals.batch-dtr-modal')

</div>

@push('scripts')
<script>
    window.schedulesData = @json($schedules);

    function nurseScheduleManager() {
        return {
            currentView: 'table',
            selectedNurse: {
                id: null,
                name: '',
                current_schedule_id: null
            },
            selectedDtrNurse: {
                id: null,
                name: ''
            },
            schedules: window.schedulesData,

            currentMonth: new Date(),
            selectedDate: null,
            scheduledNurses: {
                date: null,
                dayOfWeek: '',
                nurses: []
            },
            loadingScheduledNurses: false,

            openScheduleModal(nurse) {
                this.selectedNurse = {
                    id: nurse.id,
                    name: nurse.name,
                    current_schedule_id: nurse.current_schedule_id
                };
                this.$refs.scheduleModal.showModal();
            },

            openDtrModal(nurse) {
                this.selectedDtrNurse = {
                    id: nurse.id,
                    name: nurse.name
                };
                this.$refs.dtrModal.showModal();
            },

            openBatchDtrModal() {
                this.$refs.batchDtrModal.showModal();
            },

            getScheduleDetails(scheduleId) {
                if (!scheduleId) return null;
                const schedule = this.schedules.find(s => s.id == scheduleId);
                if (!schedule) return null;
                return {
                    name: schedule.name,
                    hours: schedule.total_hours_per_week
                };
            },

            // Calendar functions
            get calendarDays() {
                const year = this.currentMonth.getFullYear();
                const month = this.currentMonth.getMonth();

                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);
                const startDate = new Date(firstDay);
                startDate.setDate(startDate.getDate() - firstDay.getDay());

                const days = [];
                let currentDate = new Date(startDate);

                while (days.length < 42) {
                    days.push({
                        date: this.formatDateLocal(currentDate),
                        day: currentDate.getDate(),
                        isCurrentMonth: currentDate.getMonth() === month
                    });
                    currentDate.setDate(currentDate.getDate() + 1);
                }

                return days;
            },

            formatDateLocal(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            },

            previousMonth() {
                this.currentMonth.setMonth(this.currentMonth.getMonth() - 1);
                this.currentMonth = new Date(this.currentMonth);
            },

            nextMonth() {
                this.currentMonth.setMonth(this.currentMonth.getMonth() + 1);
                this.currentMonth = new Date(this.currentMonth);
            },

            isDateSelected(date) {
                return this.selectedDate === date;
            },

            selectDate(date) {
                this.selectedDate = date;
                this.fetchScheduledNurses(date);
            },

            formatDateDisplay(date) {
                return new Date(date).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            },

            async fetchScheduledNurses(date) {
                this.loadingScheduledNurses = true;
                try {
                    const response = await fetch(`{{ route('nurse.headnurse.nurses.getScheduled') }}?date=${date}`);
                    const data = await response.json();
                    this.scheduledNurses = data;
                } catch (error) {
                    console.error('Error fetching scheduled nurses:', error);
                    this.scheduledNurses = {
                        date: date,
                        dayOfWeek: '',
                        nurses: []
                    };
                } finally {
                    this.loadingScheduledNurses = false;
                }
            }
        }
    }
</script>
@endpush
@endsection
