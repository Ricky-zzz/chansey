<!-- TRANSFER REQUEST MODAL -->
<dialog id="transfer_modal" class="modal">
    <div class="modal-box modal-enterprise">
        <h3 class="font-semibold text-lg text-slate-800 mb-4">Request Patient Transfer</h3>

        <form action="{{ route('nurse.clinical.orders.transfer') }}" method="POST">
            @csrf
            <input type="hidden" name="admission_id" value="{{ $admission->id }}">
            <input type="hidden" name="medical_order_id" x-model="transferOrderId">

            <div class="bg-sky-50 border border-sky-200 text-sky-800 rounded-lg p-3 text-xs mb-4">
                <span class="font-bold">Doctor's Order:</span> <span x-text="transferInstruction"></span>
            </div>

            <!-- 1. SELECT TARGET STATION -->
            <div class="form-control mb-4">
                <label class="label font-semibold text-xs uppercase text-slate-500">Target Station / Ward</label>
                <select name="target_station_id" x-model="selectedTargetStation" class="select-enterprise w-full" required>
                    <option value="" disabled selected>Select Destination Station</option>
                    <template x-for="station in transferStations" :key="station.id">
                        <option :value="station.id" x-text="station.station_name"></option>
                    </template>
                </select>
            </div>

            <!-- 2. SELECT TARGET BED -->
            <div class="form-control mb-4">
                <label class="label font-semibold text-xs uppercase text-slate-500">Specific Bed</label>
                <select name="target_bed_id" class="select-enterprise w-full" :disabled="!selectedTargetStation" required>
                    <option value="" disabled selected x-text="selectedTargetStation ? 'Select Available Bed' : 'Select Station First'"></option>
                    <template x-for="bed in filteredTransferBeds()" :key="bed.id">
                        <option :value="bed.id" x-text="bed.bed_code + ' (Room ' + bed.room_number + ')'"></option>
                    </template>
                    <option disabled x-show="selectedTargetStation && filteredTransferBeds().length === 0">
                        No available beds in this station.
                    </option>
                </select>
            </div>

            <div class="form-control mb-4">
                <label class="label font-semibold text-xs uppercase text-slate-500">Reason / Remarks</label>
                <textarea name="remarks" class="textarea-enterprise h-20" placeholder="e.g. Needs isolation, Upgrading to Private..."></textarea>
            </div>

            <div class="modal-action">
                <button type="button" class="btn-enterprise-secondary" onclick="transfer_modal.close()">Cancel</button>
                <button type="submit" class="btn-enterprise-warning">Submit Request</button>
            </div>
        </form>
    </div>
</dialog>
