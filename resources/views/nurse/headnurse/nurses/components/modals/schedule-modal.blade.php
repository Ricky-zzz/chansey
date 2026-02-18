{{-- Assign Schedule Modal --}}
<dialog id="schedule_modal" class="modal" x-ref="scheduleModal">
    <div class="modal-enterprise" style="transform: translateY(-15vh);">
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
