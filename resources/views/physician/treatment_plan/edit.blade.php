@extends('layouts.physician')

@section('content')
<div class="max-w-4xl mx-auto" x-data="{ mode: 'view' }">

    <!-- BREADCRUMBS -->
    <div class="text-sm breadcrumbs mb-4">
        <ul>
            <li><a href="{{ route('physician.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('physician.mypatients.index') }}">My Patients</a></li>
            <li><a href="{{ route('physician.mypatients.show', $admission->id) }}">{{ $admission->patient->last_name }}</a></li>
            <li class="font-bold text-primary">Treatment Plan</li>
        </ul>
    </div>

    <!-- HEADER -->
    <div class="card bg-base-100 shadow-xl border border-base-200 mb-6">
        <div class="card-body p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold text-slate-800">Treatment Plan</h2>
                    <p class="text-sm text-gray-700 mt-1">
                        Patient: {{ $admission->patient->last_name }}, {{ $admission->patient->first_name }}
                        <span class="badge badge-neutral badge-sm ml-2">{{ $admission->bed->bed_code ?? 'Outpatient' }}</span>
                    </p>
                </div>
                <div class="flex gap-2 items-center">
                    <!-- TOGGLE BUTTONS -->
                    <div class="join border border-slate-300 rounded-lg">
                        <button @click="mode = 'view'" :class="mode === 'view' ? 'btn-active' : ''" class="btn btn-sm join-item">View</button>
                        <button @click="mode = 'edit'" :class="mode === 'edit' ? 'btn-active' : ''" class="btn btn-sm join-item">Edit</button>
                    </div>
                    <a href="{{ route('physician.mypatients.show', $admission->id) }}" class="btn btn-outline btn-error gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Chart
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== VIEW MODE ========== -->
    <template x-if="mode === 'view'">
        <div>
            <!-- DIAGNOSIS -->
            <div class="card bg-base-100 shadow-xl border border-base-200 mb-6">
                <div class="card-body p-6">
                    <h3 class="card-title text-primary border-b pb-2 mb-4">Diagnosis & Problem</h3>
                    <p class="text-gray-800 text-base leading-relaxed">{{ $plan->main_problem }}</p>
                </div>
            </div>

            <!-- GOALS & INTERVENTIONS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- GOALS -->
                <div class="card bg-base-100 shadow-xl border border-base-200">
                    <div class="card-body p-6">
                        <h3 class="card-title text-emerald-700 text-lg font-bold">Goals of Care</h3>
                        <ul class="space-y-2 mt-4">
                            @forelse($plan->goals ?? [] as $goal)
                            <li class="flex gap-2 text-gray-800">
                                <span class="text-emerald-600 font-bold">✓</span>
                                <span>{{ $goal }}</span>
                            </li>
                            @empty
                            <li class="text-gray-500 italic">No goals defined</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- INTERVENTIONS -->
                <div class="card bg-base-100 shadow-xl border border-base-200">
                    <div class="card-body p-6">
                        <h3 class="card-title text-blue-700 text-lg font-bold">Planned Interventions</h3>
                        <ul class="space-y-2 mt-4">
                            @forelse($plan->interventions ?? [] as $intervention)
                            <li class="flex gap-2 text-gray-800">
                                <span class="text-blue-600 font-bold">→</span>
                                <span>{{ $intervention }}</span>
                            </li>
                            @empty
                            <li class="text-gray-500 italic">No interventions defined</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- OUTCOMES & EVALUATION -->
            <div class="card bg-base-100 shadow-xl border border-base-200 mb-8">
                <div class="card-body p-6">
                    <h3 class="card-title text-slate-800 border-b pb-2 mb-4">Evaluation</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="label font-bold text-gray-800 mb-2">Expected Outcome</label>
                            <p class="text-gray-700 bg-gray-50 p-4 rounded">{{ $plan->expected_outcome ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <label class="label font-bold text-gray-800 mb-2">Physician's Evaluation / Progress</label>
                            <p class="text-gray-700 bg-gray-50 p-4 rounded">{{ $plan->evaluation ?? 'Not specified' }}</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="label font-bold text-gray-800 mb-2">Plan Status</label>
                        <div class="badge badge-lg"
                            :class="'{{ $plan->status }}' === 'Active' ? 'badge-success' :
                                     '{{ $plan->status }}' === 'Revised' ? 'badge-warning' : 'badge-info'">
                            {{ $plan->status }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- ========== EDIT MODE ========== -->
    <template x-if="mode === 'edit'">
        <form action="{{ route('physician.treatment-plan.update', $admission->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- 1. DIAGNOSIS -->
            <div class="card bg-base-100 shadow-xl border border-base-200 mb-6">
                <div class="card-body p-6">
                    <h3 class="card-title text-primary border-b pb-2 mb-4">Diagnosis & Problem</h3>
                    <div class="form-control w-full">
                        <textarea name="main_problem" class="textarea textarea-bordered h-20 w-full" placeholder="e.g. Acute Pneumonia with difficulty breathing..." required>{{ old('main_problem', $plan->main_problem) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- 2. GOALS & INTERVENTIONS (Alpine Powered) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                
                <!-- GOALS LIST -->
                <div class="card bg-base-100 shadow-xl border border-base-200"
                     x-data="{ 
                        items: {{ Js::from($plan->goals ?? []) }}, 
                        newInput: '',
                        add() {
                            if(this.newInput.trim()) { this.items.push(this.newInput); this.newInput = ''; }
                        },
                        remove(index) { this.items.splice(index, 1); }
                     }">
                    <div class="card-body p-6">
                        <h3 class="card-title text-emerald-700 text-lg font-bold">Goals of Care</h3>
                        <p class="text-sm text-gray-600 mb-2">What do we want to achieve?</p>
                        
                        <div class="join w-full mb-4">
                            <input type="text" x-model="newInput" @keydown.enter.prevent="add()" class="input input-bordered input-sm w-full join-item" placeholder="Add goal (e.g. Stabilize BP)...">
                            <button type="button" @click="add()" class="btn btn-sm bg-emerald-600 text-white join-item">+</button>
                        </div>

                        <ul class="space-y-2">
                            <template x-for="(item, index) in items" :key="index">
                                <li class="flex justify-between bg-emerald-50 p-2 rounded border border-emerald-100">
                                    <span x-text="item" class="text-sm text-gray-800"></span>
                                    <input type="hidden" name="goals[]" :value="item">
                                    <button type="button" @click="remove(index)" class="text-error font-bold px-2">x</button>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>

                <!-- INTERVENTIONS LIST -->
                <div class="card bg-base-100 shadow-xl border border-base-200"
                     x-data="{ 
                        items: {{ Js::from($plan->interventions ?? []) }}, 
                        newInput: '',
                        add() {
                            if(this.newInput.trim()) { this.items.push(this.newInput); this.newInput = ''; }
                        },
                        remove(index) { this.items.splice(index, 1); }
                     }">
                    <div class="card-body p-6">
                        <h3 class="card-title text-blue-700 text-lg font-bold">Planned Interventions</h3>
                        <p class="text-sm text-gray-600 mb-2">What will we do?</p>
                        
                        <div class="join w-full mb-4">
                            <input type="text" x-model="newInput" @keydown.enter.prevent="add()" class="input input-bordered input-sm w-full join-item" placeholder="Add intervention (e.g. Daily Nebulization)...">
                            <button type="button" @click="add()" class="btn btn-sm bg-sky-500 text-white join-item">+</button>
                        </div>

                        <ul class="space-y-2">
                            <template x-for="(item, index) in items" :key="index">
                                <li class="flex justify-between bg-blue-50 p-2 rounded border border-blue-100">
                                    <span x-text="item" class="text-sm text-gray-800"></span>
                                    <input type="hidden" name="interventions[]" :value="item">
                                    <button type="button" @click="remove(index)" class="text-error font-bold px-2">x</button>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 3. OUTCOMES & EVALUATION -->
            <div class="card bg-base-100 shadow-xl border border-base-200 mb-8">
                <div class="card-body p-6">
                    <h3 class="card-title text-slate-800 border-b pb-2 mb-4">Evaluation</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control w-full">
                            <label class="label font-bold text-gray-800">Expected Outcome</label>
                            <textarea name="expected_outcome" class="textarea textarea-bordered h-24 w-full">{{ old('expected_outcome', $plan->expected_outcome) }}</textarea>
                        </div>
                        <div class="form-control w-full">
                            <label class="label font-bold text-gray-800">Physician's Evaluation / Progress</label>
                            <textarea name="evaluation" class="textarea textarea-bordered h-24 w-full" placeholder="Patient responded well...">{{ old('evaluation', $plan->evaluation) }}</textarea>
                        </div>
                    </div>

                    <div class="form-control mt-4">
                        <label class="label font-bold text-gray-800">Plan Status</label>
                        <select name="status" class="select select-bordered w-full max-w-xs">
                            <option value="Active" {{ $plan->status == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Revised" {{ $plan->status == 'Revised' ? 'selected' : '' }}>Revised</option>
                            <option value="Resolved" {{ $plan->status == 'Resolved' ? 'selected' : '' }}>Resolved / Goal Met</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- SUBMIT -->
            <div class="flex justify-end pb-12">
                <button type="submit" class="btn btn-primary btn-lg shadow-lg">
                    Save Treatment Plan
                </button>
            </div>

        </form>
    </template>
</div>
@endsection