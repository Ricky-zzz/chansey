<?php

namespace App\Http\Controllers\HeadNurse;

use App\Http\Controllers\Controller;
use App\Models\ShiftSchedule;
use Illuminate\Http\Request;

class ShiftScheduleController extends Controller
{
    /**
     * Display a listing of shift schedules.
     */
    public function index(Request $request)
    {
        $schedules = ShiftSchedule::withCount('nurses')
            ->orderBy('name')
            ->paginate(10);

        return view('nurse.headnurse.shifts.index', compact('schedules'));
    }

    /**
     * Store a newly created shift schedule.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shift_schedules,name',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'monday' => 'boolean',
            'tuesday' => 'boolean',
            'wednesday' => 'boolean',
            'thursday' => 'boolean',
            'friday' => 'boolean',
            'saturday' => 'boolean',
            'sunday' => 'boolean',
        ]);

        // Ensure at least one day is selected
        $days = collect(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])
            ->filter(fn($day) => $request->boolean($day))
            ->count();

        if ($days === 0) {
            return back()->withErrors(['days' => 'Please select at least one day.'])->withInput();
        }

        ShiftSchedule::create([
            'name' => $validated['name'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'monday' => $request->boolean('monday'),
            'tuesday' => $request->boolean('tuesday'),
            'wednesday' => $request->boolean('wednesday'),
            'thursday' => $request->boolean('thursday'),
            'friday' => $request->boolean('friday'),
            'saturday' => $request->boolean('saturday'),
            'sunday' => $request->boolean('sunday'),
        ]);

        return redirect()->route('nurse.headnurse.shifts.index')
            ->with('success', 'Shift schedule created successfully.');
    }

    /**
     * Update the specified shift schedule.
     */
    public function update(Request $request, ShiftSchedule $schedule)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shift_schedules,name,' . $schedule->id,
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'monday' => 'boolean',
            'tuesday' => 'boolean',
            'wednesday' => 'boolean',
            'thursday' => 'boolean',
            'friday' => 'boolean',
            'saturday' => 'boolean',
            'sunday' => 'boolean',
        ]);

        // Ensure at least one day is selected
        $days = collect(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])
            ->filter(fn($day) => $request->boolean($day))
            ->count();

        if ($days === 0) {
            return back()->withErrors(['days' => 'Please select at least one day.'])->withInput();
        }

        $schedule->update([
            'name' => $validated['name'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'monday' => $request->boolean('monday'),
            'tuesday' => $request->boolean('tuesday'),
            'wednesday' => $request->boolean('wednesday'),
            'thursday' => $request->boolean('thursday'),
            'friday' => $request->boolean('friday'),
            'saturday' => $request->boolean('saturday'),
            'sunday' => $request->boolean('sunday'),
        ]);

        return redirect()->route('nurse.headnurse.shifts.index')
            ->with('success', 'Shift schedule updated successfully.');
    }

    /**
     * Remove the specified shift schedule.
     */
    public function destroy(ShiftSchedule $schedule)
    {
        // Check if nurses are assigned to this schedule
        if ($schedule->nurses()->exists()) {
            return back()->with('error', 'Cannot delete this schedule. There are nurses currently assigned to it.');
        }

        $schedule->delete();

        return redirect()->route('nurse.headnurse.shifts.index')
            ->with('success', 'Shift schedule deleted successfully.');
    }
}
