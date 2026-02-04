@extends('layouts.clinic')

@section('content')
<div class="max-w-6xl mx-auto" x-data="nurseScheduleManager()">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-black text-slate-800">{{ $title }}</h2>
            <p class="text-sm text-gray-500">Manage shift schedules for nurses under your supervision</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="card bg-white shadow-sm border border-slate-200">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead class="bg-slate-800 text-white uppercase text-xs">
                    <tr>
                        <th></th>
                        <th>Badge ID</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Head Nurse</th>
                        <th>Station</th>
                        <th>Shift Schedule</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nurses as $nurse)
                    <tr class="hover">
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
                            <span class="font-bold font-mono">{{ $nurse->employee_id }}</span>
                        </td>

                        {{-- NAME --}}
                        <td>
                            <div class="font-bold">{{ $nurse->last_name }}, {{ $nurse->first_name }}</div>
                            <div class="text-xs text-gray-400">{{ $nurse->license_number }}</div>
                        </td>

                        {{-- DESIGNATION --}}
                        <td>
                            <span class="badge {{ $nurse->designation === 'Admitting' ? 'badge-warning' : 'badge-success' }} text-white">
                                {{ $nurse->designation }}
                            </span>
                        </td>

                        {{-- HEAD NURSE --}}
                        <td>
                            @if($nurse->is_head_nurse)
                                <span class="badge badge-primary text-white">Head Nurse</span>
                            @else
                                <span class="badge badge-ghost">Staff</span>
                            @endif
                        </td>

                        {{-- STATION --}}
                        <td>
                            {{ $nurse->station?->station_name ?? 'Admission' }}
                        </td>

                        {{-- SHIFT SCHEDULE --}}
                        <td>
                            @if($nurse->shiftSchedule)
                                <div class="flex flex-col">
                                    <span class="badge badge-success text-white">{{ $nurse->shiftSchedule->name }}</span>
                                    <span class="text-xs text-gray-500 mt-1">
                                        {{ \Carbon\Carbon::parse($nurse->shiftSchedule->start_time)->format('g:i A') }} -
                                        {{ \Carbon\Carbon::parse($nurse->shiftSchedule->end_time)->format('g:i A') }}
                                    </span>
                                    <span class="text-xs text-gray-400">{{ $nurse->shiftSchedule->days_short }}</span>
                                </div>
                            @else
                                <span class="badge badge-warning">Unassigned</span>
                            @endif
                        </td>

                        {{-- ACTIONS --}}
                        <td class="text-right">
                            <button
                                @click="openScheduleModal({
                                    id: {{ $nurse->id }},
                                    name: '{{ $nurse->first_name }} {{ $nurse->last_name }}',
                                    current_schedule_id: {{ $nurse->shift_schedule_id ?? 'null' }}
                                })"
                                class="btn btn-sm btn-outline btn-primary gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                Assign Schedule
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-10 text-gray-400 italic">
                            <div class="flex flex-col items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-300">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                </svg>
                                <p>No nurses found under your supervision.</p>
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
        <div class="modal-box">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>

            <h3 class="font-bold text-xl mb-2">Assign Shift Schedule</h3>
            <p class="text-sm text-gray-500 mb-4">Assigning schedule for: <span class="font-bold text-primary" x-text="selectedNurse.name"></span></p>

            <form :action="'{{ route('nurse.headnurse.nurses.updateSchedule', '') }}/' + selectedNurse.id + '/schedule'" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Schedule Select --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Select Shift Schedule</span>
                    </label>
                    <select name="shift_schedule_id" x-model="selectedNurse.current_schedule_id" class="select select-bordered w-full">
                        <option value="">— Unassigned —</option>
                        @foreach($schedules as $schedule)
                        <option value="{{ $schedule->id }}">
                            {{ $schedule->name }}
                            [{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}]
                            - [{{ $schedule->days_short }}]
                        </option>
                        @endforeach
                    </select>
                    <label class="label">
                        <span class="label-text-alt text-gray-400">Select a schedule or leave unassigned</span>
                    </label>
                </div>

                {{-- Schedule Details Preview --}}
                <template x-if="selectedNurse.current_schedule_id && getScheduleDetails(selectedNurse.current_schedule_id)">
                    <div class="alert bg-base-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <div>
                            <div class="text-sm font-semibold" x-text="getScheduleDetails(selectedNurse.current_schedule_id).name"></div>
                            <div class="text-xs text-gray-500">
                                <span x-text="getScheduleDetails(selectedNurse.current_schedule_id).hours"></span> hrs/week
                            </div>
                        </div>
                    </div>
                </template>

                <div class="modal-action">
                    <button type="button" @click="$refs.scheduleModal.close()" class="btn btn-ghost">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
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
            schedules: window.schedulesData,

            openScheduleModal(nurse) {
                this.selectedNurse = {
                    id: nurse.id,
                    name: nurse.name,
                    current_schedule_id: nurse.current_schedule_id
                };
                this.$refs.scheduleModal.showModal();
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
