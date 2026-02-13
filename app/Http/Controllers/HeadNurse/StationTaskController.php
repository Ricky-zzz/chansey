<?php

namespace App\Http\Controllers\HeadNurse;

use App\Http\Controllers\Controller;
use App\Models\StationTask;
use App\Models\Nurse;
use App\Models\Admission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StationTaskController extends Controller
{
    public function index(Request $request)
    {
        $nurse = Auth::user()->nurse;
        $stationId = $nurse->station_id;

        $query = StationTask::where('station_id', $stationId)
            ->with(['assignee', 'admission.patient', 'creator']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $tasks = $query->latest()->paginate(15);

        //  nurses in the same station
        $stationNurses = Nurse::where('station_id', $stationId)
            ->where('status', 'Active')
            ->with('user')
            ->get();

        //  active admissions in the station
        $admissions = Admission::where('station_id', $stationId)
            ->where('status', '!=', 'Discharged')
            ->with('patient')
            ->get();

        return view('nurse.headnurse.stationtasks.index', [
            'title' => 'Station Tasks',
            'tasks' => $tasks,
            'stationNurses' => $stationNurses,
            'admissions' => $admissions,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:Low,Medium,High',
            'assigned_to_nurse_id' => 'required|exists:nurses,id',
            'admission_id' => 'nullable|exists:admissions,id',
        ]);

        $nurse = Auth::user()->nurse;

        StationTask::create([
            'station_id' => $nurse->station_id,
            'created_by_user_id' => Auth::id(),
            'assigned_to_nurse_id' => $validated['assigned_to_nurse_id'],
            'admission_id' => $validated['admission_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'Pending',
        ]);

        return redirect()->route('nurse.headnurse.tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function update(Request $request, StationTask $task)
    {
        $nurse = Auth::user()->nurse;
        if ($task->station_id !== $nurse->station_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:Low,Medium,High',
            'assigned_to_nurse_id' => 'required|exists:nurses,id',
            'admission_id' => 'nullable|exists:admissions,id',
        ]);

        $task->update([
            'assigned_to_nurse_id' => $validated['assigned_to_nurse_id'],
            'admission_id' => $validated['admission_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
        ]);

        return redirect()->route('nurse.headnurse.tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(StationTask $task)
    {
        $nurse = Auth::user()->nurse;
        if ($task->station_id !== $nurse->station_id) {
            abort(403);
        }

        $task->delete();

        return redirect()->route('nurse.headnurse.tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}
