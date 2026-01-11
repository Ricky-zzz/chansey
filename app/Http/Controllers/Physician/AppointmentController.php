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
        $physician_id = Auth::user()->physician->id;

        $appointments = Appointment::where('physician_id', $physician_id)
            ->whereDate('scheduled_at', today())
            ->where('status', '!=', 'Cancelled')
            ->orderBy('scheduled_at', 'asc')
            ->get()
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
