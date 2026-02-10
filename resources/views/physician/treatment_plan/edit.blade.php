@extends('layouts.physician')

@section('content')
<div class="max-w-4xl mx-auto" x-data="{ mode: 'view' }">

    <!-- BREADCRUMBS -->
    <div class="flex items-center gap-2 text-sm mb-4">
        <a href="{{ route('physician.dashboard') }}" class="text-slate-500 hover:text-emerald-600 transition-colors">Dashboard</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <a href="{{ route('physician.mypatients.index') }}" class="text-slate-500 hover:text-emerald-600 transition-colors">My Patients</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <a href="{{ route('physician.mypatients.show', $admission->id) }}" class="text-slate-500 hover:text-emerald-600 transition-colors">{{ $admission->patient->last_name }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <span class="font-semibold text-emerald-600">Treatment Plan</span>
    </div>

    <!-- HEADER -->
    <div class="card-enterprise mb-6">
        <div class="p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Treatment Plan</h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Patient: {{ $admission->patient->last_name }}, {{ $admission->patient->first_name }}
                        <span class="badge-enterprise bg-slate-100 text-slate-600 text-xs ml-2">{{ $admission->bed->bed_code ?? 'Outpatient' }}</span>
                    </p>
                </div>
                <div class="flex gap-2 items-center">
                    <!-- TOGGLE BUTTONS -->
                    <div class="flex border border-slate-200 rounded-lg overflow-hidden">
                        <button @click="mode = 'view'" :class="mode === 'view' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50'" class="px-3 py-1.5 text-sm font-medium transition-colors">View</button>
                        <button @click="mode = 'edit'" :class="mode === 'edit' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-50'" class="px-3 py-1.5 text-sm font-medium transition-colors">Edit</button>
                    </div>
                    <a href="{{ route('physician.mypatients.show', $admission->id) }}" class="btn-enterprise-secondary gap-2">
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
            <div class="card-enterprise mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-emerald-700 border-b border-slate-200 pb-2 mb-4">Diagnosis & Problem</h3>
                    <p class="text-slate-700 text-base leading-relaxed">{{ $plan->main_problem }}</p>
                </div>
            </div>

            <!-- GOALS & INTERVENTIONS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- GOALS -->
                <div class="card-enterprise">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-emerald-700">Goals of Care</h3>
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
                <div class="card-enterprise">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-blue-700">Planned Interventions</h3>
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
            <div class="card-enterprise mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-slate-800 border-b border-slate-200 pb-2 mb-4">Evaluation</h3>

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
                        <div class="badge-enterprise text-sm"
                            :class="'{{ $plan->status }}' === 'Active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' :
                                     '{{ $plan->status }}' === 'Revised' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-blue-50 text-blue-700 border border-blue-200'">
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
            <div class="card-enterprise mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-emerald-700 border-b border-slate-200 pb-2 mb-4">Diagnosis & Problem</h3>
                    <div class="form-control w-full">
                        <textarea name="main_problem" class="textarea-enterprise h-20 w-full" placeholder="e.g. Acute Pneumonia with difficulty breathing..." required>{{ old('main_problem', $plan->main_problem) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- 2. GOALS & INTERVENTIONS (Alpine Powered) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                <!-- GOALS LIST -->
                <div class="card-enterprise"
                     x-data="{
                        items: {{ Js::from($plan->goals ?? []) }},
                        newInput: '',
                        add() {
                            if(this.newInput.trim()) { this.items.push(this.newInput); this.newInput = ''; }
                        },
                        remove(index) { this.items.splice(index, 1); }
                     }">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-emerald-700">Goals of Care</h3>
                        <p class="text-sm text-slate-500 mb-2">What do we want to achieve?</p>

                        <div class="flex w-full mb-4">
                            <input type="text" x-model="newInput" @keydown.enter.prevent="add()" class="input-enterprise text-sm w-full rounded-r-none" placeholder="Add goal (e.g. Stabilize BP)...">
                            <button type="button" @click="add()" class="btn-enterprise-primary rounded-l-none text-sm">+</button>
                        </div>

                        <ul class="space-y-2">
                            <template x-for="(item, index) in items" :key="index">
                                <li class="flex justify-between bg-emerald-50 p-2 rounded border border-emerald-100">
                                    <span x-text="item" class="text-sm text-gray-800"></span>
                                    <input type="hidden" name="goals[]" :value="item">
                                    <button type="button" @click="remove(index)" class="text-red-600 font-bold px-2">x</button>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>

                <!-- INTERVENTIONS LIST -->
                <div class="card-enterprise"
                     x-data="{
                        items: {{ Js::from($plan->interventions ?? []) }},
                        newInput: '',
                        add() {
                            if(this.newInput.trim()) { this.items.push(this.newInput); this.newInput = ''; }
                        },
                        remove(index) { this.items.splice(index, 1); }
                     }">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-blue-700">Planned Interventions</h3>
                        <p class="text-sm text-slate-500 mb-2">What will we do?</p>

                        <div class="flex w-full mb-4">
                            <input type="text" x-model="newInput" @keydown.enter.prevent="add()" class="input-enterprise text-sm w-full rounded-r-none" placeholder="Add intervention (e.g. Daily Nebulization)...">
                            <button type="button" @click="add()" class="btn-enterprise-info rounded-l-none text-sm">+</button>
                        </div>

                        <ul class="space-y-2">
                            <template x-for="(item, index) in items" :key="index">
                                <li class="flex justify-between bg-blue-50 p-2 rounded border border-blue-100">
                                    <span x-text="item" class="text-sm text-gray-800"></span>
                                    <input type="hidden" name="interventions[]" :value="item">
                                    <button type="button" @click="remove(index)" class="text-red-600 font-bold px-2">x</button>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- 3. OUTCOMES & EVALUATION -->
            <div class="card-enterprise mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-slate-800 border-b border-slate-200 pb-2 mb-4">Evaluation</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control w-full">
                            <label class="label font-bold text-gray-800">Expected Outcome</label>
                            <textarea name="expected_outcome" class="textarea-enterprise h-24 w-full">{{ old('expected_outcome', $plan->expected_outcome) }}</textarea>
                        </div>
                        <div class="form-control w-full">
                            <label class="label font-bold text-gray-800">Physician's Evaluation / Progress</label>
                            <textarea name="evaluation" class="textarea-enterprise h-24 w-full" placeholder="Patient responded well...">{{ old('evaluation', $plan->evaluation) }}</textarea>
                        </div>
                    </div>

                    <div class="form-control mt-4">
                        <label class="label font-bold text-gray-800">Plan Status</label>
                        <select name="status" class="select-enterprise w-full max-w-xs">
                            <option value="Active" {{ $plan->status == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Revised" {{ $plan->status == 'Revised' ? 'selected' : '' }}>Revised</option>
                            <option value="Resolved" {{ $plan->status == 'Resolved' ? 'selected' : '' }}>Resolved / Goal Met</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- SUBMIT -->
            <div class="flex justify-end pb-12">
                <button type="submit" class="btn-enterprise-primary text-base px-8 py-3">
                    Save Treatment Plan
                </button>
            </div>

        </form>
    </template>
</div>
@endsection
