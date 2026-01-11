<?php

namespace App\Http\Controllers\Clinical;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $nurse = Auth::user()->nurse;
        $station = $nurse->station;

        $query = Admission::query()
            ->with(['patient', 'bed.room', 'attendingPhysician']) 
            ->where('admissions.status', 'Admitted')
            ->where('admissions.station_id', $station->id);


        $totalPatients = (clone $query)->count();
        
        $newArrivals = (clone $query)
            ->where('created_at', '>=', now()->subHours(24))
            ->count();

        $emergencyCases = (clone $query)
            ->where('admission_type', 'Emergency')
            ->count();

        $activeAdmissions = $query
            ->leftJoin('beds', 'admissions.bed_id', '=', 'beds.id') 
            ->orderByRaw('COALESCE(beds.bed_code, "") asc')
            ->select('admissions.*') 
            ->get();

        return view('nurse.clinical.dashboard', compact(
            'nurse', 
            'station', 
            'totalPatients', 
            'newArrivals', 
            'emergencyCases', 
            'activeAdmissions'
        ));
    }
}