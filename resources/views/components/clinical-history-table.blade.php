@props(['clinicalLogs', 'admission' => null, 'displayMode' => 'physician'])

<div class="card-enterprise">
    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-slate-800 text-base">
                {{ $displayMode === 'nurse' ? 'Clinical History' : 'Clinical Log History' }}
            </h3>
            <div class="flex gap-1.5 flex-wrap justify-end">
                @php
                    $currentType = request('type');
                    $routeName = $displayMode === 'nurse' ? 'nurse.clinical.ward.show' : 'physician.mypatients.show';
                    $patientId = $admission?->id ?? request()->route('id');
                @endphp
                <a href="{{ route($routeName, ['id' => $patientId]) }}"
                   class="badge-enterprise text-xs {{ !$currentType ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} cursor-pointer transition-colors">
                    All
                </a>
                <a href="{{ route($routeName, ['id' => $patientId, 'type' => 'Medication']) }}"
                   class="badge-enterprise text-xs {{ $currentType === 'Medication' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} cursor-pointer transition-colors">
                    Medication
                </a>
                <a href="{{ route($routeName, ['id' => $patientId, 'type' => 'Vitals']) }}"
                   class="badge-enterprise text-xs {{ $currentType === 'Vitals' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} cursor-pointer transition-colors">
                    Vitals
                </a>
                <a href="{{ route($routeName, ['id' => $patientId, 'type' => 'Laboratory']) }}"
                   class="badge-enterprise text-xs {{ $currentType === 'Laboratory' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} cursor-pointer transition-colors">
                    Lab
                </a>
                <a href="{{ route($routeName, ['id' => $patientId, 'type' => 'Transfer']) }}"
                   class="badge-enterprise text-xs {{ $currentType === 'Transfer' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} cursor-pointer transition-colors">
                    Transfer
                </a>
                <a href="{{ route($routeName, ['id' => $patientId, 'type' => 'Utility']) }}"
                   class="badge-enterprise text-xs {{ $currentType === 'Utility' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} cursor-pointer transition-colors">
                    Utility
                </a>
                <a href="{{ route($routeName, ['id' => $patientId, 'type' => 'Discharge']) }}"
                   class="badge-enterprise text-xs {{ $currentType === 'Discharge' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} cursor-pointer transition-colors">
                    Discharge
                </a>
            </div>
        </div>

        <div class="overflow-x-auto max-h-96">
            <table class="table-enterprise table-sm">
                <thead class="sticky top-0">
                    <tr>
                        <th>Time</th>
                        <th>Type</th>
                        <th>Data</th>
                        <th>Staff</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clinicalLogs as $log)
                    <tr class="cursor-pointer" @click="viewLog({
                        type: '{{ $log->type }}',
                        data: {{ json_encode($log->data) }},
                        created_at: '{{ $log->created_at }}',
                        user: {
                            name: '{{ $log->user->name ?? 'Unknown' }}'
                        }
                        {{ $log->type === 'Laboratory' && $log->labResultFile ? ',labResultFile: ' . json_encode([
                            'id' => $log->labResultFile->id,
                            'file_name' => $log->labResultFile->file_name,
                            'file_size_readable' => $log->labResultFile->file_size_readable ?? 'Unknown'
                        ]) : '' }}
                    })">
                        <td class="font-mono text-xs text-slate-600">{{ $log->created_at->format('M d H:i') }}</td>
                        <td>
                            <span class="badge-enterprise text-xs
                                {{ $log->type === 'Medication' ? 'bg-emerald-100 text-emerald-700' :
                                  ($log->type === 'Vitals' ? 'bg-sky-100 text-sky-700' :
                                  ($log->type === 'Laboratory' ? 'bg-amber-100 text-amber-700' :
                                  ($log->type === 'Transfer' ? 'bg-rose-100 text-rose-700' :
                                  ($log->type === 'Utility' ? 'bg-purple-100 text-purple-700' :
                                  ($log->type === 'Discharge' ? 'bg-lime-100 text-lime-700' : 'bg-slate-100 text-slate-600')))))}}">
                                {{ $log->type }}
                            </span>
                        </td>
                        <td class="text-xs max-w-xs text-slate-600">
                            @if($log->type === 'Medication')
                                Given: <strong class="text-slate-800">{{ $log->data['medicine'] ?? 'Unknown' }}</strong> ({{ $log->data['dosage'] ?? '--' }})
                            @elseif($log->type === 'Vitals')
                                BP: {{ $log->data['bp'] ?? '--' }} | T: {{ $log->data['temp'] ?? '--' }}°C
                            @elseif($log->type === 'Laboratory')
                                Uploaded: <strong class="text-slate-800">{{ $log->data['note'] ?? 'Lab Result' }}</strong>
                                @if($log->labResultFile)
                                <div class="text-xs text-slate-400 mt-1 line-clamp-1">{{ $log->labResultFile->file_name }}</div>
                                @endif
                            @elseif($log->type === 'Transfer')
                                Moved: <strong class="text-slate-800">{{ $log->data['from_bed'] ?? 'Unknown' }}</strong> → <strong class="text-slate-800">{{ $log->data['to_bed'] ?? 'Unknown' }}</strong>
                            @elseif($log->type === 'Discharge')
                                {{ $log->data['note'] ?? 'Patient discharged' }}
                            @else
                                {{ $log->data['observation'] ?? ($log->data['note'] ?? 'No Data') }}
                            @endif
                        </td>
                        <td class="text-xs text-slate-500">{{ $log->user->name ?? 'Unknown' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-slate-400 italic py-6 text-sm">No clinical logs recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Controls -->
        @if($clinicalLogs->hasPages())
        <div class="mt-4 pt-4 border-t border-slate-200 flex justify-between items-center text-xs">
            <span class="text-slate-500">
                Showing {{ $clinicalLogs->firstItem() ?? 0 }} to {{ $clinicalLogs->lastItem() ?? 0 }} of {{ $clinicalLogs->total() }} logs
            </span>
            <div class="flex gap-1">
                @if($clinicalLogs->onFirstPage())
                    <span class="btn-enterprise-secondary text-xs opacity-50 cursor-not-allowed px-2 py-1">Previous</span>
                @else
                    <a href="{{ $clinicalLogs->previousPageUrl() }}" class="btn-enterprise-secondary text-xs px-2 py-1">Previous</a>
                @endif

                @foreach($clinicalLogs->getUrlRange(1, $clinicalLogs->lastPage()) as $page => $url)
                    @if($page == $clinicalLogs->currentPage())
                        <span class="btn-enterprise-primary text-xs px-2 py-1">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="btn-enterprise-secondary text-xs px-2 py-1">{{ $page }}</a>
                    @endif
                @endforeach

                @if($clinicalLogs->hasMorePages())
                    <a href="{{ $clinicalLogs->nextPageUrl() }}" class="btn-enterprise-secondary text-xs px-2 py-1">Next</a>
                @else
                    <span class="btn-enterprise-secondary text-xs opacity-50 cursor-not-allowed px-2 py-1">Next</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
