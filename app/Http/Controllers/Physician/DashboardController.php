<?php

namespace App\Http\Controllers\Physician;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $physician = Auth::user()->physician;

        // My Active Patients
        $query = Admission::query()
            ->with(['patient', 'bed.room.station']) 
            ->where('admissions.status', 'Admitted')
            ->where('admissions.attending_physician_id', $physician->id);

        // Stats
        $myTotalPatients = (clone $query)->count();
        
        $newReferrals = (clone $query)
            ->where('created_at', '>=', now()->subHours(24))
            ->count();

        // New/Pending vs Old
        $emergencyCases = (clone $query)
            ->where('admission_type', 'Emergency')
            ->count();

        // Main List
        $myPatients = $query
            ->join('beds', 'admissions.bed_id', '=', 'beds.id')
            ->join('rooms', 'beds.room_id', '=', 'rooms.id')
            ->join('stations', 'rooms.station_id', '=', 'stations.id')
            ->orderBy('stations.station_name') 
            ->orderBy('rooms.room_number')    
            ->select('admissions.*')
            ->get();

        return view('physician.dashboard', compact(
            'physician',
            'myTotalPatients',
            'newReferrals',
            'emergencyCases',
            'myPatients'
        ));
    }
}