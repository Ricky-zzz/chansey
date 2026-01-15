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
    $query = Admission::with(['patient', 'bed.room', 'attendingPhysician', 'billingInfo']) 
        ->where('status', 'Ready for Discharge');

    if ($search = $request->input('search')) {
        $query->where(function($q) use ($search) {
            $q->where('admission_number', 'like', "%{$search}%")
            
              ->orWhereHas('patient', function($subQ) use ($search) {
                  $subQ->where('last_name', 'like', "{$search}%")
                       ->orWhere('first_name', 'like', "{$search}%");
              });
        });
    }

    $readyForBilling = $query->latest('updated_at')
        ->paginate(10)
        ->appends(['search' => $search]); 

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

    public function history(Request $request)
    {
        $query = Billing::with(['admission.patient'])
            ->latest('created_at');

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('receipt_number', 'like', "%{$search}%")
                  ->orWhereHas('admission.patient', function($subQ) use ($search) {
                      $subQ->where('last_name', 'like', "{$search}%")
                           ->orWhere('first_name', 'like', "{$search}%");
                  });
            });
        }

        $billings = $query->paginate(10)
            ->appends(['search' => $search]);

        return view('accountant.history.index', compact('billings'));
    }
}