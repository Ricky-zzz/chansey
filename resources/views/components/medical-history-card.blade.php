@props(['admission'])

<div class="card-enterprise">
    <div class="collapse collapse-arrow">
        <input type="checkbox" class="peer" />
        <div class="collapse-title bg-slate-50 font-bold text-slate-800 peer-checked:bg-slate-100">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Medical History</span>
                <div class="flex gap-2 ml-auto">
                    @if(!empty($admission->medication_history))
                    <span class="badge badge-sm badge-blue">{{ count($admission->medication_history) }} Meds</span>
                    @endif
                    @if(!empty($admission->past_medical_history))
                    <span class="badge badge-sm badge-warning">{{ count($admission->past_medical_history) }} Conditions</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="collapse-content space-y-6 pt-4">
            <!-- MEDICATION HISTORY -->
            <div>
                <h4 class="text-sm font-bold text-slate-700 uppercase mb-3 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.452a6 6 0 00-3.86.454l-.432.143m9.821.821a9.88 9.88 0 00-1.638-2.362m0 0a9.88 9.88 0 00-13.26 2.362m13.26-2.362a9.885 9.885 0 001.638 2.362m0 0A9.908 9.908 0 0121 12a9.906 9.906 0 01-1.639-4.874m0 0a6 6 0 00-3.860.454l-.431.143m9.821.821A9.88 9.88 0 0012 21c-4.97 0-9.26-3.244-10.973-7.6m16.946 0a9.865 9.865 0 01-1.622 2.073m-5.694-5.217a40.007 40.007 0 00-7.964 3.07M12 12a9.882 9.882 0 01-8.968 4.923c.07-.204.147-.402.231-.586m5.211-7.653a9.88 9.88 0 012.043-.645" />
                    </svg>
                    Medication History
                </h4>

                @if(!empty($admission->medication_history) && count($admission->medication_history) > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($admission->medication_history as $medication)
                    <span class="badge badge-lg badge-blue bg-blue-50 text-blue-700 border border-blue-200">
                        {{ $medication }}
                    </span>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4 text-slate-500 text-sm italic bg-slate-50 rounded-lg">
                    No medications recorded.
                </div>
                @endif
            </div>

            <!-- PAST MEDICAL HISTORY -->
            <div class="border-t border-slate-200 pt-6">
                <h4 class="text-sm font-bold text-slate-700 uppercase mb-3 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Past Medical History
                </h4>

                @if(!empty($admission->past_medical_history) && count($admission->past_medical_history) > 0)
                <div class="overflow-x-auto">
                    <table class="table table-sm table-compact w-full">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="text-left text-xs font-bold text-slate-700">Type</th>
                                <th class="text-left text-xs font-bold text-slate-700">Description</th>
                                <th class="text-left text-xs font-bold text-slate-700">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admission->past_medical_history as $record)
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="text-sm font-semibold text-slate-700 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded-full text-xs font-bold inline-block
                                        @switch($record['type'])
                                            @case('Existing Illness')
                                                bg-red-100 text-red-700
                                                @break
                                            @case('Past Hospitalization')
                                                bg-orange-100 text-orange-700
                                                @break
                                            @case('Past Surgery')
                                                bg-purple-100 text-purple-700
                                                @break
                                            @case('Hereditary Condition')
                                                bg-pink-100 text-pink-700
                                                @break
                                            @default
                                                bg-slate-100 text-slate-700
                                        @endswitch
                                    ">
                                        {{ $record['type'] ?? 'Other' }}
                                    </span>
                                </td>
                                <td class="text-sm text-slate-600 max-w-xs">
                                    {{ $record['description'] ?? 'N/A' }}
                                </td>
                                <td class="text-sm text-slate-600 whitespace-nowrap">
                                    @if($record['date'] && $record['date'] !== 'Unknown')
                                        {{ \Carbon\Carbon::parse($record['date'])->format('M d, Y') }}
                                    @else
                                        --
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4 text-slate-500 text-sm italic bg-slate-50 rounded-lg">
                    No medical history recorded.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
