@props(['clinicalLogs', 'admission' => null, 'displayMode' => 'physician']) 

<div class="card bg-base-100 shadow-xl border border-base-200">
    <div class="card-body p-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="card-title text-slate-700 text-lg">
                {{ $displayMode === 'nurse' ? 'Clinical History' : 'Clinical Log History' }}
            </h3>
            <div class="flex gap-2 flex-wrap justify-end">
                @php
                    $currentType = request('type');
                    $routeName = $displayMode === 'nurse' ? 'nurse.clinical.ward.show' : 'physician.mypatients.show';
                    $patientId = $admission?->id ?? request()->route('id');
                @endphp
                <a href="{{ route($routeName, ['id' => $patientId]) }}" 
                   class="btn btn-xs {{ !$currentType ? 'btn-active' : 'btn-outline' }}">
                    All
                </a>
                <a href="{{ route($routeName, ['id' => $patientId, 'type' => 'Medication']) }}" 
                   class="btn btn-xs {{ $currentType === 'Medication' ? 'btn-active' : 'btn-outline' }}">
                    Medication
                </a>
                <a href="{{ route($routeName, ['id' => $patientId, 'type' => 'Vitals']) }}" 
                   class="btn btn-xs {{ $currentType === 'Vitals' ? 'btn-active' : 'btn-outline' }}">
                    Vitals
                </a>
                <a href="{{ route($routeName, ['id' => $patientId, 'type' => 'Laboratory']) }}" 
                   class="btn btn-xs {{ $currentType === 'Laboratory' ? 'btn-active' : 'btn-outline' }}">
                    Lab
                </a>
                <a href="{{ route($routeName, ['id' => $patientId, 'type' => 'Transfer']) }}" 
                   class="btn btn-xs {{ $currentType === 'Transfer' ? 'btn-active' : 'btn-outline' }}">
                    Transfer
                </a>
                <a href="{{ route($routeName, ['id' => $patientId, 'type' => 'Utility']) }}" 
                   class="btn btn-xs {{ $currentType === 'Utility' ? 'btn-active' : 'btn-outline' }}">
                    Utility
                </a>
                <a href="{{ route($routeName, ['id' => $patientId, 'type' => 'Discharge']) }}" 
                   class="btn btn-xs {{ $currentType === 'Discharge' ? 'btn-active' : 'btn-outline' }}">
                    Discharge
                </a>
            </div>
        </div>

        <div class="overflow-x-auto max-h-96">
            <table class="table table-sm">
                <thead class="text-slate-600 sticky top-0 bg-base-100">
                    <tr>
                        <th>Time</th>
                        <th>Type</th>
                        <th>Data</th>
                        <th>Staff</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clinicalLogs as $log)
                    <tr class="hover:bg-slate-50 cursor-pointer" @click="viewLog({
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
                        <td class="font-mono text-xs">{{ $log->created_at->format('M d H:i') }}</td>
                        <td>
                            <div class="px-2 py-1 rounded-full text-xs font-bold text-white inline-block
                                {{ $log->type === 'Medication' ? 'bg-emerald-600' : 
                                  ($log->type === 'Vitals' ? 'bg-sky-600' : 
                                  ($log->type === 'Laboratory' ? 'bg-amber-600' :
                                  ($log->type === 'Transfer' ? 'bg-rose-600' :
                                  ($log->type === 'Utility' ? 'bg-purple-600' :
                                  ($log->type === 'Discharge' ? 'bg-lime-600' : 'bg-slate-400')))))}}">
                                {{ $log->type }}
                            </div>
                        </td>
                        <td class="text-xs max-w-xs">
                            @if($log->type === 'Medication')
                                Given: <strong>{{ $log->data['medicine'] ?? 'Unknown' }}</strong> ({{ $log->data['dosage'] ?? '--' }})
                            @elseif($log->type === 'Vitals')
                                BP: {{ $log->data['bp_systolic'] }}/{{ $log->data['bp_diastolic'] }} | T: {{ $log->data['temp'] }}
                            @elseif($log->type === 'Laboratory')
                                Uploaded: <strong>{{ $log->data['note'] ?? 'Lab Result' }}</strong>
                                @if($log->labResultFile)
                                <div class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $log->labResultFile->file_name }}</div>
                                @endif
                            @elseif($log->type === 'Transfer')
                                Moved: <strong>{{ $log->data['from_bed'] ?? 'Unknown' }}</strong> → <strong>{{ $log->data['to_bed'] ?? 'Unknown' }}</strong>
                            @elseif($log->type === 'Discharge')
                                {{ $log->data['note'] ?? 'Patient discharged' }}
                            @else
                                {{ $log->data['observation'] ?? ($log->data['note'] ?? 'No Data') }}
                            @endif
                        </td>
                        <td class="text-xs text-gray-500">{{ $log->user->name ?? 'Unknown' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-gray-400 italic py-6">No clinical logs recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Controls -->
        @if($clinicalLogs->hasPages())
        <div class="mt-4 pt-4 border-t border-base-200 flex justify-between items-center text-xs">
            <span class="text-gray-500">
                Showing {{ $clinicalLogs->firstItem() ?? 0 }} to {{ $clinicalLogs->lastItem() ?? 0 }} of {{ $clinicalLogs->total() }} logs
            </span>
            <div class="join">
                @if($clinicalLogs->onFirstPage())
                    <button class="join-item btn btn-xs btn-disabled">Previous</button>
                @else
                    <a href="{{ $clinicalLogs->previousPageUrl() }}" class="join-item btn btn-xs btn-outline">Previous</a>
                @endif

                @foreach($clinicalLogs->getUrlRange(1, $clinicalLogs->lastPage()) as $page => $url)
                    @if($page == $clinicalLogs->currentPage())
                        <button class="join-item btn btn-xs btn-active">{{ $page }}</button>
                    @else
                        <a href="{{ $url }}" class="join-item btn btn-xs btn-outline">{{ $page }}</a>
                    @endif
                @endforeach

                @if($clinicalLogs->hasMorePages())
                    <a href="{{ $clinicalLogs->nextPageUrl() }}" class="join-item btn btn-xs btn-outline">Next</a>
                @else
                    <button class="join-item btn btn-xs btn-disabled">Next</button>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
