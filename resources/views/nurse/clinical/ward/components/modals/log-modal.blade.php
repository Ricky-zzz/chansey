<!-- LOG MODAL -->
<dialog id="log_modal" class="modal">
    <div class="modal-box modal-enterprise w-11/12 max-w-2xl">
        <h3 class="font-semibold text-lg text-slate-800 mb-4 flex items-center gap-2">
            <span x-text="logType === 'Medication' ? ' Give Medication' : (orderId ? ' Clinical Note / Monitoring' : ' Spontaneous Clinical Log')"></span>
        </h3>

        <form action="{{ route('nurse.clinical.logs.store', $admission->id) }}" method="POST">
            @csrf
            <input type="hidden" name="medical_order_id" :value="orderId || ''">
            <input type="hidden" name="type" :value="logType || 'Note'">

            <!-- MEDICATION ALERT (Only if Meds and medName is set) -->
            <template x-if="logType === 'Medication' && medName !== ''">
                <div class="alert alert-warning mb-6 text-sm shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <strong>Confirm Action:</strong> Giving <strong x-text="medName"></strong> (Qty: <strong x-text="medQty"></strong>).
                        <br><span class="text-xs">This will deduct inventory and charge the patient.</span>
                    </div>
                </div>
            </template>

            <!-- VITALS INPUTS (Required if Type=Vitals, Optional if Type=Medication) -->
            <div x-show="logType === 'Vitals' || logType === 'Medication'" class="bg-slate-50 border border-slate-200 p-4 rounded-lg mb-4">
                <div class="label font-semibold text-xs uppercase text-slate-500 mb-2">
                    Vital Signs <span x-show="logType === 'Medication'">(Optional)</span>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="form-control">
                        <label class="label text-xs">BP (e.g. 120/80)</label>
                        <input type="text" name="bp" class="input-enterprise input-sm">
                    </div>
                    <div class="form-control">
                        <label class="label text-xs">Temp (°C)</label>
                        <input type="number" step="0.1" name="temp" class="input-enterprise input-sm">
                    </div>
                    <div class="form-control">
                        <label class="label text-xs">Heart Rate (bpm)</label>
                        <input type="number" name="hr" class="input-enterprise input-sm">
                    </div>
                    <div class="form-control">
                        <label class="label text-xs">Pulse Rate (bpm)</label>
                        <input type="number" name="pr" class="input-enterprise input-sm">
                    </div>
                    <div class="form-control">
                        <label class="label text-xs">Respiratory Rate</label>
                        <input type="number" name="rr" class="input-enterprise input-sm">
                    </div>
                    <div class="form-control">
                        <label class="label text-xs">O2 Sat (%)</label>
                        <input type="number" name="o2" class="input-enterprise input-sm">
                    </div>
                </div>
            </div>

            <!-- NOTES / REMARKS (Always Visible) -->
            <div class="form-control">
                <label class="label font-semibold text-xs uppercase text-slate-500">
                    <span x-text="logType === 'Medication' ? 'Remarks / Patient Reaction' : 'Observation / Note'"></span>
                </label>
                <textarea name="observation" class="textarea-enterprise w-full h-24" placeholder="Enter details..."></textarea>
            </div>

            <div class="modal-action">
                <button type="button" class="btn-enterprise-secondary" onclick="log_modal.close()">Cancel</button>
                <button type="submit" class="btn-enterprise-primary" x-text="logType === 'Medication' ? 'Confirm & Charge' : 'Save Log'">Save Log</button>
            </div>
        </form>
    </div>
</dialog>
