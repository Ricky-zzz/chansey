@extends('layouts.physician')

@section('content')
<div class="max-w-7xl mx-auto bg-white rounded-lg p-6 shadow-xl border border-slate-200">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-black text-slate-800">My Availability Schedule</h2>
            <p class="text-sm text-gray-500">Manage your appointment slots for patients to book</p>
        </div>
        <button onclick="create_slot_modal.showModal()" class="btn text-white btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="text-white w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Open New Slot
        </button>
    </div>

    {{-- Date Range Filter --}}
    <div class="card bg-white shadow-sm border border-slate-200 mb-6 p-4">
        <form action="{{ route('physician.slots.index') }}" method="GET" class="flex gap-4 items-end">
            <div class="form-control flex-1">
                <label class="label">
                    <span class="label-text font-semibold text-black">From Date</span>
                </label>
                <input type="date" name="from_date"
                       value="{{ $fromDate }}"
                       class="input input-bordered w-full" />
            </div>

            <div class="form-control flex-1">
                <label class="label">
                    <span class="label-text font-semibold text-black">To Date</span>
                </label>
                <input type="date" name="to_date"
                       value="{{ $toDate }}"
                       class="input input-bordered w-full" />
            </div>

            <button type="submit" class="btn btn-outline bg-sky-200 gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.5 5.5a7.5 7.5 0 0 0 10.5 10.5Z" />
                </svg>
                Filter
            </button>

            <a href="{{ route('physician.slots.index') }}" class="btn btn-outline bg-amber-200 gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Reset
            </a>
        </form>
    </div>

    {{-- Slots Table --}}
    <div class="card bg-white shadow-sm border border-slate-200">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead class="bg-slate-800 text-white uppercase text-xs">
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($slots as $slot)
                    <tr class="hover">
                        {{-- DATE --}}
                        <td>
                            <div class="font-bold">{{ $slot->formatted_date }}</div>
                            <div class="text-xs text-gray-400">{{ $slot->formatted_day }}</div>
                        </td>

                        {{-- TIME --}}
                        <td class="font-mono text-primary font-bold">
                            {{ $slot->formatted_start_time }} - {{ $slot->formatted_end_time }}
                        </td>

                        {{-- CAPACITY with Progress Bar --}}
                        <td>
                            <div class="flex items-center gap-2">
                                <progress
                                    class="progress {{ $slot->appointments_count >= $slot->capacity ? 'progress-error' : 'progress-primary' }} w-24"
                                    value="{{ $slot->appointments_count }}"
                                    max="{{ $slot->capacity }}">
                                </progress>
                                <span class="text-xs font-bold {{ $slot->appointments_count >= $slot->capacity ? 'text-error' : '' }}">
                                    {{ $slot->appointments_count }} / {{ $slot->capacity }}
                                </span>
                            </div>
                            <div class="text-xs text-gray-400 mt-1">
                                @if($slot->appointments_count >= $slot->capacity)
                                    <span class="text-error">Fully Booked</span>
                                @else
                                    {{ $slot->capacity - $slot->appointments_count }} remaining
                                @endif
                            </div>
                        </td>

                        {{-- STATUS --}}
                        <td>
                            @if($slot->status === 'Cancelled')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold text-white bg-rose-600 border border-rose-600">Cancelled</span>
                            @elseif(\Carbon\Carbon::parse($slot->date)->toDateString() < today()->toDateString())
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold text-gray-600 bg-gray-100 border border-gray-200">Past</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold text-white bg-emerald-600 border border-emerald-600">Active</span>
                            @endif
                        </td>

                        {{-- ACTIONS --}}
                        <td class="text-right">
                            <div class="flex justify-end gap-2">
                                {{-- View Patients Button --}}
                                @if($slot->appointments_count > 0)
                                    <button
                                        data-slot-id="{{ $slot->id }}"
                                        onclick="openViewModal(this.dataset.slotId)"
                                        class="btn btn-sm btn-outline btn-primary gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                        View
                                    </button>
                                @endif

                                {{-- Cancel Button (only for active slots) --}}
                                @if($slot->status === 'Active' && $slot->date >= today())
                                    <form action="{{ route('physician.slots.cancel', $slot->id) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to cancel this slot? All patients who booked will be notified.')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline btn-outlone bg-rose-200 gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                            </svg>
                                            Cancel
                                        </button>
                                    </form>
                                @endif

                                {{-- Delete Button (only if no appointments) --}}
                                @if($slot->appointments_count === 0 && $slot->status !== 'Cancelled')
                                    <form action="{{ route('physician.slots.destroy', $slot->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this slot permanently?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline  text-rose-600 hover:bg-red-100 border border-rose-500  gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-400 italic">
                            <div class="flex flex-col items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-300">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                </svg>
                                <p>No appointment slots created yet.</p>
                                <button onclick="create_slot_modal.showModal()" class="btn btn-sm btn-primary mt-2">
                                    Create Your First Slot
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- CREATE SLOT MODAL --}}
<dialog id="create_slot_modal" class="modal">
    <div class="modal-box">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>

        <h3 class="font-bold text-xl mb-4">Open New Appointment Slot</h3>

        <form action="{{ route('physician.slots.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Date --}}
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Date</span>
                </label>
                <input type="date" name="date"
                       min="{{ now()->format('Y-m-d') }}"
                       value="{{ old('date', now()->addDay()->format('Y-m-d')) }}"
                       class="input input-bordered w-full" required>
                @error('date')
                    <span class="text-error text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Time Range --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Start Time</span>
                    </label>
                    <input type="time" name="start_time"
                           value="{{ old('start_time', '08:00') }}"
                           class="input input-bordered w-full" required>
                    @error('start_time')
                        <span class="text-error text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">End Time</span>
                    </label>
                    <input type="time" name="end_time"
                           value="{{ old('end_time', '12:00') }}"
                           class="input input-bordered w-full" required>
                    @error('end_time')
                        <span class="text-error text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Capacity --}}
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Maximum Patients</span>
                </label>
                <input type="number" name="capacity"
                       min="1" max="50"
                       value="{{ old('capacity', 10) }}"
                       class="input input-bordered w-full" required>
                <label class="label">
                    <span class="label-text-alt text-gray-400">How many patients can book this slot?</span>
                </label>
                @error('capacity')
                    <span class="text-error text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="modal-action">
                <button type="submit" class="btn btn-primary w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Create Slot
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

