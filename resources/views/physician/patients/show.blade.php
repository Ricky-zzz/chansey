@extends('layouts.physician')

@section('content')
<div class="max-w-full mx-auto" x-data="clinicalChart()">
    <!-- BREADCRUMB -->
    <div class="text-sm breadcrumbs mb-3">
        <ul>
            <li><a href="{{ route('physician.dashboard') }}" class="link link-hover">Dashboard</a></li>
            <li><a href="{{ route('physician.mypatients.index') }}" class="link link-hover">My Patients</a></li>
            <li class="text-slate-700 font-semibold">
                {{ $admission->patient->last_name }}, {{ $admission->patient->first_name }}
            </li>
        </ul>
    </div>

    <!-- 1. HEADER: PATIENT CONTEXT -->
    <div class="flex flex-col md:flex-row justify-between items-start mb-6 gap-4 bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="avatar placeholder">
                <div class="bg-neutral text-neutral-content rounded-full w-16 h-16 flex items-center justify-center">
                    <span class="text-3xl">{{ substr($admission->patient->first_name, 0, 1) }}</span>
                </div>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-800">
                    {{ $admission->patient->last_name }}, {{ $admission->patient->first_name }}
                </h1>
                <div class="flex gap-2 items-center mt-1">
                    <span class="badge badge-lg badge-primary font-mono">
                        {{ $admission->bed ? $admission->bed->bed_code : 'Outpatient' }}
                    </span>
                    <span class="text-sm text-gray-500">{{ $admission->patient->age }}yo / {{ $admission->patient->sex }}</span>
                    <span class="text-sm text-gray-500">• Admitted: {{ $admission->admission_date->format('M d') }}</span>
                </div>
                <!-- Allergies Alert -->
                @if(!empty($admission->known_allergies))
                <div class="mt-2 flex gap-1 items-center">
                    <span class="text-xs font-bold text-gray-500">Allergies:</span>
                    @foreach($admission->known_allergies as $allergy)
                    <span class="badge badge-error text-white badge-xs">{{ $allergy }}</span>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- PRIMARY ACTIONS -->
        <div class="flex flex-col gap-2 items-end">
            <h2 class="text-xs font-bold text-gray-400 uppercase">Actions</h2>
            <div class="join">
                <!-- Order Modal Trigger -->
                <button onclick="order_modal.showModal()" class="btn btn-primary btn-sm join-item gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Write Order
                </button>

                <!-- Treatment Plan Button -->
                @if($admission->treatmentPlan)
                <a href="{{ route('physician.treatment-plan.edit', $admission->id) }}" class="btn btn-neutral btn-sm join-item text-white">Manage Plan</a>
                @else
                <a href="{{ route('physician.treatment-plan.create', $admission->id) }}" class="btn btn-neutral btn-sm join-item text-white">Create Plan</a>
                @endif
            </div>
        </div>
    </div>

    <!-- 2. TOP GRID: LATEST STATUS & PLAN SUMMARY -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8" x-data="{ orderView: 'active' }">

        <!-- LEFT COLUMN: ORDERS (ACTIVE OR HISTORY) (SCROLLABLE) -->
        <div class="lg:col-span-1">
            <div class="flex justify-between items-center mb-4 px-1">
                <h3 class="font-bold text-slate-700 text-lg" x-text="orderView === 'active' ? 'Active Orders' : 'Order History'"></h3>
                <div class="join border border-slate-300 rounded-lg">
                    <button @click="orderView = 'active'" :class="orderView === 'active' ? 'btn-active' : ''" class="btn btn-xs join-item">Active</button>
                    <button @click="orderView = 'history'" :class="orderView === 'history' ? 'btn-active' : ''" class="btn btn-xs join-item">History</button>
                </div>
                <span class="badge badge-ghost" x-text="orderView === 'active' ? {{ $admission->medicalOrders->count() }} : {{ $orderHistory->count() }}"></span>
            </div>

            <div class="space-y-4 overflow-y-auto">
                <!-- ACTIVE ORDERS -->
                <template x-if="orderView === 'active'">
                    <div>
                        @forelse($admission->medicalOrders as $order)
                        <div class="card mb-1.5 bg-base-100 shadow border border-l-4
                            {{ $order->type === 'Medication' ? 'border-l-emerald-500' : 
                              ($order->type === 'Monitoring' ? 'border-l-blue-500' : 'border-l-slate-300') }}">
                            <div class="card-body p-3">
                                <div class="flex justify-between items-start mb-1">
                                    <div class="flex gap-2 items-center">
                                        <span class="px-3 py-1 rounded-full text-sm font-bold
                                        {{ $order->type === 'Medication' ? 'bg-emerald-600 text-white' : 
                                  ($order->type === 'Monitoring' ? 'bg-sky-600 text-white' : 
                                  ($order->type === 'Laboratory' ? 'bg-amber-600 text-white' :
                                  ($order->type === 'Transfer' ? 'bg-rose-600 text-white' : 'bg-lime-600 text-white'))) }}">
                                            {{ $order->type }}
                                        </span>
                                        <span class="badge badge-sm {{ $order->status === 'Pending' ? 'badge-warning' : 'badge-neutral' }}">
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
                                        <button class="btn btn-sm btn-error btn-outline w-full">Delete</button>
                                    </form>
                                    @elseif($order->status === 'Pending' || $order->status === 'Active')
                                    <form action="{{ route('physician.orders.discontinue', $order->id) }}" method="POST" class="w-full">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-error btn-outline w-full">Stop</button>
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
                        <div class="card bg-base-100 shadow border border-l-4
                            {{ $order->type === 'Medication' ? 'border-l-emerald-500' : 
                              ($order->type === 'Monitoring' ? 'border-l-blue-500' : 'border-l-slate-300') }}">
                            <div class="card-body p-3">
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
                                        <span class="badge badge-sm {{ $order->status === 'Done' ? 'badge-success' : 'badge-error' }}">
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
                                    <a href="{{ route('document.view', $order->labResultFile->id) }}" target="_blank" class="btn btn-xs btn-primary text-white flex-1">
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
        <div class="lg:col-span-2 space-y-6">

            <!-- A. LATEST CLINICAL STATUS & PLAN -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- CARD A: LATEST VITALS  -->
                <div class="card bg-base-100 shadow-xl border-l-8 border-info">
                    <div class="card-body">
                        <h3 class="card-title text-sm text-gray-800 uppercase font-bold flex justify-between">
                            Current Vitals
                            <span class="text-info">
                                @if($latestLog && isset($latestLog->data['bp_systolic']))
                                {{ $latestLog->created_at->diffForHumans() }}
                                @else
                                Admission ({{ $admission->admission_date->format('M d H:i') }})
                                @endif
                            </span>
                        </h3>

                        @if($vitals)
                        <div class="grid grid-cols-2 gap-4 mt-2">
                            <div>
                                <div class="text-xs text-gray-800">BP</div>
                                <div class="font-mono text-lg font-bold">{{ $vitals['bp_systolic'] ?? '--' }}/{{ $vitals['bp_diastolic'] ?? '--' }}</div>
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
                <div class="card bg-base-100 shadow-xl border-l-8 border-secondary">
                    <div class="card-body">
                        <h3 class="card-title text-sm text-gray-800 uppercase font-bold">Active Treatment Plan</h3>

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
        <div class="modal-box w-11/12 max-w-2xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-bold text-xl mb-6">Create Physician Order</h3>

            <form action="{{ route('physician.orders.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="admission_id" value="{{ $admission->id }}">

                <!-- 1. ORDER TYPE -->
                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-semibold">Order Type</span>
                    </label>
                    <select name="type" x-model="orderType" class="select select-bordered w-full">
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
                        <select name="medicine_id" class="select select-bordered w-full">
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
                        <input type="number" name="quantity" class="input input-bordered w-full" value="1" min="1">
                    </div>
                </div>

                <!-- 3. FREQUENCY -->
                <div x-show="orderType === 'Medication' || orderType === 'Monitoring'" x-cloak class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-semibold">Frequency</span>
                    </label>
                    <select name="frequency" class="select select-bordered w-full">
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
                    <textarea name="instruction" class="textarea textarea-bordered w-full h-24"
                        :placeholder="
                        orderType === 'Medication' ? 'e.g. Give after meals' :
                        orderType === 'Monitoring' ? 'e.g. Check BP and Neuro Vitals' :
                        orderType === 'Laboratory' ? 'e.g. CBC, Chest X-Ray' :
                        'Enter details...'
                    "></textarea>
                </div>

                <!-- 5. DISCHARGE NOTICE -->
                <div x-show="orderType === 'Discharge'" x-cloak class="alert alert-success">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>This will flag the patient as ready for discharge.</span>
                </div>

                <!-- MODAL ACTIONS -->
                <div class="modal-action pt-2">
                    <form method="dialog">
                        <button class="btn btn-outline btn-error">Cancel</button>
                    </form>
                    <button type="submit" class="btn btn-primary">Submit Order</button>
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