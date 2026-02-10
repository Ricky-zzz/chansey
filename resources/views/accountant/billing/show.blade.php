@extends('layouts.accountant')

@section('content')
<div class="max-w-7xl mx-auto"
    x-data="billingSystem({
        roomTotal: {{ $roomTotal }},
        itemsTotal: {{ $itemsTotal }}
     })">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Process Billing</h2>
            <div class="text-sm text-gray-500">
                Patient: <span class="font-bold text-emerald-700">{{ $admission->patient->last_name }}, {{ $admission->patient->first_name }}</span>
                | ID: {{ $admission->admission_number }}
            </div>
        </div>
            <div class="text-right">
            <div class="text-xs uppercase font-bold text-gray-400">Current Status</div>
            <div class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-semibold {{ $admission->status === 'Ready for Discharge' ? 'bg-red-500 text-white border-2 border-red-200' : 'bg-gray-50 text-gray-600 border border-gray-200' }}">
                {{ $admission->status }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- LEFT COL: BILL BREAKDOWN (2/3) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- A. ROOM CHARGES -->
            <div class="card-enterprise overflow-hidden">
                <div class="p-0">
                    <div class="p-4 border-b border-slate-200 bg-slate-50 rounded-t-lg font-bold text-slate-800">
                        Room & Board Charges
                    </div>
                    <div class="overflow-x-auto max-h-64">
                        <table class="table-enterprise">
                            <thead>
                                <tr>
                                    <th>Room</th>
                                    <th>Period</th>
                                    <th>Days</th>
                                    <th class="text-right">Price</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($movements as $mov)
                                <tr>
                                    <td>{{ $mov->room->room_number }} ({{ $mov->bed->bed_code }})</td>
                                    <td class="text-xs">
                                        {{ $mov->started_at->format('M d') }} -
                                        {{ $mov->ended_at ? $mov->ended_at->format('M d') : 'Now' }}
                                    </td>
                                    <td>
                                        @php
                                        $end = $mov->ended_at ?? now();
                                        $days = $mov->started_at->startOfDay()->diffInDays($end->endOfDay()) + 1;
                                        $days = (int) $days;
                                        @endphp
                                        {{ $days }}
                                    </td>
                                    <td class="text-right font-mono">{{ number_format($mov->room_price, 2) }}</td>
                                    <td class="text-right font-mono font-bold">{{ number_format($days * $mov->room_price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <table class="table-enterprise">
                        <tfoot class="bg-slate-50">
                            <tr>
                                <td colspan="4" class="text-right font-bold text-slate-500">Room Subtotal:</td>
                                <td class="text-right font-bold text-slate-800 text-lg">₱{{ number_format($roomTotal, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- B. ITEMS & SERVICES (Meds, Labs, Fees) -->
            <div class="card-enterprise overflow-hidden">
                <div class="p-0">
                    <div class="p-4 border-b border-slate-200 bg-slate-50 rounded-t-lg flex justify-between items-center">
                        <span class="font-bold text-slate-800">Medications, Labs & Other Fees</span>

                        <!-- ADD FEE BUTTON -->
                        <button onclick="fee_modal.showModal()" class="btn-enterprise-primary text-xs py-1.5 px-3">+ Add Fee</button>
                    </div>
                    <div class="overflow-x-auto max-h-64">
                        <table class="table-enterprise">
                            <thead>
                                <tr>
                                    <th>Item / Service</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-right">Unit Price</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($billingTableData as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>

                                    <td class="text-center">
                                        {{ $item->quantity }}
                                    </td>

                                    <td class="text-right font-mono">
                                        {{ number_format($item->amount, 2) }}
                                    </td>

                                    <td class="text-right font-mono font-bold">
                                        {{ number_format($item->total, 2) }}
                                    </td>

                                    <td class="text-right">
                                        @if($item->type === 'fee' && $item->id)
                                        <form action="{{ route('accountant.billing.remove_item', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-enterprise-danger text-xs py-1 px-2">Remove</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <table class="table-enterprise">
                        <tfoot class="bg-slate-50">
                            <tr>
                                <td colspan="4" class="text-right font-bold text-slate-500">Items Subtotal:</td>
                                <td class="text-right font-bold text-slate-800 text-lg">₱{{ number_format($itemsTotal, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>

        <!-- RIGHT COL: CALCULATOR & PAYMENT (1/3) -->
        <div class="lg:col-span-1">
            <form action="{{ route('accountant.billing.store') }}" method="POST">
                @csrf
                <input type="hidden" name="admission_id" value="{{ $admission->id }}">
                <input type="hidden" name="room_total" value="{{ $roomTotal }}">
                <input type="hidden" name="items_total" value="{{ $itemsTotal }}">

                <div class="card-enterprise border-t-4 border-emerald-500">
                    <div class="p-6 space-y-4">
                        <h3 class="text-lg font-bold text-slate-800">Billing Adjustments</h3>

                        <!-- 1. PROFESSIONAL FEE -->
                        <div class="form-control">
                            <label class="label text-xs text-slate-700 font-bold uppercase">Professional Fee (Doctor)</label>
                            <div class="input-group">
                                <span>₱</span>
                                <input type="number" step="0.01" name="pf_fee" x-model.number="pf" class="input-enterprise text-2xl w-full font-mono text-right">
                            </div>
                        </div>

                        <!-- 2. DEDUCTIONS -->
                        <div class="divider text-xs text-gray-400">Less: Deductions</div>

                        <div class="form-control">
                            <label class="label text-xs font-bold uppercase text-emerald-600">PhilHealth</label>
                            <div class="input-group">
                                <span>- ₱</span>
                                <input type="number" step="0.01" name="philhealth" x-model.number="philhealth" class="input-enterprise text-2xl w-full font-mono text-right text-emerald-600">
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label text-xs font-bold uppercase text-emerald-600">HMO / Insurance</label>
                            <div class="input-group">
                                <span>- ₱</span>
                                <input type="number" step="0.01" name="hmo" x-model.number="hmo" class="input-enterprise w-full text-2xl font-mono text-right text-emerald-600">
                            </div>
                        </div>

                        <!-- 3. TOTALS -->
                        <div class="bg-slate-100 p-4 rounded-lg mt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span>Subtotal</span>
                                <span class="font-mono">₱<span x-text="subtotal.toFixed(2)"></span></span>
                            </div>
                            <div class="flex justify-between text-sm text-emerald-600">
                                <span>Total Deductions</span>
                                <span class="font-mono">- ₱<span x-text="totalDeductions.toFixed(2)"></span></span>
                            </div>
                            <div class="divider my-1"></div>
                            <div class="flex justify-between items-end">
                                <span class="font-bold text-lg">NET AMOUNT DUE</span>
                                <span class="font-bold text-3xl text-red-500">₱<span x-text="netAmount.toFixed(2)"></span></span>
                            </div>
                        </div>

                        <!-- 4. PAYMENT -->
                        <div class="form-control mt-4">
                            <label class="label text-xs text-slate-700 font-bold uppercase">Cash Tendered</label>
                            <div class="input-group">
                                <span>₱</span>
                                <input type="number" step="0.01" name="cash_tendered" x-model.number="cash" class="input-enterprise text-3xl text-slate-800 bg-amber-100 w-full font-mono text-right">
                            </div>
                        </div>

                        <div class="flex justify-between text-sm px-2">
                            <span class="font-bold text-gray-800">Change:</span>
                            <span class="font-mono font-bold text-lg" :class="change < 0 ? 'text-error' : 'text-slate-700'">
                                ₱<span x-text="change.toFixed(2)"></span>
                            </span>
                        </div>

                        <!-- SUBMIT -->
                        <button type="submit" class="btn font-bold w-full btn-lg mt-4"
                            :class="{
                                'bg-gray-300 text-gray-600 opacity-70 cursor-not-allowed': change < 0 || netAmount <= 0,
                                'bg-red-500 text-white': !(change < 0 || netAmount <= 0)
                            }"
                            :disabled="change < 0 || netAmount <= 0">
                            Confirm Payment & Discharge
                        </button>

                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- ADD FEE MODAL -->
<dialog id="fee_modal" class="modal">
    <div class="modal-enterprise">
        <h3 class="font-bold text-lg mb-4">Add Hospital Fee</h3>
        <form action="{{ route('accountant.billing.add_fee') }}" method="POST">
            @csrf
            <input type="hidden" name="admission_id" value="{{ $admission->id }}">

            <div class="form-control w-full mb-4">
                <label class="label font-semibold text-slate-700">Select Fee</label>
                <select name="fee_id" class="select-enterprise w-full">
                    @foreach($fees as $fee)
                    <option value="{{ $fee->id }}">
                        {{ $fee->name }} (₱{{ number_format($fee->price, 2) }} / {{ $fee->unit }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-control w-full mb-6">
                <label class="label font-semibold text-slate-700">Quantity / Days</label>
                <input type="number" name="quantity" class="input-enterprise w-full" value="1" min="1">
            </div>

            <div class="modal-action">
                <button type="button" class="btn-enterprise-secondary" onclick="fee_modal.close()">Cancel</button>
                <button type="submit" class="btn-enterprise-primary">Add to Bill</button>
            </div>
        </form>
    </div>
</dialog>

<!-- ALPINE LOGIC -->
<script>
    function billingSystem(initialData) {
        return {
            roomTotal: initialData.roomTotal,
            itemsTotal: initialData.itemsTotal,

            pf: 0,
            philhealth: 0,
            hmo: 0,
            cash: 0,

            get subtotal() {
                return this.roomTotal + this.itemsTotal + (this.pf || 0);
            },

            get totalDeductions() {
                return (this.philhealth || 0) + (this.hmo || 0);
            },

            get netAmount() {
                let total = this.subtotal - this.totalDeductions;
                return total > 0 ? total : 0;
            },

            get change() {
                return (this.cash || 0) - this.netAmount;
            }
        }
    }
</script>
@endsection
