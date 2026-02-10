@extends('layouts.physician')

@section('content')
<div class="max-w-full mx-auto" x-data="clinicalChart()">
    <!-- BREADCRUMB -->
    <div class="flex items-center gap-2 text-sm mb-3">
        <a href="{{ route('physician.dashboard') }}" class="text-slate-500 hover:text-emerald-600 transition-colors">Dashboard</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <a href="{{ route('physician.mypatients.index') }}" class="text-slate-500 hover:text-emerald-600 transition-colors">My Patients</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <span class="text-slate-700 font-semibold">
            {{ $admission->patient->last_name }}, {{ $admission->patient->first_name }}
        </span>
    </div>

    <!-- 1. HEADER: PATIENT CONTEXT -->
    <div class="flex flex-col md:flex-row justify-between items-start mb-6 gap-4 card-enterprise p-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                <span class="text-2xl font-bold">{{ substr($admission->patient->first_name, 0, 1) }}</span>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    {{ $admission->patient->last_name }}, {{ $admission->patient->first_name }}
                </h1>
                <div class="flex gap-2 items-center mt-1">
                    <span class="badge-enterprise bg-emerald-50 text-emerald-700 border border-emerald-200 font-mono">
                        {{ $admission->bed ? $admission->bed->bed_code : 'Outpatient' }}
                    </span>
                    <span class="text-sm text-slate-500">{{ $admission->patient->age }}yo / {{ $admission->patient->sex }}</span>
                    <span class="text-sm text-slate-500">• Admitted: {{ $admission->admission_date->format('M d') }}</span>
                </div>
                <!-- Allergies Alert -->
                @if(!empty($admission->known_allergies))
                <div class="mt-2 flex gap-1 items-center">
                    <span class="text-xs font-bold text-slate-500">Allergies:</span>
                    @foreach($admission->known_allergies as $allergy)
                    <span class="badge-enterprise bg-red-50 text-red-700 border border-red-200 text-xs">{{ $allergy }}</span>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- PRIMARY ACTIONS -->
        <div class="flex flex-col gap-2 items-end">
            <h2 class="text-xs font-bold text-gray-400 uppercase">Actions</h2>
            <div class="flex flex-row gap-2 items-center">
                <!-- Order Modal Trigger -->
                <button onclick="order_modal.showModal()" class="btn-enterprise-info text-sm gap-2 inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Write Order
                </button>

                <!-- Treatment Plan Button -->
                @if($admission->treatmentPlan)
                <a href="{{ route('physician.treatment-plan.edit', $admission->id) }}" class="btn-enterprise-primary text-sm inline-flex items-center">Manage Plan</a>
                @else
                <a href="{{ route('physician.treatment-plan.create', $admission->id) }}" class="btn-enterprise-primary text-sm inline-flex items-center">Create Plan</a>
                @endif

                <!-- Print Report Button -->
                <a href="{{ route('patient.print-report', $admission->id) }}" target="_blank" class="btn-enterprise-secondary text-sm inline-flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Report
                </a>
            </div>
        </div>
    </div>

    <!-- 2. TOP GRID: LATEST STATUS & PLAN SUMMARY -->
    <div class="grid grid-cols-1  lg:grid-cols-3 gap-6 h-[calc(100vh-300px)] overflow-hidden" x-data="{ orderView: 'active' }">

        <!-- LEFT COLUMN: ORDERS (ACTIVE OR HISTORY) (SCROLLABLE) -->
        <div class="lg:col-span-1">
            <div class="flex justify-between items-center mb-4 px-1">
                <h3 class="font-bold text-slate-700 text-lg" x-text="orderView === 'active' ? 'Active Orders' : 'Order History'"></h3>
                <div class="flex border border-slate-200 rounded-lg overflow-hidden">
                    <button @click="orderView = 'active'" :class="orderView === 'active' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50'" class="px-3 py-1 text-xs font-medium transition-colors">Active</button>
                    <button @click="orderView = 'history'" :class="orderView === 'history' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50'" class="px-3 py-1 text-xs font-medium transition-colors">History</button>
                </div>
                <span class="badge-enterprise bg-slate-100 text-slate-600 text-xs" x-text="orderView === 'active' ? {{ $admission->medicalOrders->count() }} : {{ $orderHistory->count() }}"></span>
            </div>

            <div class="space-y-4 overflow-y-auto flex-1 m-1">
                <!-- ACTIVE ORDERS -->
                <template x-if="orderView === 'active'">
                    <div>
                        @forelse($admission->medicalOrders as $order)
                        <div class="card-enterprise mb-1.5 border-l-4
                            {{ $order->type === 'Medication' ? 'border-l-emerald-500' :
                              ($order->type === 'Monitoring' ? 'border-l-blue-500' : 'border-l-slate-300') }}">
                            <div class="p-3">
                                <div class="flex justify-between items-start mb-1">
                                    <div class="flex gap-2 items-center">
                                        <span class="px-3 py-1 rounded-full text-sm font-bold
                                        {{ $order->type === 'Medication' ? 'bg-emerald-600 text-white' :
                                  ($order->type === 'Monitoring' ? 'bg-sky-600 text-white' :
                                  ($order->type === 'Laboratory' ? 'bg-amber-600 text-white' :
                                  ($order->type === 'Transfer' ? 'bg-rose-600 text-white' : 'bg-lime-600 text-white'))) }}">
                                            {{ $order->type }}
                                        </span>
                                        <span class="badge-enterprise text-xs {{ $order->status === 'Pending' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $order->status }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $order->frequency ?? 'Once' }}</span>
                                </div>
                                @if($order->medicine)
                                <div class="text-sm font-bold text-slate-800 mb-2">
                                    {{ $order->medicine->getFormattedLabel() }}
                                    <span class="text-xs font-normal text-gray-500 block">
                                        Quantity: {{ $order->quantity }}
                                    </span>
                                </div>
                                @endif
                                <div class="text-xs text-gray-600 mb-2">
                                    Instruction: {{ $order->instruction }}
                                </div>
                                <div class="flex flex-col gap-3">
                                    @if($order->status === 'Pending' && $order->clinicalLogs->count() === 0)
                                    <form action="{{ route('physician.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Delete?');" class="w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-enterprise-danger text-xs w-full">Delete</button>
                                    </form>
                                    @elseif($order->status === 'Pending' || $order->status === 'Active')
                                    <form action="{{ route('physician.orders.discontinue', $order->id) }}" method="POST" class="w-full">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn-enterprise-danger text-xs w-full">Stop</button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-gray-400 text-sm py-8">No active orders.</div>
                        @endforelse
                    </div>
                </template>

                <!-- ORDER HISTORY -->
                <template x-if="orderView === 'history'">
                    <div>
                        @forelse($orderHistory as $order)
                        <div class="card-enterprise border-l-4
                            {{ $order->type === 'Medication' ? 'border-l-emerald-500' :
                              ($order->type === 'Monitoring' ? 'border-l-blue-500' : 'border-l-slate-300') }}">
                            <div class="p-3">
                                <div class="flex justify-between items-start mb-1">
                                    <div class="flex gap-2 items-center">
                                        <span class="px-3 py-1 rounded-full text-sm font-bold
                                            {{ $order->type === 'Medication' ? 'bg-emerald-100 text-emerald-800' :
                                              ($order->type === 'Monitoring' ? 'bg-sky-100 text-sky-800' :
                                              ($order->type === 'Laboratory' ? 'bg-amber-100 text-amber-800' :
                                              ($order->type === 'Transfer' ? 'bg-rose-100 text-rose-800' :
                                              ($order->type === 'Discharge' ? 'bg-lime-100 text-lime-800' : 'bg-slate-100 text-slate-800')))) }}">
                                            {{ $order->type }}
                                        </span>
                                        <span class="badge-enterprise text-xs {{ $order->status === 'Done' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                            {{ $order->status }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $order->frequency ?? 'Once' }}</span>
                                </div>
                                @if($order->medicine)
                                <div class="text-sm font-bold text-slate-800 mb-2">
                                    {{ $order->medicine->getFormattedLabel() }}
                                    <span class="text-xs font-normal text-gray-500 block">
                                        Quantity: {{ $order->quantity }}
                                    </span>
                                </div>
                                @endif
                                <div class="text-xs text-gray-600 mb-2">
                                    Instruction: {{ $order->instruction }}
                                </div>
                                @if($order->fulfilled_at)
                                <div class="text-xs text-gray-500 mb-2">
                                    {{ $order->status === 'Done' ? 'Completed' : 'Discontinued' }}: {{ $order->fulfilled_at->format('M d H:i') }}
                                </div>
                                @endif
                                @if($order->type === 'Laboratory' && $order->labResultFile)
                                <div class="flex gap-2">
                                    <a href="{{ route('document.view', $order->labResultFile->id) }}" target="_blank" class="btn-enterprise-primary text-xs flex-1 inline-flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        View Lab Result
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-gray-400 text-sm py-8">No order history.</div>
                        @endforelse
                    </div>
                </template>
            </div>
        </div>

        <!-- RIGHT COLUMN: CLINICAL STATUS -->
        <div class="lg:col-span-2 space-y-6 h-full overflow-y-auto">

            <!-- A. LATEST CLINICAL STATUS & PLAN -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- CARD A: LATEST VITALS  -->
                <div class="card-enterprise border-l-4 border-l-sky-400">
                    <div class="p-5">
                        <h3 class="text-sm text-slate-800 uppercase font-bold flex justify-between">
                            Current Vitals
                            <span class="text-sky-600">
                                @if($latestLog && isset($latestLog->data['bp']))
                                {{ $latestLog->created_at->diffForHumans() }}
                                @else
                                  From Admission
                                @endif
                            </span>
                        </h3>

                        @if($vitals)
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-2">
                            <div>
                                <div class="text-xs text-gray-800">BP</div>
                                <div class="font-mono text-lg font-bold">{{ $vitals['bp'] ?? '--' }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-800">Temp</div>
                                <div class="font-mono text-lg font-bold">{{ $vitals['temp'] ?? '--' }}°C</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-800">HR</div>
                                <div class="font-mono text-lg font-bold">{{ $vitals['hr'] ?? '--' }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-800">PR</div>
                                <div class="font-mono text-lg font-bold">{{ $vitals['pr'] ?? '--' }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-800">RR</div>
                                <div class="font-mono text-lg font-bold">{{ $vitals['rr'] ?? '--' }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-800">O2</div>
                                <div class="font-mono text-lg font-bold">{{ $vitals['o2'] ?? '--' }}%</div>
                            </div>
                        </div>
                        @else
                        <div class="text-center py-4 text-gray-800">No vitals recorded yet.</div>
                        @endif
                    </div>
                </div>

                <!-- CARD B: TREATMENT PLAN SNAPSHOT -->
                <div class="card-enterprise border-l-4 border-l-emerald-400">
                    <div class="p-5">
                        <h3 class="text-sm text-slate-800 uppercase font-bold">Active Treatment Plan</h3>

                        @if($admission->treatmentPlan)
                        <div class="font-bold text-lg text-slate-800">{{ $admission->treatmentPlan->main_problem }}</div>

                        <div class="grid grid-cols-2 gap-4 mt-2">
                            <div>
                                <div class="text-xs text-gray-800 uppercase font-semibold">Goals</div>
                                <ul class="list-disc list-inside text-sm font-medium">
                                    @foreach(array_slice($admission->treatmentPlan->goals ?? [], 0, 3) as $goal)
                                    <li>{{ $goal }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div>
                                <div class="text-xs text-gray-800 uppercase font-semibold">Interventions</div>
                                <ul class="list-disc list-inside text-sm font-medium">
                                    @foreach(array_slice($admission->treatmentPlan->interventions ?? [], 0, 3) as $intervention)
                                    <li>{{ $intervention }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @else
                        <div class="flex flex-col items-center justify-center py-2">
                            <p class="text-gray-800 text-sm mb-2">No active plan established.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- B. CLINICAL LOG HISTORY TABLE -->
            <x-clinical-history-table :clinicalLogs="$clinicalLogs" displayMode="physician" />
        </div>
    </div>

    <!-- CREATE ORDER MODAL  -->
    <dialog id="order_modal" class="modal" x-data="{ orderType: 'Medication' }">
        <div class="modal-enterprise w-11/12 max-w-2xl">
            <form method="dialog">
                <button class="absolute right-4 top-4 text-slate-400 hover:text-slate-600 transition-colors">✕</button>
            </form>
            <h3 class="text-lg font-bold text-slate-800 mb-6">Create Physician Order</h3>

            <form action="{{ route('physician.orders.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="admission_id" value="{{ $admission->id }}">

                <!-- 1. ORDER TYPE -->
                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-semibold">Order Type</span>
                    </label>
                    <select name="type" x-model="orderType" class="select-enterprise w-full">
                        <option value="Medication">Medication</option>
                        <option value="Monitoring">Monitoring / Vitals</option>
                        <option value="Laboratory">Laboratory Request</option>
                        <option value="Transfer">Transfer Patient</option>
                        <option value="Discharge">Ready for Discharge</option>
                    </select>
                </div>

                <!-- 2. MEDICATION SPECIFICS  -->
                <div x-show="orderType === 'Medication'" x-cloak class="space-y-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Select Medicine</span>
                        </label>
                        <select name="medicine_id" class="select-enterprise w-full">
                            <option value="">-- Choose from Pharmacy --</option>
                            @foreach($medicines as $med)
                            <option value="{{ $med->id }}">{{ $med->generic_name }} {{ $med->brand_name ? "({$med->brand_name})" : '' }} {{ $med->dosage }} - {{ $med->stock_on_hand }} avail</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Quantity</span>
                        </label>
                        <input type="number" name="quantity" class="input-enterprise w-full" value="1" min="1">
                    </div>
                </div>

                <!-- 3. FREQUENCY -->
                <div x-show="orderType === 'Medication' || orderType === 'Monitoring'" x-cloak class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-semibold">Frequency</span>
                    </label>
                    <select name="frequency" class="select-enterprise w-full">
                        <option value="Once">Stat / Once Only</option>
                        <option value="Every 1 Hour">Every 1 Hour (q1h)</option>
                        <option value="Every 2 Hours">Every 2 Hours (q2h)</option>
                        <option value="Every 4 Hours">Every 4 Hours (q4h)</option>
                        <option value="Every 6 Hours">Every 6 Hours (q6h)</option>
                        <option value="Every 8 Hours">Every 8 Hours (q8h)</option>
                        <option value="Every 12 Hours">Every 12 Hours (BID)</option>
                        <option value="Daily">Daily (OD)</option>
                        <option value="PRN">PRN (As Needed)</option>
                    </select>
                </div>

                <!-- 4. THE UNIVERSAL INSTRUCTION BOX -->
                <div x-show="orderType !== 'Discharge'" x-cloak class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-semibold" x-text="
                        orderType === 'Medication' ? 'Special Instructions (Optional)' :
                        orderType === 'Monitoring' ? 'What to Monitor?' :
                        orderType === 'Laboratory' ? 'Lab:
                        'Request Details'
                    "></span>
                    </label>
                    <textarea name="instruction" class="textarea-enterprise w-full h-24"
                        :placeholder="
                        orderType === 'Medication' ? 'e.g. Give after meals' :
                        orderType === 'Monitoring' ? 'e.g. Check BP and Neuro Vitals' :
                        orderType === 'Laboratory' ? 'e.g. CBC, Chest X-Ray' :
                        'Enter details...'
                    "></textarea>
                </div>

                <!-- 5. DISCHARGE NOTICE -->
                <div x-show="orderType === 'Discharge'" x-cloak class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-lg text-emerald-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-emerald-600 shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm">This will flag the patient as ready for discharge.</span>
                </div>

                <!-- MODAL ACTIONS -->
                <div class="modal-action pt-2">
                    <button type="button" onclick="order_modal.close()" class="btn-enterprise-secondary">Cancel</button>
                    <button type="submit" class="btn-enterprise-primary">Submit Order</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- VIEW LOG DETAILS MODAL -->
    <x-clinical-log-modal />

</div>

<script>
    function clinicalChart() {
        return {
            viewLogData: {},

            viewLog(logObject) {
                this.viewLogData = logObject;
                document.getElementById('view_log_modal').showModal();
            },
        }
    }
</script>

@endsection
