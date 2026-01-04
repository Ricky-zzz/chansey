<!-- VIEW LOG DETAILS MODAL -->
<dialog id="view_log_modal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
        <h3 class="font-bold text-lg mb-4">Log Details</h3>
        
        <div class="grid grid-cols-2 gap-4 text-sm mb-4">
            <div>
                <span class="text-xs text-gray-400 uppercase font-bold block mb-1">Type</span>
                <div class="badge badge-lg font-bold" x-text="viewLogData.type"></div>
            </div>
            <div>
                <span class="text-xs text-gray-400 uppercase font-bold block mb-1">Recorded By</span>
                <div class="font-semibold" x-text="viewLogData.user?.name ?? 'Unknown'"></div>
            </div>
            <div class="col-span-2">
                <span class="text-xs text-gray-400 uppercase font-bold block mb-1">Date & Time</span>
                <div class="font-mono" x-text="new Date(viewLogData.created_at).toLocaleString()"></div>
            </div>
        </div>

        <div class="divider"></div>

        <h4 class="text-xs text-gray-400 uppercase font-bold mb-3">Recorded Data</h4>
        <div class="bg-base-200 p-4 rounded-lg space-y-2">
            <!-- MEDICATION LOG LAYOUT -->
            <template x-if="viewLogData.type === 'Medication'">
                <div class="space-y-3">
                    <!-- Medicine Info -->
                    <template x-if="viewLogData.data.medicine">
                        <div class="flex justify-between text-sm border-b border-base-300 pb-2">
                            <span class="font-semibold text-slate-600">MEDICINE</span>
                            <span class="font-mono text-slate-800 font-bold" x-text="viewLogData.data.medicine"></span>
                        </div>
                    </template>
                    <template x-if="viewLogData.data.dosage">
                        <div class="flex justify-between text-sm border-b border-base-300 pb-2">
                            <span class="font-semibold text-slate-600">DOSAGE</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.dosage"></span>
                        </div>
                    </template>

                    <!-- Vitals Section -->
                    <div class="text-xs font-bold text-gray-500 mt-3 mb-2">Vital Signs</div>
                    <template x-if="viewLogData.data.bp_systolic">
                        <div class="flex justify-between text-sm border-b border-base-300 pb-2">
                            <span class="font-semibold text-slate-600">BP SYSTOLIC</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.bp_systolic"></span>
                        </div>
                    </template>
                    <template x-if="viewLogData.data.bp_diastolic">
                        <div class="flex justify-between text-sm border-b border-base-300 pb-2">
                            <span class="font-semibold text-slate-600">BP DIASTOLIC</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.bp_diastolic"></span>
                        </div>
                    </template>
                    <template x-if="viewLogData.data.temp">
                        <div class="flex justify-between text-sm border-b border-base-300 pb-2">
                            <span class="font-semibold text-slate-600">TEMP</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.temp"></span>
                        </div>
                    </template>
                    <template x-if="viewLogData.data.hr || viewLogData.data.heart_rate">
                        <div class="flex justify-between text-sm border-b border-base-300 pb-2">
                            <span class="font-semibold text-slate-600">HEART RATE</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.hr || viewLogData.data.heart_rate"></span>
                        </div>
                    </template>
                    <template x-if="viewLogData.data.o2 || viewLogData.data.o2_sat">
                        <div class="flex justify-between text-sm border-b border-base-300 pb-2">
                            <span class="font-semibold text-slate-600">O2 SAT (%)</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.o2 || viewLogData.data.o2_sat"></span>
                        </div>
                    </template>
                    <template x-if="viewLogData.data.rr || viewLogData.data.respiratory_rate">
                        <div class="flex justify-between text-sm border-b border-base-300 pb-2">
                            <span class="font-semibold text-slate-600">RESP RATE</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.rr || viewLogData.data.respiratory_rate"></span>
                        </div>
                    </template>

                    <!-- Remarks -->
                    <template x-if="viewLogData.data.observation || viewLogData.data.remarks">
                        <div class="flex justify-between text-sm border-b border-base-300 pb-2">
                            <span class="font-semibold text-slate-600">REMARKS</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.observation || viewLogData.data.remarks || '—'"></span>
                        </div>
                    </template>
                </div>
            </template>

            <!-- VITALS LOG LAYOUT -->
            <template x-if="viewLogData.type === 'Vitals'">
                <div class="space-y-3">
                    <!-- Vitals Section First -->
                    <div class="text-xs font-bold text-gray-500 mb-2">Vital Signs</div>
                    <template x-if="viewLogData.data.bp_systolic">
                        <div class="flex justify-between text-sm border-b border-base-300 pb-2">
                            <span class="font-semibold text-slate-600">BP SYSTOLIC</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.bp_systolic"></span>
                        </div>
                    </template>
                    <template x-if="viewLogData.data.bp_diastolic">
                        <div class="flex justify-between text-sm border-b border-base-300 pb-2">
                            <span class="font-semibold text-slate-600">BP DIASTOLIC</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.bp_diastolic"></span>
                        </div>
                    </template>
                    <template x-if="viewLogData.data.temp">
                        <div class="flex justify-between text-sm border-b border-base-300 pb-2">
                            <span class="font-semibold text-slate-600">TEMP</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.temp"></span>
                        </div>
                    </template>
                    <template x-if="viewLogData.data.hr || viewLogData.data.heart_rate">
                        <div class="flex justify-between text-sm border-b border-base-300 pb-2">
                            <span class="font-semibold text-slate-600">HEART RATE</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.hr || viewLogData.data.heart_rate"></span>
                        </div>
                    </template>
                    <template x-if="viewLogData.data.o2 || viewLogData.data.o2_sat">
                        <div class="flex justify-between text-sm border-b border-base-300 pb-2">
                            <span class="font-semibold text-slate-600">O2 SAT (%)</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.o2 || viewLogData.data.o2_sat"></span>
                        </div>
                    </template>
                    <template x-if="viewLogData.data.rr || viewLogData.data.respiratory_rate">
                        <div class="flex justify-between text-sm border-b border-base-300 pb-2">
                            <span class="font-semibold text-slate-600">RESP RATE</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.rr || viewLogData.data.respiratory_rate"></span>
                        </div>
                    </template>

                    <!-- Observation -->
                    <template x-if="viewLogData.data.observation">
                        <div class="flex justify-between text-sm border-b border-base-300 pb-2">
                            <span class="font-semibold text-slate-600">OBSERVATION</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.observation || '—'"></span>
                        </div>
                    </template>
                </div>
            </template>

            <!-- LABORATORY LOG LAYOUT -->
            <template x-if="viewLogData.type === 'Laboratory'">
                <div class="space-y-4">
                    <!-- Findings -->
                    <div>
                        <div class="text-xs font-bold text-gray-500 mb-2">Details</div>
                        <div class="bg-white p-3 rounded border border-base-300">
                            <p class="text-sm text-slate-800" x-text="viewLogData.data.note"></p>
                        </div>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-gray-500 mb-2">FINDINGS</div>
                        <div class="bg-white p-3 rounded border border-base-300">
                            <p class="text-sm text-slate-800" x-text="viewLogData.data.finding"></p>
                        </div>
                    </div>

                    <!-- File Info -->
                    <template x-if="viewLogData.labResultFile">
                        <div>
                            <div class="text-xs font-bold text-gray-500 mb-2">UPLOADED FILE</div>
                            <div class="bg-white p-3 rounded border border-base-300 flex items-center justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="font-mono text-sm text-slate-800 truncate" x-text="viewLogData.labResultFile.file_name || 'Lab Result'"></div>
                                </div>
                                <a :href="'/document/view/' + viewLogData.labResultFile.id" target="_blank" class="btn btn-sm btn-primary text-white shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    View
                                </a>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>

        <div class="modal-action mt-6">
            <form method="dialog">
                <button type="submit" class="btn">Close</button>
            </form>
        </div>
    </div>
</dialog>
