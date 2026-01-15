@extends('layouts.layout')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ selectedApp: null }">
    <h2 class="text-3xl font-bold text-neutral mb-6">Appointment Manager</h2>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <div>
            <h3 class="font-bold text-lg mb-4 flex justify-between">
                <span>Pending Requests</span>
                <span class="badge badge-warning">{{ $pending->count() }}</span>
            </h3>

            <div class="space-y-4">
                @forelse($pending as $req)
                <div class="card bg-white border border-l-4 border-l-warning shadow-sm">
                    <div class="card-body p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-slate-800">{{ $req->last_name }}, {{ $req->first_name }}</h4>
                                <div class="text-xs text-gray-500">{{ $req->contact_number }}</div>
                                <div class="badge badge-outline badge-sm mt-1">{{ $req->department->name }}</div>
                            </div>
                            <div class="flex gap-1">
                                <!-- CANCEL BUTTON -->
                                <form action="{{ route('nurse.admitting.appointments.cancel', $req->id) }}" method="POST" onsubmit="return confirm('Reject this request?')">
                                    @csrf
                                    <button class="btn btn-square bg-rose-500 text-white btn-xs ">✕</button>
                                </form>
                                <!-- APPROVE BUTTON (Opens Modal) -->
                                <button @click="selectedApp = {{ $req->id }}; document.getElementById('sched_modal').showModal()" 
                                        class="btn btn-sm bg-blue-600 text-white ">Schedule</button>
                            </div>
                        </div>
                        <div class="bg-slate-50 p-2 rounded mt-2 text-xs italic text-gray-600">
                            "{{ $req->purpose }}"
                        </div>
                    </div>
                </div>
                @empty
                    <div class="text-center py-10 text-gray-400 italic">No pending requests.</div>
                @endforelse
            </div>
            <div class="mt-4">
                {{ $pending->links() }}
            </div>
        </div>

        <!-- COLUMN 2: UPCOMING SCHEDULE -->
        <div>
            <h3 class="font-bold text-lg mb-4">Scheduled Appointments for Today</h3>
            <div class="card bg-base-100 border shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-sm">
                        <thead class="bg-base-200">
                            <tr>
                                <th>Time</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Check-In</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcoming as $app)
                            <tr class="hover">
                                <td class="font-mono text-xs">
                                    <div class="font-bold">{{ $app->scheduled_at->format('M d') }}</div>
                                    {{ $app->scheduled_at->format('h:i A') }}
                                </td>
                                <td>{{ $app->last_name }}</td>
                                <td class="text-xs text-gray-500">Dr. {{ $app->physician->last_name }}</td>
                                <td>
                                    <!-- Shortcut to Admit -->
                                    <a href="{{ route('nurse.admitting.patients.create', ['prefill' => $app->id]) }}" 
                                       class="btn btn-xs btn-success text-white">
                                        Admit
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center p-4 text-gray-400">No appoinments for today.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4">
                {{ $upcoming->links() }}
            </div>
        </div>
    </div>

    <!-- SCHEDULING MODAL -->
    <dialog id="sched_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Schedule Appointment</h3>
            
            <form id="schedForm" method="POST" @submit.prevent="submitForm">
                @csrf
                
                <div class="form-control mb-4">
                    <label class="label font-bold">Assign Physician</label>
                    <select name="physician_id" class="select select-bordered w-full" required>
                        <option value="" disabled selected>Select Doctor</option>
                        @foreach($physicians as $doc)
                            <option value="{{ $doc->id }}">
                                Dr. {{ $doc->last_name }} - {{ $doc->department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="form-control">
                        <label class="label font-bold">Date</label>
                        <input type="date" name="scheduled_date" class="input input-bordered" required min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-control">
                        <label class="label font-bold">Time</label>
                        <select name="scheduled_time" class="select select-bordered" required>
                            @foreach(['08:00','08:30','09:00','09:30','10:00','10:30','11:00','13:00','13:30','14:00','14:30','15:00','15:30','16:00'] as $time)
                                <option value="{{ $time }}">{{ $time }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-action">
                    <button type="button" class="btn" onclick="sched_modal.close()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm & Notify</button>
                </div>
            </form>
        </div>
    </dialog>

</div>

<script>
    function submitForm(event) {
        const form = document.getElementById('schedForm');
        const selectedApp = document.querySelector('[x-data]')?._x_dataStack?.[0]?.selectedApp;
        if(selectedApp) {
            form.action = "/nurse/admitting/appointments/" + selectedApp + "/approve";
            form.submit();
        }
    }
    
    document.getElementById('schedForm').addEventListener('submit', function(e) {
        e.preventDefault();
        submitForm(e);
    });
</script>
@endsection