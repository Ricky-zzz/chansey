@extends('layouts.accountant')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <div class="text-center mb-8">
        <div class="inline-flex p-4 bg-success text-white rounded-full mb-4 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
        </div>
        <h1 class="text-4xl font-black text-slate-800">Payment Complete</h1>
        <p class="text-gray-500">Receipt #: {{ $billing->receipt_number }}</p>
    </div>

    <!-- TICKET CARD -->
    <div class="card bg-white shadow-xl border border-slate-200 overflow-hidden">
        
        <!-- Top Tear -->
        <div class="bg-slate-800 p-6 text-white text-center">
            <div class="text-xs uppercase font-bold opacity-50 tracking-widest">Amount Paid</div>
            <div class="text-5xl font-mono font-bold mt-2">₱{{ number_format($billing->final_total, 2) }}</div>
        </div>

        <div class="card-body p-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <div class="text-xs font-bold text-gray-400 uppercase">Billed To</div>
                    <div class="font-bold text-lg">{{ $admission->patient->last_name }}, {{ $admission->patient->first_name }}</div>
                </div>
                <div class="text-right">
                    <div class="text-xs font-bold text-gray-400 uppercase">Date</div>
                    <div class="font-bold">{{ $billing->created_at->format('M d, Y h:i A') }}</div>
                </div>
            </div>

            <!-- SUMMARY LIST WITH ACCORDIONS -->
            <div class="space-y-2 border-t border-b border-dashed border-gray-300 py-6 my-2">
                
                <!-- ROOM CHARGES ACCORDION -->
                <div class="collapse collapse-arrow bg-gray-50 border border-gray-200">
                    <input type="radio" name="billing-accordion" />
                    <div class="collapse-title font-semibold flex justify-between items-center pr-4">
                        <span>Room Charges</span>
                        <span class="font-mono font-bold">₱{{ number_format($billing->breakdown['room_total'], 2) }}</span>
                    </div>
                    <div class="collapse-content">
                        <div class="space-y-3 pt-4">
                            @forelse($billing->breakdown['movements'] ?? [] as $mov)
                            <div class="border-l-4 border-blue-400 pl-4 text-sm">
                                <div class="font-semibold">{{ $mov['room_number'] }} ({{ $mov['bed_code'] }})</div>
                                <div class="text-gray-600">{{ $mov['started_at'] }} - {{ $mov['ended_at'] }}</div>
                                <div class="flex justify-between mt-1">
                                    <span>{{ $mov['days'] }} day(s) × ₱{{ number_format($mov['price'], 2) }}</span>
                                    <span class="font-mono font-bold">₱{{ number_format($mov['total'], 2) }}</span>
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-sm">No room movement data</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- ITEMS & SERVICES ACCORDION -->
                <div class="collapse collapse-arrow bg-gray-50 border border-gray-200">
                    <input type="radio" name="billing-accordion" />
                    <div class="collapse-title font-semibold flex justify-between items-center pr-4">
                        <span>Items & Services</span>
                        <span class="font-mono font-bold">₱{{ number_format($billing->breakdown['items_total'], 2) }}</span>
                    </div>
                    <div class="collapse-content">
                        <div class="space-y-3 pt-4">
                            @forelse($billing->breakdown['items_list'] ?? [] as $item)
                            <div class="border-l-4 border-purple-400 pl-4 text-sm">
                                <div class="font-semibold">{{ $item['name'] }}</div>
                                <div class="flex justify-between mt-1">
                                    <span class="text-gray-600">Qty: {{ $item['quantity'] }} × ₱{{ number_format($item['amount'], 2) }}</span>
                                    <span class="font-mono font-bold">₱{{ number_format($item['total'], 2) }}</span>
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-sm">No items charged</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- PROFESSIONAL FEE -->
                @if(($billing->breakdown['pf_fee'] ?? 0) > 0)
                <div class="flex justify-between p-3 bg-orange-50 border border-orange-200 rounded text-sm">
                    <span class="font-semibold">Professional Fee (Doctor)</span>
                    <span class="font-mono font-bold">₱{{ number_format($billing->breakdown['pf_fee'], 2) }}</span>
                </div>
                @endif

                <!-- DEDUCTIONS ACCORDION -->
                @php
                    $philhealth = $billing->breakdown['deductions']['philhealth'] ?? 0;
                    $hmo = $billing->breakdown['deductions']['hmo'] ?? 0;
                    $totalDeductions = $philhealth + $hmo;
                @endphp
                @if($totalDeductions > 0)
                <div class="collapse collapse-arrow bg-emerald-50 border border-emerald-200">
                    <input type="radio" name="billing-accordion" />
                    <div class="collapse-title font-semibold flex justify-between items-center pr-4 text-emerald-700">
                        <span>Less: Deductions</span>
                        <span class="font-mono font-bold">- ₱{{ number_format($totalDeductions, 2) }}</span>
                    </div>
                    <div class="collapse-content">
                        <div class="space-y-2 pt-4">
                            @if($philhealth > 0)
                            <div class="flex justify-between p-2 bg-white rounded border border-emerald-100 text-sm">
                                <span class="font-semibold">PhilHealth</span>
                                <span class="font-mono font-bold">- ₱{{ number_format($philhealth, 2) }}</span>
                            </div>
                            @endif
                            @if($hmo > 0)
                            <div class="flex justify-between p-2 bg-white rounded border border-emerald-100 text-sm">
                                <span class="font-semibold">HMO / Insurance</span>
                                <span class="font-mono font-bold">- ₱{{ number_format($hmo, 2) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="flex justify-between items-center text-sm text-gray-500 mt-4">
                <span>Processed by: {{ $billing->processedBy->name ?? 'Admin' }}</span>
                <span class="badge badge-success text-white">CLEARED</span>
            </div>
        </div>
    </div>

    <!-- ACTIONS -->
    <div class="flex justify-center gap-4 mt-8">
        <a href="{{ route('accountant.dashboard') }}" class="btn btn-error">Back to Dashboard</a>
        
        <!-- PRINT BUTTON -->
        <a href="{{ route('accountant.billing.print', $billing->id) }}" target="_blank" class="btn btn-primary text-white gap-2 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            Print Receipt (PDF)
        </a>
    </div>

</div>
@endsection