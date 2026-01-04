@extends('layouts.clinic')

@section('content')
<div class="max-w-4xl mx-auto">

    <!-- BREADCRUMBS -->
    <div class="text-sm breadcrumbs mb-4">
        <ul>
            <li><a href="{{ route('nurse.clinical.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('nurse.clinical.ward.index') }}">My Ward</a></li>
            <li><a href="{{ route('nurse.clinical.ward.show', $admission->id) }}">{{ $admission->patient->last_name }}</a></li>
            <li class="font-bold text-primary">Care Plan</li>
        </ul>
    </div>

    <!-- HEADER -->
    <div class="card bg-base-100 shadow-xl border border-base-200 mb-6">
        <div class="card-body p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold text-slate-800">Nursing Care Plan (NCP)</h2>
                    <p class="text-sm text-gray-700 mt-1">
                        Patient: {{ $admission->patient->last_name }}, {{ $admission->patient->first_name }}
                        <span class="badge badge-neutral badge-sm ml-2">{{ $admission->bed->bed_code }}</span>
                    </p>
                </div>
                <a href="{{ route('nurse.clinical.ward.show', $admission->id) }}" class="btn btn-outline btn-error gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Chart
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('nurse.clinical.care-plan.update', $admission->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- 1. ASSESSMENT & DIAGNOSIS -->
        <div class="card bg-base-100 shadow-xl border border-base-200 mb-6">
            <div class="card-body p-6">
                <h3 class="card-title text-primary border-b pb-2 mb-4">Assessment & Diagnosis</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label font-bold text-gray-700">Assessment (Subjective/Objective)</label>
                        <textarea name="assessment" class="textarea textarea-bordered h-24" placeholder="e.g. Patient reports pain 8/10, guarding abdomen..." required>{{ old('assessment', $plan->assessment) }}</textarea>
                    </div>
                    <div class="form-control">
                        <label class="label font-bold text-gray-700">Nursing Diagnosis</label>
                        <textarea name="diagnosis" class="textarea textarea-bordered h-24" placeholder="e.g. Acute Pain related to surgical incision..." required>{{ old('diagnosis', $plan->diagnosis) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. PLANNING & INTERVENTIONS (Alpine) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            
            <!-- PLANNING (Goals) -->
            <div class="card bg-base-100 shadow-xl border border-base-200"
                 x-data="{ 
                    items: {{ Js::from($plan->planning ?? []) }}, 
                    newInput: '',
                    add() { if(this.newInput.trim()) { this.items.push(this.newInput); this.newInput = ''; } },
                    remove(index) { this.items.splice(index, 1); }
                 }">
                <div class="card-body p-6">
                    <h3 class="card-title text-emerald-700 text-lg font-bold">Planning / Goals</h3>
                    <p class="text-sm text-gray-600 mb-2">What do we want to achieve?</p>
                    
                    <div class="join w-full mb-4">
                        <input type="text" x-model="newInput" @keydown.enter.prevent="add()" class="input input-bordered input-sm w-full join-item" placeholder="Add goal...">
                        <button type="button" @click="add()" class="btn btn-sm bg-emerald-600 text-white join-item">+</button>
                    </div>

                    <ul class="space-y-2">
                        <template x-for="(item, index) in items" :key="index">
                            <li class="flex justify-between bg-emerald-50 p-2 rounded border border-emerald-100 text-sm">
                                <span x-text="item"></span>
                                <input type="hidden" name="planning[]" :value="item">
                                <button type="button" @click="remove(index)" class="text-error font-bold">x</button>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            <!-- INTERVENTIONS -->
            <div class="card bg-base-100 shadow-xl border border-base-200"
                 x-data="{ 
                    items: {{ Js::from($plan->interventions ?? []) }}, 
                    newInput: '',
                    add() { if(this.newInput.trim()) { this.items.push(this.newInput); this.newInput = ''; } },
                    remove(index) { this.items.splice(index, 1); }
                 }">
                <div class="card-body p-6">
                    <h3 class="card-title text-blue-700 text-lg font-bold">Nursing Interventions</h3>
                    <p class="text-sm text-gray-600 mb-2">What will we do?</p>
                    
                    <div class="join w-full mb-4">
                        <input type="text" x-model="newInput" @keydown.enter.prevent="add()" class="input input-bordered input-sm w-full join-item" placeholder="Add intervention...">
                        <button type="button" @click="add()" class="btn btn-sm bg-sky-500 text-white join-item">+</button>
                    </div>

                    <ul class="space-y-2">
                        <template x-for="(item, index) in items" :key="index">
                            <li class="flex justify-between bg-blue-50 p-2 rounded border border-blue-100 text-sm">
                                <span x-text="item"></span>
                                <input type="hidden" name="interventions[]" :value="item">
                                <button type="button" @click="remove(index)" class="text-error font-bold">x</button>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>

        <!-- 3. RATIONALE & EVALUATION -->
        <div class="card bg-base-100 shadow-xl border border-base-200 mb-8">
            <div class="card-body p-6">
                <h3 class="card-title text-slate-800 border-b pb-2 mb-4">Evaluation</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control w-full">
                        <label class="label font-bold text-gray-800">Scientific Rationale</label>
                        <textarea name="rationale" class="textarea textarea-bordered h-24 w-full" placeholder="Why are we doing this?">{{ old('rationale', $plan->rationale) }}</textarea>
                    </div>
                    <div class="form-control w-full">
                        <label class="label font-bold text-gray-800">Patient Response / Evaluation</label>
                        <textarea name="evaluation" class="textarea textarea-bordered h-24 w-full" placeholder="Patient response...">{{ old('evaluation', $plan->evaluation) }}</textarea>
                    </div>
                </div>

                <div class="form-control mt-4">
                    <label class="label font-bold text-gray-800">Plan Status</label>
                    <select name="status" class="select select-bordered w-full max-w-xs">
                        <option value="Active" {{ $plan->status == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Resolved" {{ $plan->status == 'Resolved' ? 'selected' : '' }}>Resolved / Goal Met</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- SUBMIT -->
        <div class="flex justify-end pb-12">
            <button type="submit" class="btn btn-primary btn-lg shadow-lg">
                Save Care Plan
            </button>
        </div>

    </form>
</div>
@endsection