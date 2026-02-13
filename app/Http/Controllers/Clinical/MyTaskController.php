<?php

namespace App\Http\Controllers\Clinical;

use App\Http\Controllers\Controller;
use App\Models\StationTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyTaskController extends Controller
{
    public function index(Request $request)
    {
        $nurse = Auth::user()->nurse;

        $query = StationTask::where('assigned_to_nurse_id', $nurse->id)
            ->with(['station', 'admission.patient', 'creator']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $tasks = $query->latest()->paginate(15);

        return view('nurse.mytasks.index', [
            'title' => 'My Tasks',
            'tasks' => $tasks,
        ]);
    }

    public function markDone(StationTask $task)
    {
        $nurse = Auth::user()->nurse;

        // Ensure task is assigned to current nurse
        if ($task->assigned_to_nurse_id !== $nurse->id) {
            abort(403, 'This task is not assigned to you.');
        }

        $task->update([
            'status' => 'Done',
            'completed_at' => now(),
        ]);

        return redirect()->route('nurse.mytasks.index')
            ->with('success', 'Task marked as done!');
    }

    public function markInProgress(StationTask $task)
    {
        $nurse = Auth::user()->nurse;

        // Ensure task is assigned to current nurse
        if ($task->assigned_to_nurse_id !== $nurse->id) {
            abort(403, 'This task is not assigned to you.');
        }

        $task->update([
            'status' => 'In Progress',
        ]);

        return redirect()->route('nurse.mytasks.index')
            ->with('success', 'Task status updated to In Progress!');
    }
}
