@extends('layouts.clinic')

@section('content')
<div class="max-w-6xl mx-auto" x-data="nurseScheduleManager()">

    {{-- Header --}}
    <div class="card-enterprise p-5 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $title }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">Manage shift schedules for nurses under your supervision</p>
            </div>
            <button @click="openBatchDtrModal()" class="btn-enterprise-secondary inline-flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Batch DTR Report
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="card-enterprise overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-enterprise">
                <thead>
                    <tr>
                        <th></th>
                        <th>Badge ID</th>
                        <th>Name</th>
                        <th>Station</th>
                        <th>Shift Schedule</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nurses as $nurse)
                    <tr>
                        {{-- AVATAR --}}
                        <td>
                            <div class="avatar">
                                <div class="w-10 rounded-full">
                                    @if($nurse->user->profile_image_path)
                                        <img src="{{ asset('storage/' . $nurse->user->profile_image_path) }}" alt="{{ $nurse->first_name }}">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($nurse->first_name . ' ' . $nurse->last_name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $nurse->first_name }}">
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- BADGE ID --}}
                        <td>
                            <span class="font-semibold font-mono text-slate-700">{{ $nurse->employee_id }}</span>
                        </td>

                        {{-- NAME --}}
                        <td>
                            <div class="font-semibold text-slate-800">{{ $nurse->last_name }}, {{ $nurse->first_name }}</div>
                            <div class="text-xs text-slate-400">{{ $nurse->license_number }}</div>
                        </td>

                        {{-- STATION --}}
                        <td>
                            {{ $nurse->station?->station_name ?? 'Admission' }}
                        </td>

                        {{-- SHIFT SCHEDULE --}}
                        <td>
                            @if($nurse->shiftSchedule)
                                <div class="flex flex-col">
                                    <span class="badge-enterprise bg-emerald-50 text-emerald-700 border-emerald-200">{{ $nurse->shiftSchedule->name }}</span>
                                    <span class="text-xs text-slate-500 mt-1">
                                        {{ \Carbon\Carbon::parse($nurse->shiftSchedule->start_time)->format('g:i A') }} -
                                        {{ \Carbon\Carbon::parse($nurse->shiftSchedule->end_time)->format('g:i A') }}
                                    </span>
                                    <span class="text-xs text-slate-400">{{ $nurse->shiftSchedule->days_short }}</span>
                                </div>
                            @else
                                <span class="badge-enterprise bg-amber-50 text-amber-700 border-amber-200">Unassigned</span>
                            @endif
                        </td>

                        {{-- ACTIONS --}}
                        <td class="text-right">
                            <div class="flex gap-1 justify-end">
                                <button
                                    @click="openDtrModal({
                                        id: {{ $nurse->id }},
                                        name: '{{ $nurse->first_name }} {{ $nurse->last_name }}'
                                    })"
                                    class="btn-enterprise-secondary text-xs px-3 py-1.5 inline-flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    DTR
                                </button>
                                <button
                                    @click="openScheduleModal({
                                        id: {{ $nurse->id }},
                                        name: '{{ $nurse->first_name }} {{ $nurse->last_name }}',
                                        current_schedule_id: {{ $nurse->shift_schedule_id ?? 'null' }}
                                    })"
                                    class="btn-enterprise-primary text-xs px-3 py-1.5 inline-flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    Schedule
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-10">
                            <div class="flex flex-col items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-slate-300">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                </svg>
                                <p class="text-sm text-slate-500">No nurses found under your supervision.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4">
            {{ $nurses->links() }}
        </div>
    </div>

    {{-- ASSIGN SCHEDULE MODAL --}}
    <dialog id="schedule_modal" class="modal" x-ref="scheduleModal">
        <div class="modal-enterprise">
            <form method="dialog">
                <button class="absolute right-4 top-4 text-slate-400 hover:text-slate-600 transition-colors">✕</button>
            </form>

            <h3 class="text-lg font-bold text-slate-800 mb-1">Assign Shift Schedule</h3>
            <p class="text-sm text-slate-500 mb-4">Assigning schedule for: <span class="font-bold text-emerald-600" x-text="selectedNurse.name"></span></p>

            <form :action="`{{ url('nurse/headnurse/nurses') }}/${selectedNurse.id}/schedule`" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Schedule Select --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Select Shift Schedule</label>
                    <select name="shift_schedule_id" x-model="selectedNurse.current_schedule_id" class="select-enterprise w-full">
                        <option value="">— Unassigned —</option>
                        @foreach($schedules as $schedule)
                        <option value="{{ $schedule->id }}">
                            {{ $schedule->name }}
                            [{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}]
                            - [{{ $schedule->days_short }}]
                        </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-400 mt-1">Select a schedule or leave unassigned</p>
                </div>

                {{-- Schedule Details Preview --}}
                <template x-if="selectedNurse.current_schedule_id && getScheduleDetails(selectedNurse.current_schedule_id)">
                    <div class="card-enterprise border-l-4 border-l-sky-500 p-3 flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-sky-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <div>
                            <div class="text-sm font-semibold text-slate-800" x-text="getScheduleDetails(selectedNurse.current_schedule_id).name"></div>
                            <div class="text-xs text-slate-500">
                                <span x-text="getScheduleDetails(selectedNurse.current_schedule_id).hours"></span> hrs/week
                            </div>
                        </div>
                    </div>
                </template>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-200">
                    <button type="button" @click="$refs.scheduleModal.close()" class="btn-enterprise-secondary">Cancel</button>
                    <button type="submit" class="btn-enterprise-primary inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        Save Schedule
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    {{-- SINGLE NURSE DTR REPORT MODAL --}}
    <dialog id="dtr_nurse_modal" class="modal" x-ref="dtrModal">
        <div class="modal-enterprise">
            <form method="dialog">
                <button class="absolute right-4 top-4 text-slate-400 hover:text-slate-600 transition-colors">✕</button>
            </form>

            <h3 class="text-lg font-bold text-slate-800 mb-1">DTR Report</h3>
            <p class="text-sm text-slate-500 mb-4">Generate DTR report for: <span class="font-bold text-emerald-600" x-text="selectedDtrNurse.name"></span></p>

            <form :action="`{{ url('nurse/headnurse/nurses') }}/${selectedDtrNurse.id}/dtr-report`" method="POST" target="_blank" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Date From</label>
                        <input type="date" name="date_from" required class="input-enterprise w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Date To</label>
                        <input type="date" name="date_to" required class="input-enterprise w-full" />
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-200">
                    <button type="button" @click="$refs.dtrModal.close()" class="btn-enterprise-secondary">Cancel</button>
                    <button type="submit" class="btn-enterprise-primary inline-flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Generate PDF
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

    {{-- BATCH DTR REPORT MODAL --}}
    <dialog id="dtr_batch_modal" class="modal" x-ref="batchDtrModal">
        <div class="modal-enterprise">
            <form method="dialog">
                <button class="absolute right-4 top-4 text-slate-400 hover:text-slate-600 transition-colors">✕</button>
            </form>

            <h3 class="text-lg font-bold text-slate-800 mb-1">Batch DTR Report</h3>
            <p class="text-sm text-slate-500 mb-4">Generate DTR reports for <strong>all nurses</strong> under your supervision in one PDF.</p>

            <form action="{{ route('nurse.headnurse.nurses.batchDtrReport') }}" method="POST" target="_blank" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Date From</label>
                        <input type="date" name="date_from" required class="input-enterprise w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Date To</label>
                        <input type="date" name="date_to" required class="input-enterprise w-full" />
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-200">
                    <button type="button" @click="$refs.batchDtrModal.close()" class="btn-enterprise-secondary">Cancel</button>
                    <button type="submit" class="btn-enterprise-primary inline-flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Generate Batch PDF
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

</div>

@push('scripts')
<script>
    window.schedulesData = @json($schedules);
    function nurseScheduleManager() {
        return {
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
            }
        }
    }
</script>
@endpush
@endsection
