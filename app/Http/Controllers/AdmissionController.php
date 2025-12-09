<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Admission::with(['patient', 'bed.room', 'attendingPhysician']);
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('admission_number', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($subQ) use ($search) {
                      $subQ->where('last_name', 'like', "{$search}%")
                           ->orWhere('first_name', 'like', "{$search}%")
                           ->orWhere('patient_unique_id', 'like', "%{$search}%");
                  });
            });
        }

        $admissions = $query->latest('admission_date')
                            ->paginate(10)
                            ->appends(['search' => $search]);

        return view('nurse.admitting.admissions.index', compact('admissions'));
    }
    

public function show($id)
{
    $admission = Admission::with([
        // 1. Core Relations
        'patient',
        'attendingPhysician',
        'admittingClerk',
        
        // 2. Nested Relations (Bed -> Room)
        'bed.room', 
        
        // 3. Financials
        'billingInfo',
        
        // 4. Files & Who uploaded them
        'files.uploader' 
    ])->findOrFail($id);

    return view('nurse.admitting.admissions.show', compact('admission'));
}
}