<!-- LEFT COLUMN: ACTIVE ORDERS -->
<div class="lg:col-span-1 space-y-3 max-h-[600px] overflow-y-auto pr-2">
    <div class="flex justify-between items-center px-1">
        <h3 class="font-semibold text-slate-800 text-base">Active Orders</h3>
        <span class="badge-enterprise bg-slate-100 text-slate-600">{{ $activeOrders->count() }} Pending</span>
    </div>

    <!-- Loop Orders -->
    @forelse($activeOrders as $order)
    <div class="bg-white border border-slate-200 border-l-4 rounded-lg shadow-sm hover:shadow transition-shadow
                    {{ $order->type === 'Medication' ? 'border-l-emerald-500' :
                      ($order->type === 'Monitoring' ? 'border-l-sky-500' :
                      ($order->type === 'Laboratory' ? 'border-l-amber-500' :
                      ($order->type === 'Transfer' ? 'border-l-rose-500' : 'border-l-lime-500'))) }}">
        <div class="p-4">

            <!-- Top Row: Type & Freq -->
            <div class="flex justify-between items-start mb-2">
                <span class="badge-enterprise text-xs
                        {{ $order->type === 'Medication' ? 'bg-emerald-100 text-emerald-700' :
                          ($order->type === 'Monitoring' ? 'bg-sky-100 text-sky-700' :
                          ($order->type === 'Laboratory' ? 'bg-amber-100 text-amber-700' :
                          ($order->type === 'Transfer' ? 'bg-rose-100 text-rose-700' : 'bg-lime-100 text-lime-700'))) }}">
                    {{ $order->type }}
                </span>
                @if($order->type === 'Medication')
                <span class="px-2 py-1 rounded-full text-xs font-bold {{ $order->dispensed ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $order->dispensed ? '✓ Dispensed' : ' Awaiting Pharmacy' }}
                </span>
                @endif
                <span class="text-xs font-bold text-slate-500">{{ $order->frequency }}</span>
            </div>

            <!-- Instruction -->
            <div class="font-bold text-slate-800 text-sm mb-1">
                @if($order->type === 'Medication')
                {{ $order->medicine?->getFormattedLabel() ?? 'Medication (No Details)' }}
                <span class="block text-xs font-normal text-gray-500 mt-1">
                    Dose: {{ $order->quantity }} | {{ $order->instruction }}
                </span>
                @else
                {{ $order->instruction }}
                @endif
            </div>

            <!-- Last Given Info -->
            @if($order->latestLog)
            <div class="text-[10px] text-gray-400 mb-3 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Last: {{ $order->latestLog->created_at->diffForHumans() }}
            </div>
            @else
            <div class="text-[10px] text-orange-500 mb-3 font-bold">New Order - Not yet done</div>
            @endif

            <!-- ACTIONS -->
            <div class="card-actions justify-end mt-2">

                @php
                $status = $order->timer_status;
                @endphp

                <!-- 1. MONITORING: Log Values -->
                @if($order->type === 'Monitoring')
                <button
                    @click="openLogModal({{ $order->id }}, 'Vitals')"
                    class="btn btn-sm btn-{{ $status['color'] }} {{ $status['disabled'] ? 'text-gray-700' : 'text-white' }} w-full {{ isset($status['animate']) ? 'animate-pulse' : '' }}"
                    {{ $status['disabled'] ? 'disabled' : '' }}>
                    {{ $status['label'] }}
                </button>

                <!-- 2. MEDICATION: Administer -->
                @elseif($order->type === 'Medication')
                @if($order->dispensed)
                <button
                    @click="openLogModal(@js($order->id), 'Medication', @js($order->medicine->brand_name ?? $order->medicine->generic_name ?? ''), @js($order->quantity ?? 1))"
                    class="btn btn-sm btn-{{ $status['color'] }} text-white w-full {{ isset($status['animate']) ? 'animate-pulse' : '' }}"
                    >
                    {{ $status['label'] }}
                </button>
                @else
                <button
                    class="btn btn-sm btn-warning text-white w-full"
                    disabled
                    title="Waiting for pharmacy to dispense">
                    Awaiting Pharmacy
                </button>
                @endif

                <!-- 3. UTILITY: Execute Directly -->
                @elseif($order->type === 'Utility')
                @if($order->status !== 'Done')
                <form action="{{ route('nurse.clinical.logs.store', $admission->id) }}" method="POST" class="w-full">
                    @csrf
                    <input type="hidden" name="medical_order_id" value="{{ $order->id }}">
                    <input type="hidden" name="type" value="Utility">
                    <button type="submit" class="btn btn-sm btn-primary w-full">Execute Now</button>
                </form>
                @else
                <button class="btn btn-sm btn-disabled w-full">Completed</button>
                @endif

                @elseif($order->type === 'Laboratory')
                <button @click="openLabModal(@js($order->id), @js($order->instruction))"
                    class="btn btn-sm bg-emerald-500 text-white w-full">
                    Upload Result
                </button>
                @elseif($order->type === 'Transfer')
                @if($order->status === 'Waiting')
                <button class="btn btn-sm btn-disabled text-gray-700 w-full" disabled>
                    Waiting for Approval
                </button>
                @else
                <button @click="openTransferModal(@js($order->id), @js($order->instruction))"
                    class="btn btn-sm bg-rose-400 text-white w-full">
                    Request Transfer
                </button>
                @endif
                @else
                <button class="btn btn-sm btn-neutral w-full" disabled>Info Only</button>
                @endif

            </div>
        </div>
    </div>
    @empty
    <div class="p-8 text-center text-slate-400 bg-slate-50 rounded-lg border border-dashed border-slate-200">
        <span class="text-sm">No active orders.</span>
    </div>
    @endforelse
</div>
