@extends('layouts.layout')

@section('content')
<div class="card-enterprise p-6">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Today's Appointments</h2>
            <p class="text-sm text-slate-500">{{ now()->format('l, F d, Y') }} • {{ $todayAppointments->count() }} {{ Str::plural('patient', $todayAppointments->count()) }} expected</p>
        </div>
    </div>

    {{-- Appointments Table --}}
    <div class="overflow-x-auto">
            <table class="table-enterprise">
                <thead>
                    <tr>
                        <th class="w-24">Time</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Department</th>
                        <th>Reason</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($todayAppointments as $app)
                    <tr class="hover">
                        {{-- TIME --}}
                        <td class="font-mono font-bold text-emerald-700">
                            {{ \Carbon\Carbon::parse($app->appointmentSlot->start_time)->format('h:i A') }}
                        </td>

                        {{-- PATIENT --}}
                        <td>
                            <div class="font-bold">{{ $app->last_name }}, {{ $app->first_name }}</div>
                            <div class="text-xs text-gray-400">{{ $app->contact_number }}</div>
                        </td>

                        {{-- DOCTOR --}}
                        <td>
                            <div class="font-semibold text-slate-700">
                                Dr. {{ $app->appointmentSlot->physician->last_name }}
                            </div>
                        </td>

                        {{-- DEPARTMENT --}}
                        <td>
                            <span class="badge-enterprise bg-slate-100 text-slate-600 text-xs">
                                {{ $app->appointmentSlot->physician->department->name }}
                            </span>
                        </td>

                        {{-- REASON --}}
                        <td class="max-w-xs">
                            <div class="text-sm italic text-gray-600 truncate" title="{{ $app->purpose }}">
                                "{{ Str::limit($app->purpose, 40) }}"
                            </div>
                        </td>

                        {{-- ACTION --}}
                        <td class="text-right">
                            <a href="{{ route('nurse.admitting.patients.create', ['prefill' => $app->id]) }}"
                               class="btn-enterprise-primary text-xs gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                Admit
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-gray-400 italic">
                            <div class="flex flex-col items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p>No appointments scheduled for today.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
