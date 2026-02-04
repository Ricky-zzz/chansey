@extends('layouts.clinic')

@section('content')
<div class="max-w-6xl mx-auto" x-data="shiftScheduleManager()">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-black text-slate-800">Shift Schedules</h2>
            <p class="text-sm text-gray-500">Manage work schedules for nurses</p>
        </div>
        <button @click="openCreateModal()" class="btn text-white btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            New Schedule
        </button>
    </div>

    {{-- Table --}}
    <div class="card bg-white shadow-sm border border-slate-200">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead class="bg-slate-800 text-white uppercase text-xs">
                    <tr>
                        <th>Name</th>
                        <th>Time</th>
                        <th>Days</th>
                        <th>Hours/Week</th>
                        <th>Assigned Nurses</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $schedule)
                    <tr class="hover">
                        {{-- NAME --}}
                        <td>
                            <div class="font-bold text-lg">{{ $schedule->name }}</div>
                        </td>

                        {{-- TIME --}}
                        <td class="font-mono text-primary font-bold">
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - 
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                        </td>

                        {{-- DAYS --}}
                        <td>
                            <div class="flex gap-1 flex-wrap">
                                <span class="badge {{ $schedule->monday ? 'badge-primary text-white' : 'badge-ghost' }}">M</span>
                                <span class="badge {{ $schedule->tuesday ? 'badge-primary text-white' : 'badge-ghost' }}">T</span>
                                <span class="badge {{ $schedule->wednesday ? 'badge-primary text-white' : 'badge-ghost' }}">W</span>
                                <span class="badge {{ $schedule->thursday ? 'badge-primary text-white' : 'badge-ghost' }}">TH</span>
                                <span class="badge {{ $schedule->friday ? 'badge-primary text-white' : 'badge-ghost' }}">F</span>
                                <span class="badge {{ $schedule->saturday ? 'badge-primary text-white' : 'badge-ghost' }}">SA</span>
                                <span class="badge {{ $schedule->sunday ? 'badge-primary text-white' : 'badge-ghost' }}">SU</span>
                            </div>
                        </td>

                        {{-- HOURS/WEEK --}}
                        <td>
                            <span class="font-bold text-lg">{{ $schedule->total_hours_per_week }}</span>
                            <span class="text-gray-400 text-sm">hrs</span>
                        </td>

                        {{-- ASSIGNED NURSES --}}
                        <td>
                            @if($schedule->nurses_count > 0)
                                <span class="badge badge-info text-white">{{ $schedule->nurses_count }} nurse(s)</span>
                            @else
                                <span class="badge badge-ghost">None</span>
                            @endif
                        </td>

                        {{-- ACTIONS --}}
                        <td class="text-right">
                            <div class="flex justify-end gap-2">
                                {{-- Edit Button --}}
                                <button 
                                    @click="openEditModal({
                                        id: {{ $schedule->id }},
                                        name: '{{ addslashes($schedule->name) }}',
                                        start_time: '{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}',
                                        end_time: '{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}',
                                        monday: {{ $schedule->monday ? 'true' : 'false' }},
                                        tuesday: {{ $schedule->tuesday ? 'true' : 'false' }},
                                        wednesday: {{ $schedule->wednesday ? 'true' : 'false' }},
                                        thursday: {{ $schedule->thursday ? 'true' : 'false' }},
                                        friday: {{ $schedule->friday ? 'true' : 'false' }},
                                        saturday: {{ $schedule->saturday ? 'true' : 'false' }},
                                        sunday: {{ $schedule->sunday ? 'true' : 'false' }}
                                    })"
                                    class="btn btn-sm btn-outline btn-primary gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                    Edit
                                </button>

                                {{-- Delete Button --}}
                                @if($schedule->nurses_count === 0)
                                <form action="{{ route('nurse.headnurse.shifts.destroy', $schedule->id) }}" method="POST"
                                      onsubmit="return confirm('Delete this shift schedule permanently?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline text-rose-600 hover:bg-red-100 border-rose-500 gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                                @else
                                <div class="tooltip" data-tip="Cannot delete - nurses assigned">
                                    <button class="btn btn-sm btn-outline btn-disabled gap-1" disabled>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-400 italic">
                            <div class="flex flex-col items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-300">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <p>No shift schedules created yet.</p>
                                <button @click="openCreateModal()" class="btn btn-sm btn-primary mt-2">
                                    Create Your First Schedule
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4">
            {{ $schedules->links() }}
        </div>
    </div>

    {{-- CREATE MODAL --}}
    <dialog id="create_modal" class="modal" x-ref="createModal">
        <div class="modal-box">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            
            <h3 class="font-bold text-xl mb-4">Create Shift Schedule</h3>
            
            <form action="{{ route('nurse.headnurse.shifts.store') }}" method="POST" class="space-y-4">
                @csrf
                
                {{-- Name --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Schedule Name</span>
                    </label>
                    <input type="text" name="name" x-model="form.name"
                           placeholder="e.g., Morning Shift, Night Duty"
                           class="input input-bordered w-full" required>
                    @error('name')
                        <span class="text-error text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                {{-- Time Range --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Start Time</span>
                        </label>
                        <input type="time" name="start_time" x-model="form.start_time"
                               class="input input-bordered w-full" required>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">End Time</span>
                        </label>
                        <input type="time" name="end_time" x-model="form.end_time"
                               class="input input-bordered w-full" required>
                    </div>
                </div>

                {{-- Days --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Working Days</span>
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <label class="label cursor-pointer gap-2 bg-base-200 rounded-lg px-3 py-2">
                            <input type="checkbox" name="monday" x-model="form.monday" value="1" class="checkbox checkbox-primary checkbox-sm">
                            <span class="label-text">Mon</span>
                        </label>
                        <label class="label cursor-pointer gap-2 bg-base-200 rounded-lg px-3 py-2">
                            <input type="checkbox" name="tuesday" x-model="form.tuesday" value="1" class="checkbox checkbox-primary checkbox-sm">
                            <span class="label-text">Tue</span>
                        </label>
                        <label class="label cursor-pointer gap-2 bg-base-200 rounded-lg px-3 py-2">
                            <input type="checkbox" name="wednesday" x-model="form.wednesday" value="1" class="checkbox checkbox-primary checkbox-sm">
                            <span class="label-text">Wed</span>
                        </label>
                        <label class="label cursor-pointer gap-2 bg-base-200 rounded-lg px-3 py-2">
                            <input type="checkbox" name="thursday" x-model="form.thursday" value="1" class="checkbox checkbox-primary checkbox-sm">
                            <span class="label-text">Thu</span>
                        </label>
                        <label class="label cursor-pointer gap-2 bg-base-200 rounded-lg px-3 py-2">
                            <input type="checkbox" name="friday" x-model="form.friday" value="1" class="checkbox checkbox-primary checkbox-sm">
                            <span class="label-text">Fri</span>
                        </label>
                        <label class="label cursor-pointer gap-2 bg-base-200 rounded-lg px-3 py-2">
                            <input type="checkbox" name="saturday" x-model="form.saturday" value="1" class="checkbox checkbox-primary checkbox-sm">
                            <span class="label-text">Sat</span>
                        </label>
                        <label class="label cursor-pointer gap-2 bg-base-200 rounded-lg px-3 py-2">
                            <input type="checkbox" name="sunday" x-model="form.sunday" value="1" class="checkbox checkbox-primary checkbox-sm">
                            <span class="label-text">Sun</span>
                        </label>
                    </div>
                    @error('days')
                        <span class="text-error text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Calculated Hours --}}
                <div class="alert bg-base-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <div>
                        <div class="text-sm font-semibold">Total Hours Per Week</div>
                        <div class="text-2xl font-bold text-primary" x-text="calculateTotalHours() + ' hours'"></div>
                    </div>
                </div>
                
                <div class="modal-action">
                    <button type="submit" class="btn btn-primary w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Create Schedule
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    {{-- EDIT MODAL --}}
    <dialog id="edit_modal" class="modal" x-ref="editModal">
        <div class="modal-box">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            
            <h3 class="font-bold text-xl mb-4">Edit Shift Schedule</h3>
            
            <form :action="'{{ route('nurse.headnurse.shifts.update', '') }}/' + editForm.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                {{-- Name --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Schedule Name</span>
                    </label>
                    <input type="text" name="name" x-model="editForm.name"
                           class="input input-bordered w-full" required>
                </div>
                
                {{-- Time Range --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Start Time</span>
                        </label>
                        <input type="time" name="start_time" x-model="editForm.start_time"
                               class="input input-bordered w-full" required>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">End Time</span>
                        </label>
                        <input type="time" name="end_time" x-model="editForm.end_time"
                               class="input input-bordered w-full" required>
                    </div>
                </div>

                {{-- Days --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Working Days</span>
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <label class="label cursor-pointer gap-2 bg-base-200 rounded-lg px-3 py-2">
                            <input type="checkbox" name="monday" x-model="editForm.monday" value="1" class="checkbox checkbox-primary checkbox-sm">
                            <span class="label-text">Mon</span>
                        </label>
                        <label class="label cursor-pointer gap-2 bg-base-200 rounded-lg px-3 py-2">
                            <input type="checkbox" name="tuesday" x-model="editForm.tuesday" value="1" class="checkbox checkbox-primary checkbox-sm">
                            <span class="label-text">Tue</span>
                        </label>
                        <label class="label cursor-pointer gap-2 bg-base-200 rounded-lg px-3 py-2">
                            <input type="checkbox" name="wednesday" x-model="editForm.wednesday" value="1" class="checkbox checkbox-primary checkbox-sm">
                            <span class="label-text">Wed</span>
                        </label>
                        <label class="label cursor-pointer gap-2 bg-base-200 rounded-lg px-3 py-2">
                            <input type="checkbox" name="thursday" x-model="editForm.thursday" value="1" class="checkbox checkbox-primary checkbox-sm">
                            <span class="label-text">Thu</span>
                        </label>
                        <label class="label cursor-pointer gap-2 bg-base-200 rounded-lg px-3 py-2">
                            <input type="checkbox" name="friday" x-model="editForm.friday" value="1" class="checkbox checkbox-primary checkbox-sm">
                            <span class="label-text">Fri</span>
                        </label>
                        <label class="label cursor-pointer gap-2 bg-base-200 rounded-lg px-3 py-2">
                            <input type="checkbox" name="saturday" x-model="editForm.saturday" value="1" class="checkbox checkbox-primary checkbox-sm">
                            <span class="label-text">Sat</span>
                        </label>
                        <label class="label cursor-pointer gap-2 bg-base-200 rounded-lg px-3 py-2">
                            <input type="checkbox" name="sunday" x-model="editForm.sunday" value="1" class="checkbox checkbox-primary checkbox-sm">
                            <span class="label-text">Sun</span>
                        </label>
                    </div>
                </div>

                {{-- Calculated Hours --}}
                <div class="alert bg-base-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <div>
                        <div class="text-sm font-semibold">Total Hours Per Week</div>
                        <div class="text-2xl font-bold text-primary" x-text="calculateEditTotalHours() + ' hours'"></div>
                    </div>
                </div>
                
                <div class="modal-action">
                    <button type="submit" class="btn btn-primary w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        Update Schedule
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

</div>

<script>
function shiftScheduleManager() {
    return {
        form: {
            name: '',
            start_time: '08:00',
            end_time: '16:00',
            monday: true,
            tuesday: true,
            wednesday: true,
            thursday: true,
            friday: true,
            saturday: false,
            sunday: false
        },
        editForm: {
            id: null,
            name: '',
            start_time: '',
            end_time: '',
            monday: false,
            tuesday: false,
            wednesday: false,
            thursday: false,
            friday: false,
            saturday: false,
            sunday: false
        },

        openCreateModal() {
            this.resetForm();
            this.$refs.createModal.showModal();
        },

        openEditModal(schedule) {
            this.editForm = { ...schedule };
            this.$refs.editModal.showModal();
        },

        resetForm() {
            this.form = {
                name: '',
                start_time: '08:00',
                end_time: '16:00',
                monday: true,
                tuesday: true,
                wednesday: true,
                thursday: true,
                friday: true,
                saturday: false,
                sunday: false
            };
        },

        calculateTotalHours() {
            return this._calculateHours(this.form);
        },

        calculateEditTotalHours() {
            return this._calculateHours(this.editForm);
        },

        _calculateHours(formData) {
            if (!formData.start_time || !formData.end_time) return 0;

            const [startH, startM] = formData.start_time.split(':').map(Number);
            const [endH, endM] = formData.end_time.split(':').map(Number);
            
            let startMinutes = startH * 60 + startM;
            let endMinutes = endH * 60 + endM;
            
            // Handle overnight shifts
            if (endMinutes <= startMinutes) {
                endMinutes += 24 * 60;
            }
            
            const hoursPerDay = (endMinutes - startMinutes) / 60;
            
            const daysCount = [
                formData.monday,
                formData.tuesday,
                formData.wednesday,
                formData.thursday,
                formData.friday,
                formData.saturday,
                formData.sunday
            ].filter(Boolean).length;
            
            return Math.round(hoursPerDay * daysCount * 10) / 10;
        }
    }
}
</script>
@endsection
