<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\AppointmentSlot;
use App\Models\Department;
use App\Models\Physician;

class DoctorController extends Controller
{
    /**
     * Show all doctors in a department.
     */
    public function index($department_id)
    {
        $department = Department::findOrFail($department_id);
        
        $doctors = Physician::where('department_id', $department_id)
            ->with('user')
            ->withCount(['appointmentSlots as available_slots_count' => function ($query) {
                $query->where('date', '>=', today())
                      ->where('status', 'Active');
            }])
            ->get();

        return view('public.doctors.index', compact('doctors', 'department'));
    }

    /**
     * Show available slots for a specific doctor.
     */
    public function book($doctor_id)
    {
        $doctor = Physician::with(['department', 'user'])->findOrFail($doctor_id);
        
        // Get future active slots that are not full
        $slots = AppointmentSlot::where('physician_id', $doctor_id)
            ->withCount('appointments')
            ->where('date', '>=', today())
            ->where('status', 'Active')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->filter(function ($slot) {
                return $slot->appointments_count < $slot->capacity;
            })
            ->values(); // Re-index after filtering

        return view('public.doctors.book', compact('doctor', 'slots'));
    }
}
