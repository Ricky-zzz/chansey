@extends('layouts.clinic')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="card-enterprise p-5 mb-6">
        <div class="flex justify-between items-center">
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-slate-800">{{ $memo->title }}</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $title }}</p>
            </div>
            <a href="{{ route('nurse.announcement.index') }}" class="btn-enterprise-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to List
            </a>
        </div>
    </div>

    {{-- Metadata Card --}}
    <div class="card-enterprise p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            {{-- From --}}
            <div class="flex items-start gap-3">
                <div class="bg-emerald-100 rounded-lg p-2 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <div class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-1">From</div>
                    <div class="font-semibold text-slate-800">{{ $memo->creator->name ?? 'Unknown' }}</div>
                    @php
                        $roleTitle = match($memo->creator->nurse?->role_level) {
                            'Chief' => 'Chief Nurse',
                            'Supervisor' => 'Supervisor',
                            'Head' => 'Station Head',
                            default => ''
                        };
                    @endphp
                    @if($roleTitle)
                        <span class="badge-enterprise bg-emerald-50 text-emerald-700 border-emerald-200 text-xs mt-1">
                            {{ $roleTitle }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Date --}}
            <div class="flex items-start gap-3">
                <div class="bg-sky-100 rounded-lg p-2 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <div class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-1">Date</div>
                    <div class="text-slate-800 font-medium">{{ $memo->created_at->format('F d, Y') }}</div>
                    <div class="text-xs text-slate-500">{{ $memo->created_at->format('h:i A') }}</div>
                </div>
            </div>

            {{-- Target Roles (if specified) --}}
            @if($memo->target_roles && count($memo->target_roles) > 0)
            <div class="flex items-start gap-3">
                <div class="bg-purple-100 rounded-lg p-2 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <div class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-1">Target Roles</div>
                    <div class="flex flex-wrap gap-1">
                        @foreach($memo->target_roles as $role)
                            <span class="badge-enterprise bg-purple-50 text-purple-700 border-purple-200 text-xs">
                                {{ $role }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Target Units (if specified) --}}
            @if($memo->target_units && count($memo->target_units) > 0)
            <div class="flex items-start gap-3">
                <div class="bg-amber-100 rounded-lg p-2 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <div class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-1">Target Units</div>
                    <div class="text-sm text-slate-700">
                        @php
                            $unitNames = \App\Models\Unit::whereIn('id', $memo->target_units)->pluck('name')->implode(', ');
                        @endphp
                        {{ $unitNames }}
                    </div>
                </div>
            </div>
            @endif

            {{-- Target Stations (if specified) --}}
            @if($memo->target_stations && count($memo->target_stations) > 0)
            <div class="flex items-start gap-3 md:col-span-2">
                <div class="bg-blue-100 rounded-lg p-2 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <div class="text-xs text-slate-500 uppercase tracking-wide font-semibold mb-1">Target Stations</div>
                    <div class="text-sm text-slate-700">
                        @php
                            $stationNames = \App\Models\Station::whereIn('id', $memo->target_stations)->pluck('station_name')->implode(', ');
                        @endphp
                        {{ $stationNames }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Content Card --}}
    <div class="card-enterprise p-6 mb-6">
        <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Message Content
        </h3>
        <div class="prose max-w-none bg-slate-50 border border-slate-200 rounded-lg p-6 text-slate-700">
            {!! $memo->content ?? '<p class="text-slate-400 italic">No content</p>' !!}
        </div>
    </div>

    {{-- Attachment Card --}}
    @if($memo->attachment_path)
    <div class="card-enterprise p-6">
        <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
            </svg>
            Attachment
        </h3>
        <div class="flex items-center justify-between gap-4 bg-blue-50 border-2 border-blue-200 rounded-lg p-4">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <div class="bg-blue-100 rounded-lg p-2 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="font-semibold text-slate-800 truncate">{{ basename($memo->attachment_path) }}</div>
                    <div class="text-xs text-slate-500">Click to download or open</div>
                </div>
            </div>
            <div class="flex gap-2 shrink-0">
                <a href="{{ asset('storage/' . $memo->attachment_path) }}" download
                   class="btn-enterprise-secondary gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download
                </a>
                <a href="{{ asset('storage/' . $memo->attachment_path) }}" target="_blank"
                   class="btn-enterprise-primary gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    Open
                </a>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
