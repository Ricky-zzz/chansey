@extends('layouts.clinic')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="card-enterprise p-5 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $title }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">View and complete tasks assigned to you</p>
            </div>
            <div class="flex gap-2">
                @php
                    $pendingCount = Auth::user()->nurse->assignedTasks()->where('status', 'Pending')->count();
                    $inProgressCount = Auth::user()->nurse->assignedTasks()->where('status', 'In Progress')->count();
                @endphp
                <div class="stat-card flex items-center gap-3">
                    <div class="bg-amber-100 p-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-medium">Pending</div>
                        <div class="text-xl font-bold text-slate-800">{{ $pendingCount }}</div>
                    </div>
                </div>
                <div class="stat-card flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-medium">In Progress</div>
                        <div class="text-xl font-bold text-slate-800">{{ $inProgressCount }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card-enterprise p-4 mb-6">
        <form method="GET" action="{{ route('nurse.mytasks.index') }}" class="flex flex-wrap gap-3 items-end">
            {{-- Status Tabs --}}
            <div class="flex gap-2">
                <a href="{{ route('nurse.mytasks.index') }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ !request('status') ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    All
                </a>
                <a href="{{ route('nurse.mytasks.index', ['status' => 'Pending']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === 'Pending' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Pending
                </a>
                <a href="{{ route('nurse.mytasks.index', ['status' => 'In Progress']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === 'In Progress' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    In Progress
                </a>
                <a href="{{ route('nurse.mytasks.index', ['status' => 'Done']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === 'Done' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Done
                </a>
            </div>

            {{-- Search Bar --}}
            <div class="flex gap-2 flex-1 max-w-md">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title..." class="input-enterprise flex-1">
                <button type="submit" class="btn-enterprise-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </button>
            </div>
        </form>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="alert bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg shadow-sm mb-6 p-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Tasks Cards --}}
    <div class="grid gap-4">
        @forelse($tasks as $task)
        <div class="card-enterprise p-5 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between gap-4">
                {{-- Task Info --}}
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <h3 class="font-bold text-slate-800 text-lg">{{ $task->title }}</h3>
                        @if($task->priority === 'High')
                            <span class="badge-enterprise bg-red-50 text-red-700 border border-red-200">High Priority</span>
                        @elseif($task->priority === 'Medium')
                            <span class="badge-enterprise bg-amber-50 text-amber-700 border border-amber-200">Medium Priority</span>
                        @else
                            <span class="badge-enterprise bg-slate-50 text-slate-700 border border-slate-200">Low Priority</span>
                        @endif
                    </div>

                    @if($task->description)
                        <p class="text-sm text-slate-600 mb-3">{{ $task->description }}</p>
                    @endif

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                        {{-- Patient Info --}}
                        <div>
                            <div class="text-xs text-slate-500 font-medium mb-0.5">Patient</div>
                            @if($task->admission)
                                <div class="font-semibold text-slate-800">{{ $task->admission->patient->last_name }}, {{ $task->admission->patient->first_name }}</div>
                                <div class="text-xs text-slate-500">{{ $task->admission->admission_number }}</div>
                            @else
                                <div class="text-slate-400 italic">General Task</div>
                            @endif
                        </div>

                        {{-- Status --}}
                        <div>
                            <div class="text-xs text-slate-500 font-medium mb-0.5">Status</div>
                            @if($task->status === 'Done')
                                <span class="badge-enterprise bg-emerald-50 text-emerald-700 border border-emerald-200">Done</span>
                            @elseif($task->status === 'In Progress')
                                <span class="badge-enterprise bg-blue-50 text-blue-700 border border-blue-200">In Progress</span>
                            @else
                                <span class="badge-enterprise bg-slate-50 text-slate-700 border border-slate-200">Pending</span>
                            @endif
                        </div>

                        {{-- Assigned By --}}
                        <div>
                            <div class="text-xs text-slate-500 font-medium mb-0.5">Assigned By</div>
                            <div class="font-medium text-slate-800">{{ $task->creator->name }}</div>
                        </div>

                        {{-- Created Date --}}
                        <div>
                            <div class="text-xs text-slate-500 font-medium mb-0.5">Created</div>
                            <div class="text-slate-800">{{ $task->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-slate-500">{{ $task->created_at->format('h:i A') }}</div>
                        </div>
                    </div>

                    @if($task->status === 'Done' && $task->completed_at)
                        <div class="mt-3 pt-3 border-t border-emerald-100">
                            <div class="flex items-center gap-2 text-emerald-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span class="text-sm font-medium">Completed on {{ $task->completed_at->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="flex flex-col gap-2">
                    @if($task->status === 'Pending')
                        <form action="{{ route('nurse.mytasks.markInProgress', $task) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-enterprise-info btn-sm whitespace-nowrap">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                Start Task
                            </button>
                        </form>
                    @endif

                    @if($task->status !== 'Done')
                        <form action="{{ route('nurse.mytasks.markDone', $task) }}" method="POST" onsubmit="return confirm('Mark this task as done?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-enterprise-primary btn-sm whitespace-nowrap">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Mark Done
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="card-enterprise p-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            <h3 class="text-lg font-semibold text-slate-600 mb-1">No Tasks Found</h3>
            <p class="text-sm text-slate-500">You don't have any tasks assigned to you yet.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($tasks->count() > 0)
    <div class="mt-6">
        {{ $tasks->links() }}
    </div>
    @endif

</div>
@endsection
