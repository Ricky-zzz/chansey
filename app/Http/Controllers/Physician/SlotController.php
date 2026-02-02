<?php

namespace App\Http\Controllers\Physician;

use App\Http\Controllers\Controller;
use App\Models\AppointmentSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SlotController extends Controller
{
    /**
     * Display all slots for the logged-in physician.
     */
    public function index(Request $request)
    {
        $fromDate = $request->input('from_date', today()->format('Y-m-d'));
        $toDate = $request->input('to_date', today()->addMonth()->format('Y-m-d'));

        $slots = AppointmentSlot::where('physician_id', Auth::user()->physician->id)
            ->withCount('appointments')
            ->where('date', '>=', Carbon::createFromFormat('Y-m-d', $fromDate)->startOfDay())
            ->where('date', '<=', Carbon::createFromFormat('Y-m-d', $toDate)->endOfDay())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('physician.slots.index', compact('slots', 'fromDate', 'toDate'));
    }

    /**
     * Store a new appointment slot.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'capacity' => 'required|integer|min:1|max:50',
        ]);

        AppointmentSlot::create([
            'physician_id' => Auth::user()->physician->id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'capacity' => $request->capacity,
            'status' => 'Active',
        ]);

        return back()->with('success', 'Appointment slot created successfully.');
    }

    /**
     * Get appointments for a specific slot (AJAX).
     */
    public function show($id)
    {
        $slot = AppointmentSlot::with('appointments')
            ->where('physician_id', Auth::user()->physician->id)
            ->findOrFail($id);

        return response()->json([
            'slot' => $slot,
            'appointments' => $slot->appointments,
        ]);
    }

    /**
     * Cancel a slot and all its appointments.
     */
    public function cancel($id)
    {
        $slot = AppointmentSlot::with('appointments')
            ->where('physician_id', Auth::user()->physician->id)
            ->findOrFail($id);

        // 1. Mark Slot as Cancelled
        $slot->update(['status' => 'Cancelled']);

        // 2. Mark all bookings as Cancelled
        foreach ($slot->appointments as $app) {
            $app->update(['status' => 'Cancelled']);

            // Optional: Send Email here
            // Mail::to($app->email)->send(new AppointmentCancelled($app));
        }

        return back()->with('success', 'Schedule cancelled. Patients have been notified.');
    }

    /**
     * Delete a slot (only if no appointments).
     */
    public function destroy($id)
    {
        $slot = AppointmentSlot::withCount('appointments')
            ->where('physician_id', Auth::user()->physician->id)
            ->findOrFail($id);

        if ($slot->appointments_count > 0) {
            return back()->with('error', 'Cannot delete slot. Patients have already booked.');
        }

        $slot->delete();
        return back()->with('success', 'Slot removed successfully.');
    }
}