{{-- VIEW APPOINTMENTS MODAL --}}
<div x-data="viewAppointmentsModal()" x-cloak @open-view-modal.window="open($event.detail)">
    <dialog id="view_appointments_modal" class="modal" x-ref="modal">
        <div class="modal-box max-w-3xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>

            <h3 class="font-bold text-xl mb-2">Booked Patients</h3>
            <p class="text-sm text-gray-500 mb-4" x-text="slotInfo"></p>

            {{-- Loading State --}}
            <div x-show="loading" class="flex justify-center py-8">
                <span class="loading loading-spinner loading-lg text-primary"></span>
            </div>

            {{-- Appointments List --}}
            <div x-show="!loading && appointments.length > 0">
                <table class="table table-zebra w-full">
                    <thead class="bg-slate-100">
                        <tr>
                            <th>#</th>
                            <th>Patient Name</th>
                            <th>Contact</th>
                            <th>Purpose</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(app, index) in appointments" :key="app.id">
                            <tr>
                                <td class="font-bold" x-text="index + 1"></td>
                                <td>
                                    <div class="font-bold" x-text="app.last_name + ', ' + app.first_name"></div>
                                    <div class="text-xs text-gray-400" x-text="app.email || 'No email'"></div>
                                </td>
                                <td class="font-mono text-sm" x-text="app.contact_number"></td>
                                <td class="max-w-xs truncate text-sm italic text-gray-600">
                                    "<span x-text="app.purpose"></span>"
                                </td>
                                <td>
                                    <span class="badge"
                                          :class="{
                                              'badge-info': app.status === 'Booked',
                                              'badge-error text-white': app.status === 'Cancelled',
                                              'badge-success text-white': app.status === 'Completed',
                                              'badge-ghost': !['Booked', 'Cancelled', 'Completed'].includes(app.status)
                                          }"
                                          x-text="app.status">
                                    </span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            {{-- Empty State --}}
            <div x-show="!loading && appointments.length === 0" class="text-center py-8 text-gray-400">
                No appointments found for this slot.
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</div>

@endsection

@push('scripts')
<script>
function viewAppointmentsModal() {
    return {
        loading: false,
        appointments: [],
        slotInfo: '',

        open(slotId) {
            this.loading = true;
            this.appointments = [];
            this.slotInfo = '';
            this.$refs.modal.showModal();

            fetch(`{{ url('physician/slots') }}/${slotId}`)
                .then(response => response.json())
                .then(data => {
                    this.loading = false;
                    this.appointments = data.appointments;

                    const slot = data.slot;
                    const date = new Date(slot.date).toLocaleDateString('en-US', {
                        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
                    });
                    this.slotInfo = `${date} • ${this.formatTime(slot.start_time)} - ${this.formatTime(slot.end_time)}`;
                })
                .catch(error => {
                    this.loading = false;
                    console.error('Error:', error);
                });
        },

        formatTime(timeString) {
            const [hours, minutes] = timeString.split(':');
            const date = new Date();
            date.setHours(parseInt(hours), parseInt(minutes));
            return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
        }
    }
}

// Global function using window event dispatch
window.openViewModal = function(slotId) {
    window.dispatchEvent(new CustomEvent('open-view-modal', { detail: slotId }));
}
</script>
@endpush
