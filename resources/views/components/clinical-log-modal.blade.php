<!-- VIEW LOG DETAILS MODAL -->
<dialog id="view_log_modal" class="modal">
    <div class="modal-enterprise w-11/12 max-w-2xl">
        <h3 class="font-semibold text-lg text-slate-800 mb-4">Log Details</h3>

        <div class="grid grid-cols-2 gap-4 text-sm mb-4">
            <div>
                <span class="text-xs text-slate-400 uppercase font-semibold block mb-1">Type</span>
                <div class="badge-enterprise text-sm font-semibold inline-block"
                    :class="{
                        'bg-emerald-100 text-emerald-700': viewLogData.type === 'Medication',
                        'bg-sky-100 text-sky-700': viewLogData.type === 'Vitals',
                        'bg-amber-100 text-amber-700': viewLogData.type === 'Laboratory',
                        'bg-rose-100 text-rose-700': viewLogData.type === 'Transfer',
                        'bg-purple-100 text-purple-700': viewLogData.type === 'Utility',
                        'bg-lime-100 text-lime-700': viewLogData.type === 'Discharge',
                        'bg-indigo-100 text-indigo-700': viewLogData.type === 'Note',
                        'bg-slate-100 text-slate-600': !['Medication', 'Vitals', 'Laboratory', 'Transfer', 'Utility', 'Discharge', 'Note'].includes(viewLogData.type)
                    }"
                    x-text="viewLogData.type">
                </div>
            </div>
            <div>
                <span class="text-xs text-slate-400 uppercase font-semibold block mb-1">Recorded By</span>
                <div class="font-semibold text-slate-800" x-text="viewLogData.user?.name ?? 'Unknown'"></div>
            </div>
            <div class="col-span-2">
                <span class="text-xs text-slate-400 uppercase font-semibold block mb-1">Date & Time</span>
                <div class="font-mono text-slate-700" x-text="new Date(viewLogData.created_at).toLocaleString()"></div>
            </div>
        </div>

        <div class="border-t border-slate-200 my-4"></div>

        <h4 class="text-xs text-slate-400 uppercase font-semibold mb-3">Recorded Data</h4>
        <div class="bg-slate-50 border border-slate-200 p-4 rounded-lg space-y-2">
            <!-- MEDICATION LOG LAYOUT -->
            <template x-if="viewLogData.type === 'Medication'">
                <div class="space-y-3">
                    <!-- Medicine Info -->
                    <template x-if="viewLogData.data.medicine">
                        <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">MEDICINE</span>
                            <span class="font-mono text-slate-800 font-bold" x-text="viewLogData.data.medicine"></span>
                        </div>
                    </template>
                    <template x-if="viewLogData.data.dosage">
                        <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">DOSAGE</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.dosage"></span>
                        </div>
                    </template>

                    <!-- Vitals Section -->
                    <div class="text-xs font-semibold text-slate-400 mt-3 mb-2">Vital Signs</div>
                    <template x-if="viewLogData.data.bp">
                        <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">BLOOD PRESSURE</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.bp"></span>
                        </div>
                    </template>
                    <template x-if="viewLogData.data.temp">
                        <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">TEMP</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.temp"></span>
                        </div>
                    </template>
                    <template x-if="viewLogData.data.hr">
                        <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">HEART RATE</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.hr"></span>
                        </div>
                    </template>
                    <template x-if="viewLogData.data.pr">
                        <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">PULSE RATE</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.pr"></span>
                        </div>
                    </template>
                    <template x-if="viewLogData.data.o2">
                        <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">O2 SAT (%)</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.o2"></span>
                        </div>
                    </template>

                    <!-- Remarks -->
                    <template x-if="viewLogData.data.observation || viewLogData.data.remarks">
                        <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">REMARKS</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.observation || viewLogData.data.remarks || '—'"></span>
                        </div>
                    </template>
                </div>
            </template>

            <!-- VITALS LOG LAYOUT -->
            <template x-if="viewLogData.type === 'Vitals'">
                <div class="space-y-3">
                    <!-- Vitals Section First -->
                    <div class="text-xs font-semibold text-slate-400 mb-2">Vital Signs</div>
                    <template x-if="viewLogData.data.bp">
                        <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">BP</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.bp"></span>
                        </div>
                    </template>
                    <template x-if="viewLogData.data.temp">
                        <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">TEMP</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.temp"></span>
                        </div>
                    </template>
                    <template x-if="viewLogData.data.hr">
                        <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">HEART RATE</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.hr"></span>
                        </div>
                    </template>
                    <template x-if="viewLogData.data.pr">
                        <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">PULSE RATE</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.pr"></span>
                        </div>
                    </template>
                    <template x-if="viewLogData.data.rr">
                        <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">RESP RATE</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.rr"></span>
                        </div>
                    </template>
                    <template x-if="viewLogData.data.o2">
                        <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">O2 SAT (%)</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.o2"></span>
                        </div>
                    </template>

                    <!-- Observation -->
                    <template x-if="viewLogData.data.observation">
                        <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">OBSERVATION</span>
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
                        <div class="text-xs font-semibold text-slate-400 mb-2">Details</div>
                        <div class="bg-white p-3 rounded-lg border border-slate-200">
                            <p class="text-sm text-slate-800" x-text="viewLogData.data.note"></p>
                        </div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-slate-400 mb-2">FINDINGS</div>
                        <div class="bg-white p-3 rounded-lg border border-slate-200">
                            <p class="text-sm text-slate-800" x-text="viewLogData.data.finding"></p>
                        </div>
                    </div>

                    <!-- File Info -->
                    <template x-if="viewLogData.labResultFile">
                        <div>
                            <div class="text-xs font-semibold text-slate-400 mb-2">UPLOADED FILE</div>
                            <div class="bg-white p-3 rounded-lg border border-slate-200 flex items-center justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="font-mono text-sm text-slate-800 truncate" x-text="viewLogData.labResultFile.file_name || 'Lab Result'"></div>
                                </div>
                                <a :href="'/document/view/' + viewLogData.labResultFile.id" target="_blank" class="btn-enterprise-primary text-sm px-3 py-1.5 inline-flex items-center gap-1.5 shrink-0">
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

            <!-- TRANSFER LOG LAYOUT -->
            <template x-if="viewLogData.type === 'Transfer'">
                <div class="space-y-3">
                    <!-- From Bed -->
                    <template x-if="viewLogData.data.from_bed">
                        <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">FROM BED</span>
                            <span class="font-mono text-slate-800 font-bold" x-text="viewLogData.data.from_bed"></span>
                        </div>
                    </template>

                    <!-- To Bed -->
                    <template x-if="viewLogData.data.to_bed">
                        <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">TO BED</span>
                            <span class="font-mono text-slate-800 font-bold" x-text="viewLogData.data.to_bed"></span>
                        </div>
                    </template>

                    <!-- Remarks -->
                    <template x-if="viewLogData.data.remarks">
                        <div class="mt-3 pt-3 border-t border-slate-200">
                            <div class="text-xs font-semibold text-slate-400 mb-2">REMARKS</div>
                            <div class="bg-white p-3 rounded-lg border border-slate-200">
                                <p class="text-sm text-slate-800" x-text="viewLogData.data.remarks"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            <!-- DISCHARGE LOG LAYOUT -->
            <template x-if="viewLogData.type === 'Discharge'">
                <div class="space-y-3">
                    <!-- Note/Reason -->
                    <template x-if="viewLogData.data.note">
                        <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">STATUS</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.note"></span>
                        </div>
                    </template>
                </div>
            </template>

            <!-- UTILITY LOG LAYOUT -->
            <template x-if="viewLogData.type === 'Utility'">
                <div class="space-y-3">
                    <!-- Item Used -->
                    <template x-if="viewLogData.data.note">
                        <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">ITEM USED</span>
                            <span class="font-mono text-slate-800 font-bold" x-text="viewLogData.data.note"></span>
                        </div>
                    </template>

                    <!-- Quantity -->
                    <template x-if="viewLogData.data.qty">
                        <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">QUANTITY</span>
                            <span class="font-mono text-slate-800" x-text="viewLogData.data.qty + ' unit(s)'"></span>
                        </div>
                    </template>

                    <!-- Price -->
                    <template x-if="viewLogData.data.price">
                        <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                            <span class="font-semibold text-slate-500">UNIT PRICE</span>
                            <span class="font-mono text-slate-800" x-text="'₱' + parseFloat(viewLogData.data.price).toFixed(2)"></span>
                        </div>
                    </template>

                    <!-- Total (calculated) -->
                    <template x-if="viewLogData.data.price && viewLogData.data.qty">
                        <div class="flex justify-between text-sm border-b border-slate-200 pb-2 font-bold">
                            <span class="font-semibold text-slate-500">TOTAL</span>
                            <span class="font-mono text-slate-800 text-lg" x-text="'₱' + (parseFloat(viewLogData.data.price) * parseInt(viewLogData.data.qty)).toFixed(2)"></span>
                        </div>
                    </template>

                    <!-- Remarks -->
                    <template x-if="viewLogData.data.remarks">
                        <div class="mt-3 pt-3 border-t border-slate-200">
                            <div class="text-xs font-semibold text-slate-400 mb-2">REMARKS</div>
                            <div class="bg-white p-3 rounded-lg border border-slate-200">
                                <p class="text-sm text-slate-800" x-text="viewLogData.data.remarks"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            <!-- NOTE LOG LAYOUT -->
            <template x-if="viewLogData.type === 'Note'">
                <div class="space-y-3">
                    <!-- Note Content -->
                    <template x-if="viewLogData.data.note || viewLogData.data.observation">
                        <div class="bg-white p-3 rounded-lg border border-slate-200">
                            <p class="text-sm text-slate-800 whitespace-pre-wrap" x-text="viewLogData.data.note || viewLogData.data.observation || 'No note content'"></p>
                        </div>
                    </template>

                    <!-- Vital Signs (if provided with note) -->
                    <template x-if="viewLogData.data.bp || viewLogData.data.temp || viewLogData.data.hr || viewLogData.data.pr || viewLogData.data.o2">
                        <div class="text-xs font-semibold text-slate-400 mt-3 mb-2">Associated Vital Signs</div>
                        <template x-if="viewLogData.data.bp">
                            <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                                <span class="font-semibold text-slate-500">BLOOD PRESSURE</span>
                                <span class="font-mono text-slate-800" x-text="viewLogData.data.bp"></span>
                            </div>
                        </template>
                        <template x-if="viewLogData.data.temp">
                            <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                                <span class="font-semibold text-slate-500">TEMP</span>
                                <span class="font-mono text-slate-800" x-text="viewLogData.data.temp"></span>
                            </div>
                        </template>
                        <template x-if="viewLogData.data.hr">
                            <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                                <span class="font-semibold text-slate-500">HEART RATE</span>
                                <span class="font-mono text-slate-800" x-text="viewLogData.data.hr"></span>
                            </div>
                        </template>
                        <template x-if="viewLogData.data.pr">
                            <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                                <span class="font-semibold text-slate-500">PULSE RATE</span>
                                <span class="font-mono text-slate-800" x-text="viewLogData.data.pr"></span>
                            </div>
                        </template>
                        <template x-if="viewLogData.data.o2">
                            <div class="flex justify-between text-sm border-b border-slate-200 pb-2">
                                <span class="font-semibold text-slate-500">O2 SAT (%)</span>
                                <span class="font-mono text-slate-800" x-text="viewLogData.data.o2"></span>
                            </div>
                        </template>
                    </template>
                </div>
            </template>
        </div>

        <div class="flex justify-end mt-6 pt-4 border-t border-slate-200">
            <form method="dialog">
                <button type="submit" class="btn-enterprise-secondary">Close</button>
            </form>
        </div>
    </div>
</dialog>
