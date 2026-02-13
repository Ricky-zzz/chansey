@extends('layouts.clinic')

@section('content')
<div class="max-w-7xl mx-auto" x-data="taskManager()">

    {{-- Header --}}
    <div class="card-enterprise p-5 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $title }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">Manage and assign tasks to nurses in your station</p>
            </div>
            <button @click="openCreateModal()" class="btn-enterprise-primary inline-flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Create Task
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card-enterprise p-4 mb-6">
        <form method="GET" action="{{ route('nurse.headnurse.tasks.index') }}" class="flex flex-wrap gap-3 items-end">
            {{-- Status Tabs --}}
            <div class="flex gap-2">
                <a href="{{ route('nurse.headnurse.tasks.index') }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ !request('status') ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    All
                </a>
                <a href="{{ route('nurse.headnurse.tasks.index', ['status' => 'Pending']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === 'Pending' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Pending
                </a>
                <a href="{{ route('nurse.headnurse.tasks.index', ['status' => 'In Progress']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === 'In Progress' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    In Progress
                </a>
                <a href="{{ route('nurse.headnurse.tasks.index', ['status' => 'Done']) }}"
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

    {{-- Table --}}
    <div class="card-enterprise overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-enterprise">
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Priority</th>
                        <th>Assigned To</th>
                        <th>Patient</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                    <tr>
                        <td>
                            <div class="font-semibold text-slate-800">{{ $task->title }}</div>
                            @if($task->description)
                                <div class="text-xs text-slate-500 mt-0.5">{{ Str::limit($task->description, 50) }}</div>
                            @endif
                        </td>
                        <td>
                            @if($task->priority === 'High')
                                <span class="badge-enterprise bg-red-50 text-red-700 border border-red-200">High</span>
                            @elseif($task->priority === 'Medium')
                                <span class="badge-enterprise bg-amber-50 text-amber-700 border border-amber-200">Medium</span>
                            @else
                                <span class="badge-enterprise bg-slate-50 text-slate-700 border border-slate-200">Low</span>
                            @endif
                        </td>
                        <td>
                            <div class="text-sm font-medium text-slate-800">{{ $task->assignee->first_name }} {{ $task->assignee->last_name }}</div>
                            <div class="text-xs text-slate-500">{{ $task->assignee->employee_id }}</div>
                        </td>
                        <td>
                            @if($task->admission)
                                <div class="text-sm font-medium text-slate-800">{{ $task->admission->patient->last_name }}, {{ $task->admission->patient->first_name }}</div>
                                <div class="text-xs text-slate-500">{{ $task->admission->admission_number }}</div>
                            @else
                                <span class="text-slate-400 text-xs">General Task</span>
                            @endif
                        </td>
                        <td>
                            @if($task->status === 'Done')
                                <span class="badge-enterprise bg-emerald-50 text-emerald-700 border border-emerald-200">Done</span>
                            @elseif($task->status === 'In Progress')
                                <span class="badge-enterprise bg-blue-50 text-blue-700 border border-blue-200">In Progress</span>
                            @else
                                <span class="badge-enterprise bg-slate-50 text-slate-700 border border-slate-200">Pending</span>
                            @endif
                        </td>
                        <td>
                            <div class="text-sm text-slate-600">{{ $task->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-slate-500">{{ $task->created_at->format('h:i A') }}</div>
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <button @click="openEditModal({{ $task->id }})" class="btn btn-xs btn-ghost text-blue-600 hover:bg-blue-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                                <form action="{{ route('nurse.headnurse.tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Delete this task?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-ghost text-red-600 hover:bg-red-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-slate-500 py-8">No tasks found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4">
            {{ $tasks->links() }}
        </div>
    </div>

    {{-- CREATE/EDIT MODAL --}}
    <dialog id="task_modal" class="modal" x-ref="taskModal">
        <div class="modal-enterprise">
            <form method="dialog">
                <button class="absolute right-4 top-4 text-slate-400 hover:text-slate-600 transition-colors">✕</button>
            </form>

            <h3 class="text-lg font-bold text-slate-800 mb-1" x-text="isEditMode ? 'Edit Task' : 'Create New Task'"></h3>
            <p class="text-sm text-slate-500 mb-4">Assign a task to a nurse in your station</p>

            <form :action="formAction" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" x-model="formMethod">

                {{-- Title --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Task Title *</label>
                    <input type="text" name="title" x-model="formData.title" required class="input-enterprise w-full" placeholder="e.g., Check patient vitals">
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Description</label>
                    <textarea name="description" x-model="formData.description" rows="3" class="textarea-enterprise w-full" placeholder="Additional details..."></textarea>
                </div>

                {{-- Priority --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Priority *</label>
                    <select name="priority" x-model="formData.priority" required class="select-enterprise w-full">
                        <option value="">Select Priority</option>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>

                {{-- Assign To --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Assign To *</label>
                    <select name="assigned_to_nurse_id" x-model="formData.assigned_to_nurse_id" required class="select-enterprise w-full">
                        <option value="">Select Nurse</option>
                        @foreach($stationNurses as $nurse)
                            <option value="{{ $nurse->id }}">{{ $nurse->first_name }} {{ $nurse->last_name }} ({{ $nurse->employee_id }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Patient (Optional) --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Related Patient (Optional)</label>
                    <select name="admission_id" x-model="formData.admission_id" class="select-enterprise w-full">
                        <option value="">None (General Task)</option>
                        @foreach($admissions as $admission)
                            <option value="{{ $admission->id }}">
                                {{ $admission->patient->last_name }}, {{ $admission->patient->first_name }} ({{ $admission->admission_number }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-200">
                    <button type="button" @click="$refs.taskModal.close()" class="btn-enterprise-secondary">Cancel</button>
                    <button type="submit" class="btn-enterprise-primary" x-text="isEditMode ? 'Update Task' : 'Create Task'"></button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

</div>

@push('scripts')
<script>
    window.tasksData = @json($tasks->items());

    function taskManager() {
        return {
            isEditMode: false,
            formAction: '',
            formMethod: 'POST',
            formData: {
                title: '',
                description: '',
                priority: '',
                assigned_to_nurse_id: '',
                admission_id: '',
            },
            tasks: window.tasksData,

            openCreateModal() {
                this.isEditMode = false;
                this.formAction = '{{ route("nurse.headnurse.tasks.store") }}';
                this.formMethod = 'POST';
                this.formData = {
                    title: '',
                    description: '',
                    priority: '',
                    assigned_to_nurse_id: '',
                    admission_id: '',
                };
                this.$refs.taskModal.showModal();
            },

            openEditModal(taskId) {
                const task = this.tasks.find(t => t.id === taskId);
                if (!task) return;

                this.isEditMode = true;
                this.formAction = `{{ url('nurse/headnurse/tasks') }}/${taskId}`;
                this.formMethod = 'PUT';
                this.formData = {
                    title: task.title,
                    description: task.description || '',
                    priority: task.priority,
                    assigned_to_nurse_id: task.assigned_to_nurse_id,
                    admission_id: task.admission_id || '',
                };
                this.$refs.taskModal.showModal();
            }
        }
    }
</script>
@endpush
@endsection
