@extends('layouts.layout')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- OUTER CONTAINER WITH BORDER + WHITE BG -->
    <div class="card-enterprise p-6">

        <!-- HEADER & SEARCH -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h2 class="text-xl font-bold text-slate-800">Patient Master List</h2>

            <div class="flex gap-2 w-full md:w-auto">
                <!-- SEARCH FORM -->
                <form action="{{ route('nurse.admitting.patients.index') }}" method="GET" class="flex w-full md:w-96">
                    <input type="text" name="search" class="input-enterprise rounded-r-none w-full"
                        placeholder="Search PID or Name..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn-enterprise-primary rounded-l-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>

                <!-- ADD BUTTON -->
                <a href="{{ route('nurse.admitting.patients.create') }}" class="btn-enterprise-primary">
                    + New Patient
                </a>
            </div>
        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">
                <table class="table-enterprise">
                    <!-- Head -->
                    <thead>
                        <tr>
                            <th>Patient ID</th>
                            <th>Patient Name</th>
                            <th>Age / Sex</th>
                            <th>Contact</th>
                            <th>Date Registered</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <!-- Body -->
                    <tbody>
                        @forelse($patients as $patient)
                        <tr class="hover text-neutral">
                            <td class=" text-lg font-mono font-bold ">{{ $patient->patient_unique_id }}</td>

                            <td>
                                <div class="font-bold">{{ $patient->last_name }}, {{ $patient->first_name }}</div>
                                <div class="text-xs text-gray-500">{{ $patient->middle_name }}</div>
                            </td>

                            <td>
                                {{ $patient->age }} yo / {{ $patient->sex }}
                                <br>
                                <span class="text-xs text-gray-400">{{ $patient->date_of_birth->format('M d, Y') }}</span>
                            </td>

                            <td>{{ $patient->contact_number }}</td>
                            <td>{{ $patient->created_at->format('M d, Y') }}</td>

                            <td>
                                <a href="{{ route('nurse.admitting.patients.show', $patient->id) }}"
                                    class="btn-enterprise-primary text-xs gap-2"> <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>

                                    View Profile
                                </a>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                No patients found matching your search.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-200">
                {{ $patients->links() }}
            </div>

    </div>

</div>
@endsection
