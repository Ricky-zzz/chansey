<?php

namespace App\Http\Controllers\Physician;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use App\Models\Admission;

class AppointmentController extends Controller
{
    public function index()
    {
        $physician = Auth::user()->physician;

        // Get today's appointment slots for this physician
        $slots = $physician->appointmentSlots()
            ->whereDate('date', today())
            ->orderBy('start_time', 'asc')
            ->with('appointments')
            ->get();

        // Flatten and organize appointments in queue order
        $appointments = $slots->flatMap(function ($slot) {
            return $slot->appointments()->where('status', '!=', 'Cancelled')->with('appointmentSlot')->get();
        })
        ->map(function ($app) {
            $admission = Admission::whereHas('patient', function ($q) use ($app) {
                $q->where('first_name', $app->first_name)
                    ->where('last_name', $app->last_name);
            })
                ->whereDate('created_at', today())
                ->latest()
                ->first();

            $app->admission_id = $admission ? $admission->id : null;
            return $app;
        });

        return view('physician.appointments.index', compact('appointments'));
    }

    public function complete($id)
    {
        Appointment::where('id', $id)->update(['status' => 'Completed']);
        return back()->with('success', 'Marked as done.');
    }
}
