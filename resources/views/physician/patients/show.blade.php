@extends('layouts.physician')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- 1. PATIENT HEADER -->
    <div class="flex flex-col md:flex-row justify-between items-start mb-6 gap-4">
        <div>
            <div class="text-sm breadcrumbs mb-1">
                <ul>
                    <li><a href="{{ route('physician.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('physician.patients.index') }}">My Patients</a></li>
                    <li class="font-bold text-primary">{{ $admission->patient->last_name }}</li>
                </ul>
            </div>
            <h2 class="text-3xl font-black text-slate-800 flex items-center gap-3">
                {{ $admission->patient->last_name }}, {{ $admission->patient->first_name }}
                <span class="badge badge-lg badge-neutral font-mono text-sm">
                    {{ $admission->bed->bed_code ?? 'Waiting' }}
                </span>
            </h2>
            <div class="text-sm text-gray-500 mt-1">
                {{ $admission->patient->age }} yo / {{ $admission->patient->sex }} • Admitted: {{ $admission->admission_date->format('M d') }}
            </div>
        </div>

        <!-- PRIMARY ACTIONS -->
        <div class="join">
            <!-- Order Modal Trigger -->
            <button onclick="order_modal.showModal()" class="btn btn-primary join-item gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Write Order
            </button>

            <!-- Treatment Plan Button -->
            @if($admission->treatmentPlan)
            <a href="{{ route('physician.treatment-plan.edit', $admission->id) }}" class="btn btn-secondary join-item">Manage Plan</a>
            @else
            <a href="{{ route('physician.treatment-plan.create', $admission->id) }}" class="btn btn-secondary join-item">Create Plan</a>
            @endif
        </div>
    </div>

    <!-- 2. TOP GRID: LATEST STATUS & PLAN SUMMARY -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        <!-- CARD A: LATEST CLINICAL STATUS  -->
        <div class="card bg-base-100 shadow-xl border-l-8 border-info">
            <div class="card-body">
                <h3 class="card-title text-sm text-gray-800 uppercase font-bold flex justify-between">
                    Latest Clinical Update
                    <span class="text-info">{{ $latestLog ? $latestLog->created_at->diffForHumans() : 'No Data' }}</span>
                </h3>

                @if($latestLog)
                @php $data = $latestLog->data; @endphp
                <div class="grid grid-cols-2 gap-4 mt-2">
                    <div>
                        <div class="text-xs text-gray-800">Vital Signs</div>
                        <div class="font-mono text-lg font-bold">
                            BP: {{ $data['bp'] ?? '--' }} <br>
                            T: {{ $data['temp'] ?? '--' }}°C
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-800">Latest Note</div>
                        <p class="italic text-sm text-gray-600 line-clamp-3">
                            "{{ $data['observation'] ?? ($data['note'] ?? 'No note recorded') }}"
                        </p>
                    </div>
                </div>
                <div class="mt-4 text-xs text-right text-gray-800">
                    Logged by: {{ $latestLog->user->name ?? 'Nurse' }}
                </div>
                @else
                <div class="text-center py-4 text-gray-800">No clinical logs recorded yet.</div>
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

    <!-- 3. DOCTOR'S ORDERS  -->
    <div class="card bg-base-100 shadow-xl border border-base-200 mb-8">
        <div class="card-body">
            <h3 class="card-title text-slate-700">Active Orders</h3>

            <div class="overflow-x-auto">
                <table class="table">
                    <thead class="text-slate-600">
                        <tr>
                            <th>Type</th>
                            <th>Instruction</th>
                            <th>Frequency</th>
                            <th>Status</th>
                            <th class="w-40 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($admission->medicalOrders as $order)
                        <tr>
                            <td>
                                <div class="badge badge-outline">{{ $order->type }}</div>
                            </td>
                            <td class="font-bold text-slate-700">
                                {{ $order->instruction }}
                                @if($order->medicine)
                                <span class="text-xs font-normal text-gray-500 block">
                                    Using: {{ $order->medicine->name }} (Qty: {{ $order->quantity }})
                                </span>
                                @endif
                            </td>
                            <td>{{ $order->frequency ?? 'Once' }}</td>
                            <td>
                                <span class="badge {{ $order->status === 'Pending' ? 'badge-warning' : 'badge-neutral' }}">
                                    {{ $order->status }}
                                </span>
                            </td>

                            <td class="text-right">

                                @if($order->status === 'Pending' && $order->clinicalLogs->count() === 0)

                                <form action="{{ route('physician.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Delete this order?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-xs btn-outline text-error">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </button>
                                </form>

                                @elseif($order->status === 'Pending' || $order->status === 'Active')

                                <form action="{{ route('physician.orders.discontinue', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-xs btn-warning text-white">Stop / Discontinue</button>
                                </form>

                                @else
                                <span class="text-xs text-gray-800">Locked</span>
                                @endif

                            </td>


                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-800 italic">No active orders.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 4. CLINICAL LOGS HISTORY  -->
    <div class="collapse collapse-arrow bg-base-100 border border-base-200">
        <input type="checkbox" />
        <div class="collapse-title text-xl font-medium text-slate-600">
            Clinical Log History
        </div>
        <div class="collapse-content">
            <div class="overflow-x-auto max-h-60">
                <table class="table table-xs">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Data</th>
                            <th>Nurse</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admission->clinicalLogs as $log)
                        <tr>
                            <td class="font-mono">{{ $log->created_at->format('M d H:i') }}</td>
                            <td class="font-bold">{{ $log->type }}</td>
                            <td>
                                <!-- Dirty quick dump of JSON data for reading -->
                                @foreach($log->data as $key => $val)
                                <span class="badge badge-ghost badge-xs">{{ $key }}: {{ $val }}</span>
                                @endforeach
                            </td>
                            <td>{{ $log->user->name ?? 'Unknown' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
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
                    <option value="Dietary">Dietary Instruction</option>
                    <option value="Utility">General/Utility</option>
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
                        orderType === 'Dietary' ? 'Diet Details' :
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

@endsection