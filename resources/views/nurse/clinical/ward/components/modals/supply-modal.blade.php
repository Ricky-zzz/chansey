<!-- SUPPLY CHARGE MODAL -->
<dialog id="supply_modal" class="modal">
    <div class="modal-box modal-enterprise">
        <h3 class="font-semibold text-lg text-slate-800 mb-2">Charge Supply / Utility</h3>
        <p class="text-sm text-slate-500 mb-4">Use this to record items consumed by the patient (Linens, Kits, etc).</p>

        <form action="{{ route('nurse.clinical.supplies.store') }}" method="POST" id="supply_form">
            @csrf
            <input type="hidden" name="admission_id" value="{{ $admission->id }}">

            <div class="form-control w-full mb-4">
                <label class="label font-semibold text-xs uppercase text-slate-500">Select Item</label>
                @if($supplies && $supplies->count() > 0)
                <select name="inventory_item_id" class="select-enterprise w-full" required>
                    <option value="" disabled selected>-- Select Item --</option>
                    @foreach($supplies as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->item_name }} | ₱{{ $item->price }}
                        </option>
                    @endforeach
                </select>
                @else
                <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-lg p-3 text-sm">
                    No inventory items available. Please add items to inventory first.
                </div>
                @endif
            </div>

            <div class="form-control w-full mb-4">
                <label class="label font-semibold text-xs uppercase text-slate-500">Quantity</label>
                <input type="number" name="quantity" class="input-enterprise" value="1" min="1" required>
            </div>

            <div class="form-control w-full mb-6">
                <label class="label font-semibold text-xs uppercase text-slate-500">Remarks</label>
                <textarea name="remarks" class="textarea-enterprise h-20" placeholder="e.g. Patient requested extra pillows, Linens soiled..."></textarea>
            </div>

            <div class="modal-action">
                <button type="button" class="btn-enterprise-secondary" onclick="supply_modal.close()">Cancel</button>
                <button type="submit" class="btn-enterprise-warning">Confirm & Charge</button>
            </div>
        </form>
    </div>
</dialog>
