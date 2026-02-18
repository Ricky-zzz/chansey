@extends('layouts.clinic')

@section('content')
<div class="max-w-full mx-auto" x-data="clinicalChart()" data-stations='@json($stations)' data-beds='@json($transferBeds)'>

    <!-- BREADCRUMB -->
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-4">
        <a href="{{ route('nurse.clinical.ward.index') }}" class="hover:text-emerald-600 transition-colors">Ward</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-700 font-medium">{{ $admission->patient->getFullNameAttribute() }}</span>
    </div>

    <!-- PATIENT HEADER -->
    @include('nurse.clinical.ward.components.patient-header')

    <!-- MAIN GRID: ORDERS + SNAPSHOTS/HISTORY -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- LEFT COLUMN: ACTIVE ORDERS -->
        @include('nurse.clinical.ward.components.active-orders')

        <!-- RIGHT COLUMN: SNAPSHOTS & HISTORY -->
        <div class="lg:col-span-2 space-y-6">
            <!-- VITALS & CARE PLAN SNAPSHOTS -->
            @include('nurse.clinical.ward.components.vitals-snapshot')

            <!-- MEDICAL HISTORY CARD -->
            <x-medical-history-card :admission="$admission" />

            <!-- CLINICAL LOG HISTORY -->
            <x-clinical-history-table :clinicalLogs="$clinicalLogs" displayMode="nurse" />
        </div>
    </div>

</div>

<!-- MODALS SECTION -->
@include('nurse.clinical.ward.components.modals.log-modal')
@include('nurse.clinical.ward.components.modals.lab-modal')
@include('nurse.clinical.ward.components.modals.transfer-modal')
@include('nurse.clinical.ward.components.modals.supply-modal')

<!-- EXISTING VIEW LOG MODAL (Keep if it's a custom component) -->
<x-clinical-log-modal />

<!-- ALPINE.JS SCRIPT -->
@push('scripts')
    @include('nurse.clinical.ward.components.clinical-chart-script')
@endpush

@endsection
