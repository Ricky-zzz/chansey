@extends('layouts.clinic')

@section('content')
<div class="max-w-5xl mx-auto">

    {{-- Header --}}
    <div class="card-enterprise p-5 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('nurse.clinical.endorsments.index') }}" class="btn btn-circle btn-ghost btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-slate-800">{{ $title }}</h2>
                    <p class="text-sm text-slate-500 mt-0.5">
                        <strong>{{ $endorsment->admission->patient->first_name }} {{ $endorsment->admission->patient->last_name }}</strong>
                    </p>
                </div>
            </div>
            <div class="text-right">
                <div class="mb-2">
                    @if($endorsment->isLocked())
                        <span class="badge-enterprise bg-emerald-50 text-emerald-700 border border-emerald-200">✓ Submitted</span>
                    @else
                        <span class="badge-enterprise bg-amber-50 text-amber-700 border border-amber-200">Draft</span>
                    @endif
                </div>
                <p class="text-xs text-slate-500">
                    Created {{ $endorsment->created_at->diffForHumans() }}
                </p>
            </div>
        </div>
    </div>

    {{-- Key Info Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="stat-card">
            <div class="stat-title text-xs text-slate-500">From Nurse</div>
            <div class="stat-value text-sm text-slate-800">{{ $endorsment->outgoingNurse->name }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title text-xs text-slate-500">To Nurse</div>
            <div class="stat-value text-sm text-slate-800">{{ $endorsment->incomingNurse->user->name }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title text-xs text-slate-500">Code Status</div>
            <div class="stat-value text-lg {{ match($endorsment->code_status) {
                'Severe' => 'text-red-700',
                'High' => 'text-orange-700',
                'Moderate' => 'text-amber-700',
                'Low' => 'text-green-700',
                default => 'text-slate-800'
            } }}">{{ $endorsment->code_status }}</div>
        </div>
    </div>

    {{-- View Tracking Banner (if submitted) --}}
    @if($endorsment->isLocked())
        <div class="card-enterprise p-4 mb-6 bg-slate-50 border-l-4 border-blue-500">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <div class="text-sm text-slate-700">
                    <strong>Submitted:</strong> {{ $endorsment->submitted_at->format('M d, Y \a\t h:i A') }}
                    @if($endorsment->viewers->count() > 0)
                        | <strong>Viewed:</strong>
                        @php
                            $lastViewer = $endorsment->viewers->sortByDesc('viewed_at')->first();
                        @endphp
                        {{ $endorsment->viewers->count() }}x (Last: {{ $lastViewer->user->name }})
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- SITUATION --}}
    <div class="card-enterprise p-6 mb-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <span class="inline-flex items-center justify-center w-8 h-8 bg-emerald-100 text-emerald-700 rounded-full text-sm font-bold">S</span>
            Situation
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase">Diagnosis</p>
                <p class="text-slate-800">{{ $endorsment->diagnosis ?? 'Not specified' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase">Code Status</p>
                <p class="text-slate-800">{{ $endorsment->code_status }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs text-slate-500 font-semibold uppercase">Current Condition</p>
                <p class="text-slate-800 whitespace-pre-wrap">{{ $endorsment->current_condition ?? 'Not specified' }}</p>
            </div>
        </div>
    </div>

    {{-- BACKGROUND --}}
    <div class="card-enterprise p-6 mb-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <span class="inline-flex items-center justify-center w-8 h-8 bg-sky-100 text-sky-700 rounded-full text-sm font-bold">B</span>
            Background
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase mb-2">Allergies</p>
                @if($endorsment->known_allergies && count($endorsment->known_allergies) > 0)
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($endorsment->known_allergies as $allergy)
                            <li class="text-slate-800">{{ $allergy }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-slate-500 italic">None documented</p>
                @endif
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase mb-2">Medication History</p>
                @if($endorsment->medication_history && count($endorsment->medication_history) > 0)
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($endorsment->medication_history as $med)
                            <li class="text-slate-800">{{ $med }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-slate-500 italic">None documented</p>
                @endif
            </div>
            <div class="md:col-span-2">
                <p class="text-xs text-slate-500 font-semibold uppercase mb-2">Past Medical History</p>
                @if($endorsment->past_medical_history && count($endorsment->past_medical_history) > 0)
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($endorsment->past_medical_history as $history)
                            <li class="text-slate-800">{{ $history }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-slate-500 italic">None documented</p>
                @endif
            </div>
        </div>
    </div>

    {{-- ASSESSMENT --}}
    <div class="card-enterprise p-6 mb-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <span class="inline-flex items-center justify-center w-8 h-8 bg-amber-100 text-amber-700 rounded-full text-sm font-bold">A</span>
            Assessment
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4 bg-white/60 rounded-lg p-4 border border-slate-100">
                {{-- Vitals --}}
                @if($endorsment->latest_vitals)
                    <div class="border border-slate-200 rounded-lg p-4 bg-slate-50">
                        <p class="text-xs text-slate-700 font-semibold uppercase mb-3 tracking-wide">Latest Vitals</p>
                        <div class="space-y-2">
                            @foreach($endorsment->latest_vitals as $key => $value)
                                <div class="flex justify-between">
                                    <span class="text-slate-600 text-sm font-semibold">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                    <span class="font-semibold text-slate-800">{{ $value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                {{-- IV Lines --}}
                <div class="border border-slate-200 rounded-lg p-4 bg-slate-50">
                    <p class="text-xs text-slate-700 font-semibold uppercase mb-2 tracking-wide">IV Lines</p>
                    @if($endorsment->iv_lines && count($endorsment->iv_lines) > 0)
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($endorsment->iv_lines as $iv)
                                <li class="text-slate-800 text-sm">{{ $iv }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-slate-500 italic text-sm">None</p>
                    @endif
                </div>
                {{-- Wounds --}}
                <div class="border border-slate-200 rounded-lg p-4 bg-slate-50">
                    <p class="text-xs text-slate-700 font-semibold uppercase mb-2 tracking-wide">Wounds / Incisions</p>
                    @if($endorsment->wounds && count($endorsment->wounds) > 0)
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($endorsment->wounds as $wound)
                                <li class="text-slate-800 text-sm">{{ $wound }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-slate-500 italic text-sm">None</p>
                    @endif
                </div>
            </div>
            <div class="space-y-4 bg-white/60 rounded-lg p-4 border border-slate-100">
                <div class="border border-slate-200 rounded-lg p-4 bg-slate-50 flex flex-col items-center justify-center">
                    <p class="text-xs text-slate-700 font-semibold uppercase mb-2 tracking-wide">Pain Scale</p>
                    <p class="text-emerald-700 font-bold text-3xl">{{ $endorsment->pain_scale ?? 'Not assessed' }}</p>
                </div>
                {{-- Labs Pending --}}
                <div class="border border-slate-200 rounded-lg p-4 bg-slate-50">
                    <p class="text-xs text-slate-700 font-semibold uppercase mb-2 tracking-wide">Pending Labs</p>
                    @if($endorsment->labs_pending && count($endorsment->labs_pending) > 0)
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($endorsment->labs_pending as $lab)
                                <li class="text-slate-800 text-sm">{{ $lab }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-slate-500 italic text-sm">None pending</p>
                    @endif
                </div>
                {{-- Abnormal Findings --}}
                <div class="border border-slate-200 rounded-lg p-4 bg-slate-50">
                    <p class="text-xs text-slate-700 font-semibold uppercase mb-2 tracking-wide">Abnormal Findings</p>
                    @if($endorsment->abnormal_findings && count($endorsment->abnormal_findings) > 0)
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($endorsment->abnormal_findings as $finding)
                                <li class="text-slate-800 text-sm">{{ $finding }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-slate-500 italic text-sm">None documented</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- RECOMMENDATIONS --}}
    <div class="card-enterprise p-6 mb-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <span class="inline-flex items-center justify-center w-8 h-8 bg-pink-100 text-pink-700 rounded-full text-sm font-bold">R</span>
            Recommendations
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase mb-2">Upcoming Medications</p>
                @if($endorsment->upcoming_medications && count($endorsment->upcoming_medications) > 0)
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($endorsment->upcoming_medications as $med)
                            <li class="text-slate-800 text-sm">{{ $med }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-slate-500 italic text-sm">None scheduled</p>
                @endif
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase mb-2">Lab Follow-ups</p>
                @if($endorsment->labs_follow_up && count($endorsment->labs_follow_up) > 0)
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($endorsment->labs_follow_up as $followup)
                            <li class="text-slate-800 text-sm">{{ $followup }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-slate-500 italic text-sm">None</p>
                @endif
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase mb-2">Monitoring Instructions</p>
                @if($endorsment->monitor_instructions && count($endorsment->monitor_instructions) > 0)
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($endorsment->monitor_instructions as $instruction)
                            <li class="text-slate-800 text-sm">{{ $instruction }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-slate-500 italic text-sm">None</p>
                @endif
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase mb-2">Special Precautions</p>
                @if($endorsment->special_precautions && count($endorsment->special_precautions) > 0)
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($endorsment->special_precautions as $precaution)
                            <li class="text-slate-800 text-sm">{{ $precaution }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-slate-500 italic text-sm">None</p>
                @endif
            </div>
        </div>
    </div>

    {{-- WARD STATUS (if any) --}}
    @if($endorsment->bed_occupancy || $endorsment->equipment_issues || $endorsment->station_issues || $endorsment->critical_incidents)
        <div class="card-enterprise p-6 mb-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Ward / Station Status</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($endorsment->bed_occupancy)
                    <div>
                        <p class="text-xs text-slate-500 font-semibold uppercase mb-2">Bed Occupancy</p>
                        <p class="text-slate-800">{{ $endorsment->bed_occupancy }}</p>
                    </div>
                @endif

                @if($endorsment->equipment_issues && count($endorsment->equipment_issues) > 0)
                    <div>
                        <p class="text-xs text-slate-500 font-semibold uppercase mb-2">Equipment Issues</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($endorsment->equipment_issues as $issue)
                                <li class="text-slate-800 text-sm">{{ $issue }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($endorsment->station_issues && count($endorsment->station_issues) > 0)
                    <div>
                        <p class="text-xs text-slate-500 font-semibold uppercase mb-2">Station Issues</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($endorsment->station_issues as $issue)
                                <li class="text-slate-800 text-sm">{{ $issue }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($endorsment->critical_incidents && count($endorsment->critical_incidents) > 0)
                    <div>
                        <p class="text-xs text-slate-500 font-semibold uppercase mb-2">Critical Incidents</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($endorsment->critical_incidents as $incident)
                                <li class="text-slate-800 text-sm">{{ $incident }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- NOTES SECTION --}}
    <div class="card-enterprise p-6 mb-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Notes & Amendments</h3>

        @if($endorsment->notes->count() > 0)
            <div class="space-y-4 mb-6">
                @foreach($endorsment->notes->sortByDesc('created_at') as $note)
                    <div class="border border-slate-200 rounded-lg p-4 {{ $note->note_type === 'correction' ? 'bg-amber-50' : 'bg-blue-50' }}">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <p class="font-semibold text-slate-800">{{ $note->user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $note->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="badge-enterprise {{ $note->note_type === 'correction' ? 'bg-amber-100 text-amber-700 border-amber-200' : 'bg-blue-100 text-blue-700 border-blue-200' }}">
                                {{ ucfirst($note->note_type) }}
                            </span>
                        </div>
                        <p class="text-slate-800 whitespace-pre-wrap">{{ $note->note }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-slate-500 italic mb-6">No notes added yet</p>
        @endif

        {{-- Add Note Form (if submitted) --}}
        @if($endorsment->isLocked())
            <div class="border-t border-slate-200 pt-4">
                <form action="{{ route('nurse.clinical.endorsments.notes.store', $endorsment->id) }}" method="POST">
                    @csrf
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Add Note
                        <span class="text-xs text-slate-500 font-normal">
                            ({{ $isCreator ? 'Correction' : 'Observation' }})
                        </span>
                    </label>
                    <textarea name="note" class="textarea-enterprise w-full h-20 mb-3" placeholder="Add a correction, observation, or important note..." required></textarea>
                    @error('note')
                        <p class="text-red-500 text-xs mb-2">{{ $message }}</p>
                    @enderror
                    <button type="submit" class="btn-enterprise-primary">
                        Add Note
                    </button>
                </form>
            </div>
        @elseif($isCreator)
            {{-- Show draft message + submit button --}}
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-4">
                <p class="text-amber-800 text-sm mb-3">This endorsement is still a <strong>draft</strong>. Submit it to lock it and allow the incoming nurse to add notes.</p>
                <form action="{{ route('nurse.clinical.endorsments.submit', $endorsment->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-enterprise-primary">
                        Submit Now
                    </button>
                </form>
            </div>
        @endif
    </div>

    {{-- Footer Actions --}}
    <div class="flex gap-3">
        <a href="{{ route('nurse.clinical.endorsments.index') }}" class="btn-enterprise-secondary">
            Back to Endorsements
        </a>
    </div>

</div>

@endsection
