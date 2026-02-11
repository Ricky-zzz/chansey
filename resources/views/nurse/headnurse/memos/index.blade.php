@extends('layouts.clinic')

@section('content')
<div class="max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="card-enterprise p-5 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $title }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">Manage and send memos to your station staff</p>
            </div>
            <a href="{{ route('nurse.headnurse.memos.create') }}" class="btn-enterprise-primary inline-flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New Memo
            </a>
        </div>
    </div>


    {{-- Table --}}
    <div class="card-enterprise overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-enterprise">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Created</th>
                        <th>Attachment</th>
                        <th class="text-right">Actions</th>
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

                        {{-- CREATED DATE --}}
                        <td>
                            <div class="text-sm text-slate-700">{{ $memo->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-slate-500">{{ $memo->created_at->format('h:i A') }}</div>
                        </td>

                        {{-- ATTACHMENT --}}
                        <td>
                            @if($memo->attachment_path)
                                <span class="badge-enterprise bg-blue-50 text-blue-700 border border-blue-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    Attached
                                </span>
                            @else
                                <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>

                        {{-- ACTIONS --}}
                        <td class="text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('nurse.headnurse.memos.show', $memo->id) }}"
                                   class="btn btn-sm btn-ghost text-sky-600 hover:bg-sky-50 normal-case h-8 min-h-0"
                                   title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>

                                <a href="{{ route('nurse.headnurse.memos.edit', $memo->id) }}"
                                   class="btn btn-sm btn-ghost text-amber-600 hover:bg-amber-50 normal-case h-8 min-h-0"
                                   title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <form action="{{ route('nurse.headnurse.memos.destroy', $memo->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this memo?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-ghost text-red-600 hover:bg-red-50 normal-case h-8 min-h-0"
                                            title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-slate-400 font-medium">No memos yet</p>
                            <p class="text-sm text-slate-400 mt-1">Create your first memo to communicate with your staff</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4">
            {{ $memos->links() }}
        </div>
    </div>

</div>
@endsection
