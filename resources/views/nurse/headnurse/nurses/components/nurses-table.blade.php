{{-- Nurses Table View --}}
<div class="card-enterprise overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table-enterprise">
            <thead>
                <tr>
                    <th></th>
                    <th>Badge ID</th>
                    <th>Name</th>
                    <th>Station</th>
                    <th>Patient Ratio</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nurses as $nurse)
                <tr data-nurse-id="{{ $nurse->id }}">
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

                    {{-- PATIENT RATIO --}}
                    <td>
                        <button @click="viewNursePatients({{ $nurse->id }}, '{{ $nurse->first_name }} {{ $nurse->last_name }}')"
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-mono font-bold hover:bg-emerald-100 transition-colors">
                            <span id="ratio-{{ $nurse->id }}">—</span>
                        </button>
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
                                @click="openAssignPatientModal({ id: {{ $nurse->id }}, name: '{{ $nurse->first_name }} {{ $nurse->last_name }}' })"
                                class="btn-enterprise-info text-xs px-3 py-1.5 inline-flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                Assign Patient
                            </button>
                            <button
                                @click="openViewSchedulesModal({
                                    id: {{ $nurse->id }},
                                    name: '{{ $nurse->first_name }} {{ $nurse->last_name }}'
                                })"
                                class="btn-enterprise-primary text-xs px-3 py-1.5 inline-flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                See Schedules
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-10">
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
