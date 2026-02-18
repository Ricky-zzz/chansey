@extends('layouts.clinic')

@section('content')
<div class="max-w-6xl mx-auto" x-data="nurseScheduleManager()" @load="init()" x-init="init()">

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
    @include('nurse.headnurse.nurses.components.modals.view-schedule-modal')
    @include('nurse.headnurse.nurses.components.modals.date-schedule-modal', ['availableNurses' => $availableNurses, 'nurseTypes' => $nurseTypes])
    @include('nurse.headnurse.nurses.components.modals.nurse-patients-modal')
    @include('nurse.headnurse.nurses.components.modals.single-dtr-modal')
    @include('nurse.headnurse.nurses.components.modals.batch-dtr-modal')

</div>

@push('scripts')
<script>
    window.availableNursesData = @json($availableNurses);
    window.nurseTypesData = @json($nurseTypes);

    function nurseScheduleManager() {
        return {
            currentView: 'table',
            selectedNurse: {
                id: null,
                name: '',
            },
            selectedDtrNurse: {
                id: null,
                name: ''
            },
            availableNurses: window.availableNursesData,
            nurseTypes: window.nurseTypesData,
            nurseSchedules: [],
            isEditingSchedule: false,
            dateScheduleForm: {
                date: '',
                nurse_id: '',
                start_shift: '',
                end_shift: '',
                assignment: '',
            },

            currentMonth: new Date(),
            selectedDate: null,
            scheduledNurses: {
                date: null,
                dayOfWeek: '',
                nurses: []
            },
            loadingScheduledNurses: false,

            // Patient Load Management State
            viewNursePatientsOpen: false,
            nursePatientsLoading: false,
            assignedPatients: [],
            nursePatientsRatio: '0:0',

            // Initialize and load all nurse ratios
            init() {
                this.$nextTick(() => {
                    // Load patient ratios for all visible nurses
                    document.querySelectorAll('[data-nurse-id]').forEach(el => {
                        const nurseId = el.getAttribute('data-nurse-id');
                        if (nurseId) {
                            this.loadNurseRatio(nurseId);
                        }
                    });
                });
            },

            loadNurseRatio(nurseId) {
                const url = `{{ route('nurse.headnurse.patient-loads.getPatients', 0) }}`.replace('/0', `/${nurseId}`);

                fetch(url)
                    .then(response => response.ok ? response.json() : Promise.reject('Error'))
                    .then(data => {
                        const ratioElement = document.getElementById(`ratio-${nurseId}`);
                        if (ratioElement && data.ratio) {
                            ratioElement.textContent = data.ratio;
                        }
                    })
                    .catch(error => console.error('Error loading nurse ratio:', error));
            },

            // Toast notification helper
            showToast(message, type = 'success') {
                const toastHtml = `
                    <div class="toast toast-top toast-end z-50">
                        <div class="toast-enterprise-${type} flex items-center gap-3 px-4 py-3">
                            ${type === 'success' ? `
                                <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0 h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            ` : `
                                <svg xmlns="http://www.w3.org/2000/svg" class="shrink-0 h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m8-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            `}
                            <div class="text-sm">
                                <span class="font-semibold">${type === 'success' ? 'Success!' : 'Error!'}</span> ${message}
                            </div>
                        </div>
                    </div>
                `;

                const temp = document.createElement('div');
                temp.innerHTML = toastHtml;
                document.body.appendChild(temp.firstElementChild);

                setTimeout(() => {
                    temp.firstElementChild?.remove();
                }, 3000);
            },

            // View schedules modal
            openViewSchedulesModal(nurse) {
                this.selectedNurse = {
                    id: nurse.id,
                    name: nurse.name,
                };
                this.fetchNurseSchedules(nurse.id);
                this.$refs.viewSchedulesModal.showModal();
            },

            async fetchNurseSchedules(nurseId) {
                try {
                    const response = await fetch(
                        `{{ route('nurse.headnurse.nurses.getDateSchedules', ':nurseId') }}`.replace(':nurseId', nurseId)
                    );
                    const data = await response.json();

                    if (response.ok && data.success) {
                        this.nurseSchedules = data.schedules;
                    } else {
                        this.nurseSchedules = [];
                        this.showToast('Could not load schedules', 'error');
                    }
                } catch (error) {
                    console.error('Error fetching nurse schedules:', error);
                    this.nurseSchedules = [];
                    this.showToast('Error loading schedules', 'error');
                }
            },

            // Assign date schedule modal
            openAssignDateScheduleModal(nurseId) {
                this.isEditingSchedule = false;
                this.resetDateScheduleForm();
                this.dateScheduleForm.nurse_id = nurseId;
                this.$refs.dateScheduleModal.showModal();
            },

            openAssignDateScheduleModalForDate() {
                this.isEditingSchedule = false;
                this.resetDateScheduleForm();
                this.dateScheduleForm.date = this.selectedDate;
                this.$refs.dateScheduleModal.showModal();
            },

            openEditDateScheduleModal(schedule) {
                this.isEditingSchedule = true;
                this.dateScheduleForm = {
                    id: schedule.id || schedule.dateschedule_id,
                    date: schedule.date || this.selectedDate,
                    nurse_id: schedule.nurse_id || '',
                    start_shift: schedule.start_shift,
                    end_shift: schedule.end_shift,
                    assignment: schedule.assignment || '',
                };
                this.$refs.dateScheduleModal.showModal();
            },

            openEditScheduleModal(schedule) {
                this.isEditingSchedule = true;
                this.dateScheduleForm = {
                    id: schedule.id,
                    date: schedule.date,
                    nurse_id: schedule.nurse_id,
                    start_shift: schedule.start_shift,
                    end_shift: schedule.end_shift,
                    assignment: schedule.assignment || '',
                };
                this.$refs.dateScheduleModal.showModal();
            },

            resetDateScheduleForm() {
                this.dateScheduleForm = {
                    date: '',
                    nurse_id: '',
                    start_shift: '',
                    end_shift: '',
                    assignment: '',
                };
            },

            async submitDateScheduleForm() {
                try {
                    const url = this.isEditingSchedule
                        ? `{{ route('nurse.headnurse.date-schedules.update', ':id') }}`.replace(':id', this.dateScheduleForm.id)
                        : `{{ route('nurse.headnurse.date-schedules.store') }}`;

                    const method = this.isEditingSchedule ? 'PUT' : 'POST';

                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify(this.dateScheduleForm),
                    });

                    const data = await response.json();

                    if (response.ok) {
                        this.showToast(data.message || 'Schedule saved successfully', 'success');
                        this.$refs.dateScheduleModal.close();

                        // Refresh the schedules
                        if (this.selectedDate) {
                            this.fetchScheduledNurses(this.selectedDate);
                        }
                        if (this.selectedNurse.id) {
                            this.fetchNurseSchedules(this.selectedNurse.id);
                        }
                    } else {
                        this.showToast(data.error || data.message || 'Error saving schedule', 'error');
                    }
                } catch (error) {
                    console.error('Error submitting form:', error);
                    this.showToast('An error occurred while saving the schedule', 'error');
                }
            },

            async deleteSchedule(scheduleId) {
                if (!confirm('Are you sure you want to delete this schedule?')) {
                    return;
                }

                try {
                    const response = await fetch(
                        `{{ route('nurse.headnurse.date-schedules.destroy', ':id') }}`.replace(':id', scheduleId),
                        {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                        }
                    );

                    const data = await response.json();

                    if (response.ok) {
                        this.showToast(data.message || 'Schedule deleted successfully', 'success');

                        // Refresh the schedules
                        if (this.selectedDate) {
                            this.fetchScheduledNurses(this.selectedDate);
                        }
                        if (this.selectedNurse.id) {
                            this.fetchNurseSchedules(this.selectedNurse.id);
                        }
                    } else {
                        this.showToast(data.error || data.message || 'Error deleting schedule', 'error');
                    }
                } catch (error) {
                    console.error('Error deleting schedule:', error);
                    this.showToast('An error occurred while deleting the schedule', 'error');
                }
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

            viewNursePatients(nurseId, nurseName) {
                this.nursePatientsLoading = true;
                this.viewNursePatientsOpen = true;

                const url = `{{ route('nurse.headnurse.patient-loads.getPatients', 0) }}`.replace('/0', `/${nurseId}`);
                console.log('Fetching patient loads from:', url);

                fetch(url)
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Patient loads data:', data);
                        this.assignedPatients = data.patients || [];
                        this.nursePatientsRatio = data.ratio || '0:0';
                        // Update the ratio display on the table
                        const ratioElement = document.getElementById(`ratio-${nurseId}`);
                        if (ratioElement) {
                            ratioElement.textContent = this.nursePatientsRatio;
                        }
                        this.nursePatientsLoading = false;
                    })
                    .catch(error => {
                        console.error('Error fetching patient loads:', error);
                        this.nursePatientsLoading = false;
                        this.showToast('Error loading patient assignments', 'error');
                    });
            },

            getAcuityColor(acuity) {
                const colors = {
                    'Severe': '#dc2626',
                    'High': '#f59e0b',
                    'Moderate': '#0ea5e9',
                    'Low': '#10b981',
                };
                return colors[acuity] || '#6b7280';
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

            formatDate(dateStr) {
                return new Date(dateStr).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
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
