@extends('layouts.clinic')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="card-enterprise p-5 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $title }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">Recruit floating nurses to your station or release them back to the pool</p>
            </div>
            <div class="flex gap-3">
                <div class="stat-card flex items-center gap-3">
                    <div class="bg-emerald-100 p-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-medium">Available</div>
                        <div class="text-xl font-bold text-slate-800">{{ $availableFloaters->total() }}</div>
                    </div>
                </div>
                <div class="stat-card flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-medium">My Recruits</div>
                        <div class="text-xl font-bold text-slate-800">{{ $myRecruitedFloaters->total() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
    <div class="alert bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg shadow-sm mb-6 p-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert bg-red-50 border border-red-200 text-red-800 rounded-lg shadow-sm mb-6 p-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- Tabs --}}
    <div class="card-enterprise overflow-hidden">
        <div role="tablist" class="tabs tabs-lifted">
            {{-- Available Pool Tab --}}
            <input type="radio" name="floater_tabs" role="tab" class="tab" aria-label="Available Pool" checked />
            <div role="tabpanel" class="tab-content bg-white p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Available Floating Nurses</h3>
                <p class="text-sm text-slate-500 mb-6">These nurses are not currently assigned to any station and are available for recruitment.</p>

                @if($availableFloaters->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table-enterprise">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Employee ID</th>
                                <th>License Number</th>
                                <th>Contact Number</th>
                                <th>Hired Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($availableFloaters as $floater)
                            <tr>
                                <td>
                                    <div class="font-semibold text-slate-800">{{ $floater->first_name }} {{ $floater->last_name }}</div>
                                    <div class="text-xs text-slate-500">{{ $floater->user->email }}</div>
                                </td>
                                <td>
                                    <span class="font-mono text-sm text-slate-700">{{ $floater->employee_id }}</span>
                                </td>
                                <td>
                                    <span class="text-sm text-slate-700">{{ $floater->license_number }}</span>
                                </td>
                                <td>
                                    <span class="text-sm text-slate-700">{{ $floater->contact_number ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="text-sm text-slate-700">{{ $floater->date_hired->format('M d, Y') }}</span>
                                </td>
                                <td>
                                    <form action="{{ route('nurse.headnurse.floaters.recruit', $floater->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-enterprise-primary btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                            Recruit
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $availableFloaters->links() }}
                </div>
                @else
                <div class="text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    <h3 class="text-lg font-semibold text-slate-600 mb-1">No Available Floating Nurses</h3>
                    <p class="text-sm text-slate-500">All floating nurses are currently assigned to stations.</p>
                </div>
                @endif
            </div>

            {{-- My Recruits Tab --}}
            <input type="radio" name="floater_tabs" role="tab" class="tab" aria-label="My Recruits" />
            <div role="tabpanel" class="tab-content bg-white p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">My Recruited Floating Nurses</h3>
                <p class="text-sm text-slate-500 mb-6">These nurses are currently assigned to your station.</p>

                @if($myRecruitedFloaters->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table-enterprise">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Employee ID</th>
                                <th>License Number</th>
                                <th>Contact Number</th>
                                <th>Shift Schedule</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($myRecruitedFloaters as $floater)
                            <tr>
                                <td>
                                    <div class="font-semibold text-slate-800">{{ $floater->first_name }} {{ $floater->last_name }}</div>
                                    <div class="text-xs text-slate-500">{{ $floater->user->email }}</div>
                                </td>
                                <td>
                                    <span class="font-mono text-sm text-slate-700">{{ $floater->employee_id }}</span>
                                </td>
                                <td>
                                    <span class="text-sm text-slate-700">{{ $floater->license_number }}</span>
                                </td>
                                <td>
                                    <span class="text-sm text-slate-700">{{ $floater->contact_number ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    @if($floater->shiftSchedule)
                                        <span class="badge-enterprise bg-blue-50 text-blue-700 border border-blue-200">
                                            {{ $floater->shiftSchedule->name }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs italic">No schedule</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('nurse.headnurse.floaters.release', $floater->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to release this nurse back to the pool? Their shift schedule will be reset.');">
                                        @csrf
                                        <button type="submit" class="btn-enterprise-danger btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                            Release
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $myRecruitedFloaters->links() }}
                </div>
                @else
                <div class="text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                    <h3 class="text-lg font-semibold text-slate-600 mb-1">No Recruited Nurses</h3>
                    <p class="text-sm text-slate-500">You haven't recruited any floating nurses to your station yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
