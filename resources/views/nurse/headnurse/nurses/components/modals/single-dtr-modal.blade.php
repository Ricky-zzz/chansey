{{-- Single Nurse DTR Report Modal --}}
<dialog id="dtr_nurse_modal" class="modal" x-ref="dtrModal">
    <div class="modal-enterprise">
        <form method="dialog">
            <button class="absolute right-4 top-4 text-slate-400 hover:text-slate-600 transition-colors">✕</button>
        </form>

        <h3 class="text-lg font-bold text-slate-800 mb-1">DTR Report</h3>
        <p class="text-sm text-slate-500 mb-4">Generate DTR report for: <span class="font-bold text-emerald-600" x-text="selectedDtrNurse.name"></span></p>

        <form :action="`{{ url('nurse/headnurse/nurses') }}/${selectedDtrNurse.id}/dtr-report`" method="POST" target="_blank" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Date From</label>
                    <input type="date" name="date_from" required class="input-enterprise w-full" />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Date To</label>
                    <input type="date" name="date_to" required class="input-enterprise w-full" />
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-200">
                <button type="button" @click="$refs.dtrModal.close()" class="btn-enterprise-secondary">Cancel</button>
                <button type="submit" class="btn-enterprise-primary inline-flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Generate PDF
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>
