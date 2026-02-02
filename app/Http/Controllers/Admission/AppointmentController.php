<?php

namespace App\Http\Controllers\Admission;

use App\Http\Controllers\Controller;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    /**
     * Show all appointments scheduled for today.
     * No more pending/approval - patients book directly through slots.
     */
    public function index()
    {
        // Get all appointments for today (from slots with today's date)
        $todayAppointments = Appointment::with(['appointmentSlot.physician.department'])
            ->whereHas('appointmentSlot', function ($query) {
                $query->whereDate('date', today());
            })
            ->where('status', 'Booked') // Only show booked, not cancelled
            ->orderBy(
                \App\Models\AppointmentSlot::select('start_time')
                    ->whereColumn('appointment_slots.id', 'appointments.appointment_slot_id')
            )
            ->get();

        return view('nurse.admitting.appointments.index', compact('todayAppointments'));
    }
}