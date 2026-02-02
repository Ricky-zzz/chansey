<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentSlot;
use App\Models\Department;
use App\Mail\AppointmentBooked;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Mail\Mailer;

class AppointmentRequestController extends Controller
{
    public function __construct(protected Mailer $mailer)
    {
    }

    // Show the Landing Page with Departments
    public function create()
    {
        $departments = Department::withCount('physicians')->get();
        
        return view('welcome', compact('departments'));
    }

    // Book an appointment to a specific slot
    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_slot_id' => 'required|exists:appointment_slots,id',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'nullable|email',
            'contact_number' => 'required|string|max:20',
            'purpose' => 'required|string|max:500',
        ]);

        try {
            $appointment = DB::transaction(function () use ($validated) {
                // Lock the slot row for update to prevent race conditions
                $slot = AppointmentSlot::lockForUpdate()->findOrFail($validated['appointment_slot_id']);
                
                if ($slot->status !== 'Active') {
                    throw new \Exception('This slot is no longer available.');
                }
                
                // Check capacity
                $currentCount = $slot->appointments()->count();
                if ($currentCount >= $slot->capacity) {
                    throw new \Exception('fully_booked');
                }
                
                // Create the appointment
                return Appointment::create([
                    'appointment_slot_id' => $slot->id,
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'email' => $validated['email'] ?? null,
                    'contact_number' => $validated['contact_number'],
                    'purpose' => $validated['purpose'],
                    'status' => 'Booked',
                ]);
            });

            // Load relationships for email
            $appointment->load('appointmentSlot.physician.department');

            if ($appointment->email) {
                $this->mailer->to($appointment->email)->send(new AppointmentBooked($appointment));
            }

            return redirect()->route('public.booking.success', $appointment->id);

        } catch (\Exception $e) {
            if ($e->getMessage() === 'fully_booked') {
                return back()
                    ->withInput()
                    ->with('error', 'Sorry, this slot just became fully booked. Please choose another date.');
            }
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    // Show booking success page
    public function success($id)
    {
        $appointment = Appointment::with('appointmentSlot.physician.department')->findOrFail($id);
        
        return view('public.booking-success', compact('appointment'));
    }
}