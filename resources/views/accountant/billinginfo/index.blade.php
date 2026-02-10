@extends('layouts.accountant')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="card-enterprise p-6">

        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Billing Information</h2>
            </div>

            <!-- SEARCH -->
            <form action="{{ route('accountant.billinginfo.index') }}" method="GET" class="flex w-full md:w-96">
                <input type="text" name="search" class="input-enterprise rounded-r-none w-full" placeholder="Search by admission # or patient name..." value="{{ request('search') }}">
                <button type="submit" class="btn-enterprise-primary rounded-l-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </button>
            </form>
        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="table-enterprise">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Admission #</th>
                        <th>Payment Type</th>
                        <th>Insurance Provider</th>
                        <th>Policy #</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($billingInfos as $billingInfo)
                    <tr class="hover">
                        <!-- Patient -->
                        <td>
                            <div class="font-bold text-md text-emerald-700">
                                {{ $billingInfo->admission->patient->getFullNameAttribute() }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $billingInfo->admission->patient->sex }} • {{ $billingInfo->admission->patient->age }} yo
                            </div>
                        </td>

                        <!-- Admission # -->
                        <td>
                            <span class="badge-enterprise bg-slate-100 text-slate-600 font-mono text-xs">
                                {{ $billingInfo->admission->admission_number }}
                            </span>
                        </td>

                        <!-- Payment Type -->
                        <td>
                            @php
                                $typeColors = [
                                    'Cash' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'Insurance' => 'bg-sky-50 text-sky-700 border-sky-200',
                                    'HMO' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'Government' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                ];
                                $badgeClass = $typeColors[$billingInfo->payment_type] ?? 'bg-slate-50 text-slate-600 border-slate-200';
                            @endphp
                            <span class="badge-enterprise {{ $badgeClass }} text-xs">{{ $billingInfo->payment_type ?? 'Not Set' }}</span>
                        </td>

                        <!-- Insurance Provider -->
                        <td>
                            <span class="text-sm text-gray-700">
                                {{ $billingInfo->primary_insurance_provider ?? '-' }}
                            </span>
                        </td>

                        <!-- Policy # -->
                        <td>
                            <span class="font-mono text-sm">
                                {{ $billingInfo->policy_number ?? '-' }}
                            </span>
                        </td>

                        <!-- Action -->
                        <td class="text-right">
                            <a href="{{ route('accountant.billinginfo.show', $billingInfo->admission->id) }}" class="btn-enterprise-primary text-xs inline-flex items-center gap-1.5 py-1.5 px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                View/Edit
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-400 italic">
                            @if(request('search'))
                                No billing information found for "{{ request('search') }}".
                            @else
                                Please search for a patient by admission number or name to view billing information.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        @if($billingInfos->count() > 0)
        <div class="mt-4">
            {{ $billingInfos->links() }}
        </div>
        @endif

    </div>
</div>
@endsection
