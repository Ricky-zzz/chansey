@extends('layouts.clinic')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- PATIENT LIST -->
    <div class="bg-white rounded-lg p-6 shadow-xl border border-base-200">
        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-800">Station Roster</h2>
                <p class="text-sm text-slate-500 font-bold uppercase tracking-wider">
                    {{ $nurse->station->station_name ?? 'Unassigned Station' }}
                </p>
            </div>

            <!-- SEARCH -->
            <form action="{{ route('nurse.clinical.ward.index') }}" method="GET" class="join w-full md:w-96">
                <input type="text" name="search" class="input input-bordered join-item w-full"
                       placeholder="Find Patient or Bed..."
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary join-item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </button>
            </form>
        </div>

        <div class="border-t border-base-200 pt-6"></div>

        <div class="overflow-x-auto">
            <table class="table table-zebra table-lg">
                <!-- Head -->
                <thead class="bg-slate-800 text-white uppercase text-xs font-bold">
                    <tr>
                        <th class="w-32">Bed / Room</th>
                        <th>Patient Identity</th>
                        <th>Physician</th>
                        <th>Clinical Snapshot</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <!-- Body -->
                <tbody>
                    @forelse($patients as $admission)
                    <tr class="hover group">

                        <!-- 1. LOCATION -->
                        <td>
                            <div class="flex flex-col">
                                <span class="font-mono font-black text-lg text-blue-600">
                                    @if($admission->bed?->bed_code)
                                    {{ $admission->bed->bed_code }}
                                    @else
                                    <span class="italic text-gray-400">Outpatient</span>
                                    @endif
                                </span>
                                <span class="text-xs text-gray-500 font-bold">
                                    {{ $admission->bed?->room->room_type ?? 'Waiting Area' }}
                                </span>
                            </div>
                        </td>

                        <!-- 2. PATIENT -->
                        <td>
                            <div class="font-bold text-md text-slate-700">
                                {{ $admission->patient->getFullNameAttribute() }}
                            </div>
                            <div class="font-bold text-xs text-sky-600">
                                {{ $admission->patient->patient_unique_id }}
                            </div>
                            <div class="text-xs text-gray-500 font-medium">
                                {{ $admission->patient->sex }} •
                                {{ $admission->patient->getAgeAttribute() }} yrs old
                            </div>
                        </td>

                        <!-- 3. DOCTOR -->
                        <td>
                            <div class="font-semibold text-sm">
                                Dr. {{ $admission->attendingPhysician->getFullNameAttribute() }}
                            </div>
                            <div class="text-xs text-gray-500 italic">
                                {{ $admission->attendingPhysician->specialization }}
                            </div>
                        </td>

                        <!-- 4. SNAPSHOT -->
                        <td class="max-w-xs">
                            <!-- Show Tags if Emergency -->
                            @if($admission->admission_type === 'Emergency')
                                <span class="badge badge-error badge-xs text-white mb-1">Emergency</span>
                            @endif

                            <div class="text-sm font-medium text-slate-600 truncate">
                                {{ $admission->initial_diagnosis ?? 'Pending Diagnosis' }}
                            </div>
                            <div class="text-xs text-gray-400 italic truncate">
                                "{{ $admission->truncatedChiefComplaint() }}"
                            </div>
                        </td>

                        <!-- 5. ACTION -->
                        <td class="text-right">
                            <a href="{{ route('nurse.clinical.ward.show', $admission->id) }}"
                               class="btn btn-primary btn-sm gap-2 shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                Open Chart
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12">
                            <div class="flex flex-col items-center opacity-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                <span class="text-gray-400 font-bold text-lg">No active patients found.</span>
                                <span class="text-gray-400 text-sm">Your station is currently empty.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="p-4 border-t border-base-200">
            {{ $patients->links() }}
        </div>
    </div>
</div>
@endsection
