@extends('layouts.accountant')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="bg-white border border-base-300 rounded-xl p-6 shadow">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Billing History</h2>
                <p class="text-sm text-gray-500">All cleared and discharged patient transactions.</p>
            </div>

            <!-- Simple Search -->
            <form action="{{ route('accountant.history') }}" method="GET" class="join">
                <input type="text" name="search" class="input input-bordered join-item input-sm"
                    placeholder="Search Patient..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary join-item btn-sm">Search</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead class="bg-slate-100 text-slate-600 font-bold uppercase text-xs">
                    <tr>
                        <th>Receipt #</th>
                        <th>Patient</th>
                        <th>Admission #</th>
                        <th>Payment Date</th>
                        <th class="text-right">Gross Amount</th>
                        <th class="text-right">Final Amount</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($billings as $billing)
                    <tr>
                        <td class="font-mono font-bold text-primary">{{ $billing->receipt_number }}</td>

                        <td>
                            <div class="font-bold">{{ $billing->admission->patient->last_name }}, {{ $billing->admission->patient->first_name }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $billing->admission->admission_number }}
                            </div>
                        </td>

                        <td class="font-mono text-sm">{{ $billing->admission->admission_number }}</td>

                        <td>
                            <div class="text-sm font-bold">{{ $billing->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $billing->created_at->diffForHumans() }}</div>
                        </td>

                        <td class="text-right font-mono font-bold">
                            <span class="text-slate-800">P{{ number_format($billing->gross_total, 2) }}</span>
                        </td>

                        <td class="text-right font-mono font-bold">
                            <span class="text-green-600">P{{ number_format($billing->final_total, 2) }}</span>
                        </td>

                        <td class="text-right">
                            <a href="{{route('accountant.billing.show', $billing->admission->id)}}"
                                class="btn btn-sm bg-blue-500 text-white gap-2 border border-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View Receipt
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-20 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>No billing records found.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $billings->links() }}
        </div>
    </div>

</div>
@endsection