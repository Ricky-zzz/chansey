@extends('layouts.clinic')

@section('content')
<div class="max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="card-enterprise p-5 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $title }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">View memos and announcements sent to you</p>
            </div>

        </div>
    </div>

    {{-- Table --}}
    <div class="card-enterprise overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-enterprise">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>From</th>
                        <th>Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($memos as $memo)
                    <tr>
                        {{-- TITLE --}}
                        <td>
                            <div class="font-semibold text-slate-800">{{ $memo->title }}</div>
                            <div class="text-xs text-slate-500 line-clamp-1">
                                {!! Str::limit(strip_tags($memo->content), 80) !!}
                            </div>
                        </td>

                        {{-- FROM --}}
                        <td>
                            <div class="font-medium text-slate-700">{{ $memo->creator->name ?? 'Unknown' }}</div>
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
                        </td>

                        {{-- DATE --}}
                        <td>
                            <div class="text-sm text-slate-700">{{ $memo->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-slate-500">{{ $memo->created_at->format('h:i A') }}</div>
                        </td>

                        {{-- ACTIONS --}}
                        <td class="text-center">
                            <a href="{{ route('nurse.announcement.show', $memo->id) }}"
                               class="btn btn-sm btn-ghost text-sky-600 hover:bg-sky-50 normal-case h-8 min-h-0"
                               title="View Details">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="text-slate-400 font-medium">No announcements yet</p>
                            <p class="text-sm text-slate-400 mt-1">You'll see memos and announcements sent to you here</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($memos->hasPages())
        <div class="p-4">
            {{ $memos->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
