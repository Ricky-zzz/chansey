@extends('layouts.clinic')

@section('content')
<div class="max-w-7xl mx-auto" x-data="patientLoadCards()">

    <!-- PATIENT LOAD LIST -->
    <div class="card-enterprise p-6">
        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">My Patient Load</h2>
                <p class="text-sm text-slate-500 font-medium mt-0.5">
                    Patients assigned to you
                </p>
            </div>
        </div>

        <div class="border-t border-slate-200 pt-5"></div>

        @if($patientLoads->isEmpty())
        <!-- Empty State -->
        <div class="text-center py-16">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-14 w-14 text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            <p class="text-slate-400 font-medium mb-1">No patients assigned yet</p>
            <p class="text-slate-400 text-sm">Your assigned patients will appear here</p>
        </div>
        @else
        <!-- Patient Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($patientLoads as $load)
            <div class="border border-slate-200 rounded-lg p-4 hover:shadow-md transition-shadow bg-white">
                <!-- Acuity Badge -->
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">{{ $load['patient_name'] }}</h3>
                        <p class="text-xs text-slate-500 font-mono">{{ $load['patient_unique_id'] }}</p>
                    </div>
                    <span class="badge-enterprise text-white text-xs px-2 py-0.5"
                        :style="'background-color: ' + getAcuityColor('{{ $load['acuity'] }}')"
                        title="Acuity Level">
                        {{ $load['acuity'] }}
                    </span>
                </div>

                <!-- Patient Info -->
                <div class="space-y-1 mb-3 text-xs text-slate-600">
                    <p><span class="font-semibold">Age:</span> {{ $load['age'] }} yrs old</p>
                    <p><span class="font-semibold">Sex:</span> {{ $load['sex'] }}</p>
                    <p><span class="font-semibold">Score:</span> {{ $load['score'] }}</p>
                </div>

                <!-- Notes -->
                @if($load['description'])
                <div class="mb-3 p-2 bg-slate-50 rounded text-xs text-slate-600 italic border-l-2 border-slate-300">
                    {{ Str::limit($load['description'], 80) }}
                </div>
                @endif

                <!-- Actions -->
                <div class="flex gap-2">
                    <button @click="viewDetails({{ json_encode($load) }})"
                        class="flex-1 btn-enterprise-secondary text-xs px-2 py-1.5 inline-flex items-center justify-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.658 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                        Details
                    </button>
                    <a href="{{ route('nurse.clinical.ward.show', ['id' => $load['admission_id']]) }}"
                        class="flex-1 btn-enterprise-primary text-xs px-2 py-1.5 inline-flex items-center justify-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        Chart
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- DETAILS MODAL -->
    <div x-show="detailsOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
        <div @click.away="detailsOpen = false" class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
            <!-- Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-bold text-slate-800">Assignment Details</h3>
                <button @click="detailsOpen = false" class="text-slate-400 hover:text-slate-600 p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-xs font-semibold text-slate-500 mb-1">PATIENT</p>
                    <p class="text-sm font-bold text-slate-800" x-text="selectedLoad?.patient_name"></p>
                    <p class="text-xs text-slate-500 font-mono" x-text="selectedLoad?.patient_unique_id"></p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 mb-1">ACUITY</p>
                        <p class="badge-enterprise text-white text-xs w-fit px-2 py-1"
                            :style="'background-color: ' + getAcuityColor(selectedLoad?.acuity)"
                            x-text="selectedLoad?.acuity"></p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 mb-1">SCORE</p>
                        <p class="text-sm font-bold text-slate-800" x-text="selectedLoad?.score"></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 mb-1">AGE</p>
                        <p class="text-sm font-semibold text-slate-700" x-text="selectedLoad?.age + ' years'"></p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 mb-1">SEX</p>
                        <p class="text-sm font-semibold text-slate-700" x-text="selectedLoad?.sex"></p>
                    </div>
                </div>

                <div x-show="selectedLoad?.description">
                    <p class="text-xs font-semibold text-slate-500 mb-1">NOTES</p>
                    <p class="text-sm text-slate-700 p-3 bg-slate-50 rounded border border-slate-200 italic" x-text="selectedLoad?.description"></p>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-3 bg-slate-50 border-t border-slate-200 flex gap-2 rounded-b-xl">
                <button @click="detailsOpen = false" class="btn-enterprise-secondary flex-1 text-xs">Close</button>
                <a :href="'/nurse/clinical/patient/' + selectedLoad?.admission_id"
                    class="btn-enterprise-primary flex-1 text-xs inline-flex items-center justify-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    Open Chart
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function patientLoadCards() {
    return {
        detailsOpen: false,
        selectedLoad: null,

        viewDetails(load) {
            this.selectedLoad = load;
            this.detailsOpen = true;
        },

        getAcuityColor(acuity) {
            const colors = {
                'Severe': '#dc2626',
                'High': '#f59e0b',
                'Moderate': '#0ea5e9',
                'Low': '#10b981',
            };
            return colors[acuity] || '#6b7280';
        }
    };
}
</script>
@endsection
