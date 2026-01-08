<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Billing;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
    $query = Admission::with(['patient', 'bed.room', 'attendingPhysician', 'billingInfo']) // Added billingInfo
        ->where('status', 'Ready for Discharge');

    // SEARCH LOGIC
    if ($search = $request->input('search')) {
        $query->where(function($q) use ($search) {
            // 1. Admission Number
            $q->where('admission_number', 'like', "%{$search}%")
            
            // 2. Patient Name
              ->orWhereHas('patient', function($subQ) use ($search) {
                  $subQ->where('last_name', 'like', "{$search}%")
                       ->orWhere('first_name', 'like', "{$search}%");
              });
        });
    }

    // Sort and Paginate
    $readyForBilling = $query->latest('updated_at')
        ->paginate(10)
        ->appends(['search' => $search]); // Keep search in URL

        $pendingCount = Admission::where('status', 'Ready for Discharge')->count();
        
        $collectedToday = Billing::whereDate('created_at', today())
            ->sum('amount_paid');

        $dischargedToday = Admission::where('status', 'Discharged')
            ->whereDate('updated_at', today())
            ->count();

        return view('accountant.dashboard', compact(
            'readyForBilling', 
            'pendingCount', 
            'collectedToday', 
            'dischargedToday'
        ));
    }
}