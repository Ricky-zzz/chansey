@extends('layouts.clinic')

@section('content')
<div class="max-w-full mx-auto" x-data="clinicalChart()" data-stations='@json($stations)' data-beds='@json($transferBeds)'>

    <!-- BREADCRUMB -->
    <div class="text-sm breadcrumbs mb-3">
        <ul>
            <li><a href="{{ route('nurse.clinical.ward.index') }}" class="link link-hover">Ward</a></li>
            <li class="text-slate-700 font-semibold">
                {{ $admission->patient->getFullNameAttribute() }}
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
                    {{ $admission->patient->getFullNameAttribute() }}
                    @if($admission->status !== 'Admitted')
                        <span class="ml-2 inline-block px-4 py-1 bg-rose-100 text-rose-700 rounded-full text-sm font-semibold">
                            {{ $admission->status}}
                        </span>
                    @endif
                </h1>
                <div class="flex gap-2 items-center mt-1 text-sm text-gray-500">
                    Details:<span class="font-bold text-gray-600">{{ $admission->patient->age }}yo / {{ $admission->patient->sex }}
                    • Admitted: {{ $admission->admission_date->format('M d') }}</span>
                </div>
                <div class="flex gap-2 items-center mt-1 text-sm text-gray-600"">
                    Room: <span class="font-bold text-gray-600">{{ $admission->bed->bed_code ?? "Outpatient" }}</span>
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

        <div class="flex flex-col gap-2 items-end">
            <h2 class="text-xs font-bold text-gray-400 uppercase">Actions</h2>
            <div class="flex flex-row gap-2 items-center">
                <div>
                    <button onclick="supply_modal.showModal()" class="btn bg-amber-500 hover:bg-amber-400 text-white btn-sm gap-2 border-2 border-amber-600 hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Charge Item
                    </button>
                </div>
                <div class="join">
                    <!-- SPONTANEOUS LOG BUTTON -->
                    <button @click="openLogModal(null, null)" class="mr-2 btn bg-sky-500 hover:bg-sky-400 text-white btn-sm gap-2 border-2 border-sky-600 hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Add Clinical Note
                    </button>
                    <!-- NURSING CARE PLAN -->
                    <a href="{{ route('nurse.clinical.care-plan.edit', $admission->id) }}" class="btn bg-emerald-500 hover:bg-emerald-400 text-white btn-sm border-2 border-emerald-600 hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>
                        Care Plan</a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- LEFT COLUMN: DOCTOR'S ORDERS  -->
        <div class="lg:col-span-1 space-y-4 max-h-[600px] overflow-y-auto pr-2">
            <div class="flex justify-between items-center px-1">
                <h3 class="font-bold text-slate-700 text-lg">Active Orders</h3>
                <span class="badge badge-ghost">{{ $activeOrders->count() }} Pending</span>
            </div>

            <!-- Loop Orders -->
            @forelse($activeOrders as $order)
            <div class="card bg-white border border-l-4 shadow-sm hover:shadow-md transition-shadow
                            {{ $order->type === 'Medication' ? 'border-l-emerald-500' : 
                              ($order->type === 'Monitoring' ? 'border-l-sky-500' : 
                              ($order->type === 'Laboratory' ? 'border-l-amber-500' :
                              ($order->type === 'Transfer' ? 'border-l-rose-500' : 'border-l-lime-500'))) }}">
                <div class="card-body p-4">

                    <!-- Top Row: Type & Freq -->
                    <div class="flex justify-between items-start mb-2">
                        <span class="px-3 py-1 rounded-full text-sm font-bold
                                {{ $order->type === 'Medication' ? 'bg-emerald-600 text-white' : 
                                  ($order->type === 'Monitoring' ? 'bg-sky-600 text-white' : 
                                  ($order->type === 'Laboratory' ? 'bg-amber-600 text-white' :
                                  ($order->type === 'Transfer' ? 'bg-rose-600 text-white' : 'bg-lime-600 text-white'))) }}">
                            {{ $order->type }}
                        </span>
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
                        <button
                            @click="openLogModal(@js($order->id), 'Medication', @js($order->medicine->brand_name ?? $order->medicine->generic_name ?? ''), @js($order->quantity ?? 1))"
                            class="btn btn-sm btn-{{ $status['color'] }} {{ $status['disabled'] ? 'text-gray-700' : 'text-white' }} w-full {{ isset($status['animate']) ? 'animate-pulse' : '' }}"
                            {{ $status['disabled'] ? 'disabled' : '' }}>
                            {{ $status['label'] }}
                        </button>

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
            <div class="p-8 text-center text-gray-400 bg-slate-50 rounded-xl border border-dashed">
                No active orders.
            </div>
            @endforelse
        </div>

        <!-- RIGHT COLUMN: SNAPSHOTS & HISTORY -->
        <div class="lg:col-span-2 space-y-6">

            <!-- A. SNAPSHOTS ROW -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Latest Vitals -->
                <div class="card bg-white shadow-sm border border-slate-200">
                    <div class="card-body p-4">
                        <h3 class="text-xs font-bold text-gray-400 uppercase mb-2">Current Vitals</h3>
                        @if($vitals)
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div><span class="text-gray-500">BP:</span> <span class="font-mono font-bold">{{ $vitals['bp_systolic'] ?? '--' }}/{{ $vitals['bp_diastolic'] ?? '--' }}</span></div>
                            <div><span class="text-gray-500">Temp:</span> <span class="font-mono font-bold">{{ $vitals['temp'] ?? '--' }}°C</span></div>
                            <div><span class="text-gray-500">HR:</span> <span class="font-mono font-bold">{{ $vitals['hr'] ?? '--' }}</span></div>
                            <div><span class="text-gray-500">O2:</span> <span class="font-mono font-bold text-primary">{{ $vitals['o2'] ?? '--' }}%</span></div>
                        </div>
                        <div class="text-[10px] text-gray-400 mt-2 text-right">
                            @if($latestLog && isset($latestLog->data['bp_systolic']))
                            {{ $latestLog->created_at->diffForHumans() }}
                            @else
                            Admission Vitals ({{ $admission->admission_date->format('M d H:i') }})
                            @endif
                        </div>
                        @else
                        <div class="text-gray-400 text-sm italic">No vitals recorded yet.</div>
                        @endif
                    </div>
                </div>

                <!-- Treatment Plan Summary -->
                <div class="card bg-white shadow-sm border border-slate-200">
                    <div class="card-body p-4">
                        <h3 class="text-xs font-bold text-gray-400 uppercase mb-2">Care Plan Focus</h3>
                        @if($admission->nursingCarePlans)
                        <div class="text-sm font-bold text-slate-800 mb-1 line-clamp-1">{{ $admission->nursingCarePlans->diagnosis }}</div>
                        <ul class="list-disc list-inside text-xs text-gray-600">
                            @foreach(array_slice($admission->nursingCarePlans->interventions ?? [], 0, 2) as $goal)
                            <li>{{ $goal }}</li>
                            @endforeach
                        </ul>
                        @else
                        <div class="text-gray-400 text-sm italic">No active plan.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- B. CLINICAL LOG HISTORY -->
            <x-clinical-history-table :clinicalLogs="$clinicalLogs" displayMode="nurse" />

        </div>
    </div>

    <!-- LOG MODAL -->
    <dialog id="log_modal" class="modal">
        <div class="modal-box w-11/12 max-w-2xl">
            <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                <span x-text="logType === 'Medication' ? ' Give Medication' : (orderId ? ' Clinical Note / Monitoring' : ' Spontaneous Clinical Log')"></span>
            </h3>

            <form action="{{ route('nurse.clinical.logs.store', $admission->id) }}" method="POST">
                @csrf
                <input type="hidden" name="medical_order_id" :value="orderId">
                <input type="hidden" name="type" :value="logType">

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
                <div x-show="logType === 'Vitals' || logType === 'Medication'" class="bg-base-200 p-4 rounded-lg mb-4">
                    <div class="label font-bold text-xs uppercase text-slate-500 mb-2">
                        Vital Signs <span x-show="logType === 'Medication'">(Optional)</span>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="form-control">
                            <label class="label text-xs">BP Systolic</label>
                            <input type="number" name="bp_systolic" class="input input-sm input-bordered">
                        </div>
                        <div class="form-control">
                            <label class="label text-xs">BP Diastolic</label>
                            <input type="number" name="bp_diastolic" class="input input-sm input-bordered">
                        </div>
                        <div class="form-control">
                            <label class="label text-xs">Temp (°C)</label>
                            <input type="number" step="0.1" name="temp" class="input input-sm input-bordered">
                        </div>
                        <div class="form-control">
                            <label class="label text-xs">Heart Rate</label>
                            <input type="number" name="heart_rate" class="input input-sm input-bordered">
                        </div>
                        <div class="form-control">
                            <label class="label text-xs">Resp Rate</label>
                            <input type="number" name="respiratory_rate" class="input input-sm input-bordered">
                        </div>
                        <div class="form-control">
                            <label class="label text-xs">O2 Sat (%)</label>
                            <input type="number" name="o2_sat" class="input input-sm input-bordered">
                        </div>
                    </div>
                </div>

                <!-- NOTES / REMARKS (Always Visible) -->
                <div class="form-control">
                    <label class="label font-bold text-xs uppercase text-slate-500">
                        <span x-text="logType === 'Medication' ? 'Remarks / Patient Reaction' : 'Observation / Note'"></span>
                    </label>
                    <textarea name="observation" class="textarea textarea-bordered w-full h-24" placeholder="Enter details..."></textarea>
                </div>

                <div class="modal-action">
                    <button type="button" class="btn" onclick="log_modal.close()">Cancel</button>
                    <button type="submit" class="btn btn-primary" x-text="logType === 'Medication' ? 'Confirm & Charge' : 'Save Log'">Save Log</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- LAB UPLOAD MODAL -->
    <dialog id="lab_modal" class="modal">
        <div class="modal-box w-11/12 max-w-2xl">
            <h3 class="font-bold text-lg mb-2 flex items-center gap-2 ">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3v-6" />
                </svg>
                Upload Lab Result
            </h3>
            <p class="text-xs text-gray-500 mb-4">Complete the lab order by uploading findings and supporting documents</p>

            <form action="{{ route('nurse.clinical.orders.upload_result') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="medical_order_id" x-model="labOrderId">

                <!-- Order Context Alert -->
                <div class="alert alert-info text-xs mb-6 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <strong>Order:</strong> <span x-text="labInstruction" class="font-mono"></span>
                    </div>
                </div>

                <!-- 1. The Finding -->
                <div class="form-control mb-5">
                    <label class="label font-bold text-xs uppercase text-slate-500 mb-1">
                        <span>Result / Finding</span>
                        <span class="text-error">*</span>
                    </label>
                    <textarea name="findings" class="textarea textarea-bordered h-24" placeholder="e.g. Normal, Fracture detected, High WBC count, Elevated cholesterol..." required></textarea>
                    <label class="label text-xs text-gray-400">
                        <span>Describe the lab findings clearly</span>
                    </label>
                </div>

                <!-- 2. The File Upload -->
                <div class="form-control mb-6">
                    <label class="label font-bold text-xs uppercase text-slate-500 mb-1">
                        <span>Scan / PDF / Image</span>
                        <span class="text-error">*</span>
                    </label>
                    <input type="file" name="result_file" class="file-input file-input-bordered w-full" accept=".pdf,.jpg,.png,.jpeg" required />
                    <label class="label text-xs text-gray-400">
                        <span>Supported: PDF, JPG, PNG (Max 5MB)</span>
                    </label>
                </div>

                <!-- Form Actions -->
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="lab_modal.close()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8m0 8l-6-4m6 4l6-4" />
                        </svg>
                        Upload & Complete
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- VIEW LOG DETAILS MODAL  -->
    <x-clinical-log-modal />

    <!-- TRANSFER REQUEST MODAL -->
    <dialog id="transfer_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4 text-warning">Request Patient Transfer</h3>

            <form action="{{ route('nurse.clinical.orders.transfer') }}" method="POST">
                @csrf
                <input type="hidden" name="admission_id" value="{{ $admission->id }}">
                <input type="hidden" name="medical_order_id" x-model="transferOrderId">

                <div class="alert alert-info text-xs mb-4">
                    <span class="font-bold">Doctor's Order:</span> <span x-text="transferInstruction"></span>
                </div>

                <!-- 1. SELECT TARGET STATION -->
                <div class="form-control mb-4">
                    <label class="label font-bold text-xs">Target Station / Ward</label>
                    <select name="target_station_id" x-model="selectedTargetStation" class="select select-bordered w-full" required>
                        <option value="" disabled selected>Select Destination Station</option>
                        <template x-for="station in transferStations" :key="station.id">
                            <option :value="station.id" x-text="station.station_name"></option>
                        </template>
                    </select>
                </div>

                <!-- 2. SELECT TARGET BED -->
                <div class="form-control mb-4">
                    <label class="label font-bold text-xs">Specific Bed</label>
                    <select name="target_bed_id" class="select select-bordered w-full" :disabled="!selectedTargetStation" required>
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
                    <label class="label font-bold text-xs">Reason / Remarks</label>
                    <textarea name="remarks" class="textarea textarea-bordered h-20" placeholder="e.g. Needs isolation, Upgrading to Private..."></textarea>
                </div>

                <div class="modal-action">
                    <button type="button" class="btn" onclick="transfer_modal.close()">Cancel</button>
                    <button type="submit" class="btn btn-warning">Submit Request</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- SUPPLY CHARGE MODAL -->
    <dialog id="supply_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4 text-warning">Charge Supply / Utility</h3>
            <p class="text-sm text-gray-500 mb-4">Use this to record items consumed by the patient (Linens, Kits, etc).</p>

            <form action="{{ route('nurse.clinical.supplies.store') }}" method="POST" id="supply_form">
                @csrf
                <input type="hidden" name="admission_id" value="{{ $admission->id }}">

                <div class="form-control w-full mb-4">
                    <label class="label font-bold">Select Item</label>
                    @if($supplies && $supplies->count() > 0)
                    <select name="inventory_item_id" class="select select-bordered w-full" required>
                        <option value="" disabled selected>-- Select Item --</option>
                        @foreach($supplies as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->item_name }} (₱{{ number_format($item->price, 2) }}) - Stock: {{ $item->quantity }}
                            </option>
                        @endforeach
                    </select>
                    @else
                    <div class="alert alert-warning text-sm">
                        No inventory items available. Please add items to inventory first.
                    </div>
                    @endif
                </div>

                <div class="form-control w-full mb-4">
                    <label class="label font-bold">Quantity</label>
                    <input type="number" name="quantity" class="input input-bordered" value="1" min="1" required>
                </div>

                <div class="form-control w-full mb-6">
                    <label class="label font-bold">Remarks</label>
                    <textarea name="remarks" class="textarea textarea-bordered h-20" placeholder="e.g. Patient requested extra pillows, Linens soiled..."></textarea>
                </div>

                <div class="modal-action">
                    <button type="button" class="btn" onclick="supply_modal.close()">Cancel</button>
                    <button type="submit" class="btn btn-warning">Confirm & Charge</button>
                </div>
            </form>
        </div>
    </dialog>

