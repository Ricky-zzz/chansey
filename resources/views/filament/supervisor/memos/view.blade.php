@php /** @var \App\Models\Memo $memo */ @endphp
<div class="p-6 space-y-5">
    <!-- Title -->
    <div>
        <h2 class="text-2xl font-bold text-slate-800">{{ $memo->title }}</h2>
    </div>

    <!-- Metadata -->
    <div class="grid grid-cols-2 gap-4 text-sm text-slate-600 bg-slate-50 p-4 rounded-lg">
        <div>
            <span class="font-semibold">From:</span>
            @php
                $roleTitle = match($memo->creator->nurse?->role_level) {
                    'Chief' => 'Chief Nurse',
                    'Supervisor' => 'Supervisor',
                    'Head' => 'Station Head',
                    default => ''
                };
            @endphp
            {{ $memo->creator->name ?? 'Unknown' }}
            @if($roleTitle)
                <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded ml-1">{{ $roleTitle }}</span>
            @endif
        </div>
        <div>
            <span class="font-semibold">Date:</span> {{ $memo->created_at->format('M d, Y H:i') }}
        </div>
        @if($memo->target_roles && count($memo->target_roles) > 0)
        <div>
            <span class="font-semibold">Target Roles:</span> {{ implode(', ', $memo->target_roles) }}
        </div>
        @endif
        @if($memo->target_units && count($memo->target_units) > 0)
        <div>
            <span class="font-semibold">Target Units:</span>
            @php
                $unitNames = \App\Models\Unit::whereIn('id', $memo->target_units)->pluck('name')->implode(', ');
            @endphp
            {{ $unitNames }}
        </div>
        @endif
    </div>

    <!-- Body -->
    <div>
        <h3 class="text-sm font-semibold text-slate-700 mb-2">Message Content</h3>
        <div class="bg-white border border-slate-200 rounded-lg p-4 text-slate-700">
            {!! $memo->content ?? 'No content' !!}
        </div>
    </div>

    <!-- Attachments -->
    @if($memo->attachment_path)
    <div>
        <h3 class="text-sm font-semibold text-slate-700 mb-2">Attachment</h3>
        <div class="flex items-center gap-3 bg-blue-50 border border-blue-200 rounded-lg p-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            <a href="{{ asset('storage/' . $memo->attachment_path) }}" download class="text-blue-600 hover:text-blue-700 font-semibold flex-1">
                {{ basename($memo->attachment_path) }}
            </a>
            <a href="{{ asset('storage/' . $memo->attachment_path) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4m-4-6l6 6m0 0l-6 6m6-6H3" />
                </svg>
                Open
            </a>
        </div>
    </div>
    @endif
</div>
