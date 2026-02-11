@extends('layouts.clinic')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="card-enterprise p-5 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('nurse.headnurse.memos.index') }}" class="btn btn-sm btn-ghost normal-case h-9 min-h-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
                <div>
                    <h2 class="text-xl font-bold text-slate-800">{{ $title }}</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Sent on {{ $memo->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('nurse.headnurse.memos.edit', $memo->id) }}"
                   class="btn-enterprise-secondary inline-flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
            </div>
        </div>
    </div>

    {{-- Memo Content --}}
    <div class="card-enterprise p-6 space-y-6">

        {{-- Title --}}
        <div>
            <h3 class="text-2xl font-bold text-slate-800">{{ $memo->title }}</h3>
        </div>

        {{-- Metadata --}}
        <div class="grid grid-cols-2 gap-4 text-sm text-slate-600 bg-slate-50 p-4 rounded-lg border border-slate-200">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span><strong>From:</strong> {{ $memo->creator->name }}</span>
            </div>
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span><strong>Date:</strong> {{ $memo->created_at->format('M d, Y') }}</span>
            </div>
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span><strong>Target Roles:</strong> Staff Nurses</span>
            </div>
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span><strong>Target Station:</strong>
                    @php
                        $station = \App\Models\Station::find($memo->target_stations[0] ?? null);
                    @endphp
                    {{ $station ? $station->station_name : '—' }}
                </span>
            </div>
        </div>

        {{-- Content --}}
        <div>
            <h4 class="text-sm font-semibold text-slate-700 mb-3">Message Content</h4>
            <div class="prose max-w-none bg-white border border-slate-200 rounded-lg p-4">
                {!! $memo->content !!}
            </div>
        </div>

        {{-- Attachment --}}
        @if($memo->attachment_path)
        <div>
            <h4 class="text-sm font-semibold text-slate-700 mb-3">Attachment</h4>
            <div class="flex items-center gap-3 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <div class="flex-1">
                    <p class="font-semibold text-blue-800">{{ basename($memo->attachment_path) }}</p>
                    <p class="text-xs text-blue-600">Click to download or view</p>
                </div>
                <a href="{{ asset('storage/' . $memo->attachment_path) }}"
                   download
                   class="btn btn-sm btn-ghost text-blue-600 hover:bg-blue-100 normal-case h-8 min-h-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download
                </a>
                <a href="{{ asset('storage/' . $memo->attachment_path) }}"
                   target="_blank"
                   class="btn btn-sm bg-blue-600 hover:bg-blue-700 text-white border-0 normal-case h-8 min-h-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4m-4-6l6 6m0 0l-6 6m6-6H3" />
                    </svg>
                    Open
                </a>
            </div>
        </div>
        @endif

    </div>

</div>
@endsection