</div>

<script>
    function clinicalChart() {
        return {
            logType: 'Notes',
            orderId: null,
            medName: '',
            medQty: 0,

            transferOrderId: null,
            transferInstruction: '',
            transferStations: [],
            transferRawBeds: [],
            selectedTargetStation: '',
            viewLogData: {},

            init() {
                this.transferStations = JSON.parse(
                    this.$el.dataset.stations
                );
                this.transferRawBeds = JSON.parse(
                    this.$el.dataset.beds
                );
            },

            filteredTransferBeds() {
                if (!this.selectedTargetStation) return [];
                return this.transferRawBeds.filter(
                    bed => bed.station_id == this.selectedTargetStation
                );
            },

            openLogModal(id, type, medName = '', medQty = 0) {
                this.orderId = id;
                this.logType = type || 'Notes';
                this.medName = medName;
                this.medQty = parseInt(medQty) || 0;
                document.getElementById('log_modal').showModal();
            },

            openLabModal(id, instruction) {
                this.labOrderId = id;
                this.labInstruction = instruction;
                document.getElementById('lab_modal').showModal();
            },

            openTransferModal(id, instruction) {
                this.transferOrderId = id;
                this.transferInstruction = instruction;
                this.selectedTargetStation = '';
                document.getElementById('transfer_modal').showModal();
            },
            viewLog(logObject) {
                this.viewLogData = logObject;
                document.getElementById('view_log_modal').showModal();
            },
        }
    }
</script>

@endsection