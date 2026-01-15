<?php

namespace App\Http\Controllers\Admission;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Physician;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail; 
use App\Mail\AppointmentApproved; 

class AppointmentController extends Controller
{
    public function index()
    {
        $pending = Appointment::with('department')
            ->where('status', 'Pending')
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        $upcoming = Appointment::with(['physician', 'department'])
            ->where('status', 'Approved')
            ->whereDate('scheduled_at', '=', today())
            ->orderBy('scheduled_at', 'asc')
            ->paginate(10);

        $physicians = Physician::with('department')->get();

        return view('nurse.admitting.appointments.index', compact('pending', 'upcoming', 'physicians'));
    } 

    public function approve(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $request->validate([
            'physician_id' => 'required|exists:physicians,id',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'scheduled_time' => 'required', 
        ]);

        $start = Carbon::parse($request->scheduled_date . ' ' . $request->scheduled_time);
        $end = $start->copy()->addMinutes(30); 

        $conflict = Appointment::where('physician_id', $request->physician_id)
            ->where('status', 'Approved')
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('scheduled_at', [$start, $end])
                  ->orWhereBetween('end_time', [$start, $end]);
            })
            ->exists();

        if ($conflict) {
            return back()->with('error', 'Doctor is busy at that time.');
        }

        $appointment->update([
            'physician_id' => $request->physician_id,
            'scheduled_at' => $start,
            'end_time' => $end,
            'status' => 'Approved'
        ]);

        if($appointment->email) Mail::to($appointment->email)->send(new AppointmentApproved($appointment));

        return back()->with('success', 'Appointment Scheduled!');
    }
    
    public function cancel($id)
    {
        Appointment::findOrFail($id)->update(['status' => 'Cancelled']);
        return back()->with('success', 'Request rejected.');
    }
}