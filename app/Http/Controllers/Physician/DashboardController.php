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

        // Main List - Paginated (10 per page)
        $myPatients = $query
            ->leftJoin('beds', 'admissions.bed_id', '=', 'beds.id')
            ->leftJoin('rooms', 'beds.room_id', '=', 'rooms.id')
            ->leftJoin('stations', 'rooms.station_id', '=', 'stations.id')
            ->orderBy('admissions.admission_date', 'DESC')
            ->select('admissions.*')
            ->paginate(8);

        return view('physician.dashboard', compact(
            'physician',
            'myTotalPatients',
            'newReferrals',
            'emergencyCases',
            'myPatients'
        ));
    }
}
