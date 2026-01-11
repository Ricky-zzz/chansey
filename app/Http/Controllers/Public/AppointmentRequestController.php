<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Department; // Import this!
use Illuminate\Http\Request;

class AppointmentRequestController extends Controller
{
    // Show the Landing Page
    public function create()
    {
        $departments = Department::all();
        
        return view('welcome', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'nullable|email',
            'contact_number' => 'required|string|max:20',
            'department_id' => 'required|exists:departments,id', 
            'purpose' => 'required|string|max:500',
            'preferred_date' => 'required|date|after:today', 
        ]);

        Appointment::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'department_id' => $request->department_id,
            'purpose' => $request->purpose . " (Prefers: {$request->preferred_date})",
            'status' => 'Pending',
        ]);

        return back()->with('success', 'Appointment requested successfully! Wait for confirmation via email of phone.');
    }
}