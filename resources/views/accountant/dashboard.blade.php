@extends('layouts.accountant')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- 1. HEADER & STATS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card-enterprise border-l-4 border-l-amber-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-4xl font-bold text-slate-800">{{ $pendingCount }}</h2>
                    <p class="uppercase text-xs font-semibold text-slate-500 mt-1">Pending Clearance</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-amber-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Sales Today -->
        <div class="card-enterprise border-l-4 border-l-emerald-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-4xl font-bold text-slate-800">₱{{ number_format($collectedToday) }}</h2>
                    <p class="uppercase text-xs font-semibold text-slate-500 mt-1">Collected Today</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Processed -->
        <div class="card-enterprise border-l-4 border-l-sky-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-4xl font-bold text-slate-800">{{ $dischargedToday }}</h2>
                    <p class="uppercase text-xs font-semibold text-slate-500 mt-1">Discharged Today</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-sky-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. QUEUE TABLE (The Worklist) -->
    <div class="card-enterprise p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Billing Queue</h2>
                <p class="text-sm text-slate-500">Patients cleared by doctors, waiting for payment.</p>
            </div>

            <!-- Simple Search -->
            <form action="{{ route('accountant.dashboard') }}" method="GET" class="flex">
                <input type="text" name="search" class="input-enterprise rounded-r-none text-sm"
                    placeholder="Search Patient..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn-enterprise-primary rounded-l-none text-sm">Search</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="table-enterprise w-full">
                <thead>
                    <tr>
                        <th>Admission #</th>
                        <th>Patient</th>
                        <th>Room / Accommodation</th>
                        <th>Discharge Aproval Date</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($readyForBilling as $admission)
                    <tr>
                        <td class="font-mono font-bold text-emerald-700">{{ $admission->admission_number }}</td>

                        <td>
                            <div class="font-bold">{{ $admission->patient->last_name }}, {{ $admission->patient->first_name }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $admission->billingInfo->payment_type ?? 'Cash' }}
                                @if($admission->billingInfo?->primary_insurance_provider)
                                / {{ $admission->billingInfo->primary_insurance_provider }}
                                @endif
                            </div>
                        </td>

                        <td>
                            <span class="badge-enterprise bg-slate-100 text-slate-600">{{ $admission->bed->bed_code ?? 'None' }}</span>
                            <div class="text-xs text-gray-500 mt-1">
                                ₱{{ number_format($admission->bed->room->price_per_night ?? 0, 2) }} / night
                            </div>
                        </td>

                        <td>
                            <div class="text-sm font-bold">{{ $admission->updated_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $admission->updated_at->diffForHumans() }}</div>
                        </td>

                        <td class="text-right">
                            <a href="{{route('accountant.billing.show', $admission->id)}}"
                                class="btn-enterprise-warning text-xs inline-flex items-center gap-1.5 py-1.5 px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                Process Bill
                            </a>
                            <a href="{{route('accountant.billing.bill', $admission->id)}}"
                                target="_blank"
                                class="btn-enterprise-info text-xs inline-flex items-center gap-1.5 py-1.5 px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                Print Bill
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-20 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>No patients pending for billing.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $readyForBilling->links() }}
        </div>
    </div>

</div>
@endsection
