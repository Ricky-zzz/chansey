<?php

namespace App\Http\Controllers\HeadNurse;

use App\Http\Controllers\Controller;
use App\Models\Endorsment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EndorsmentController extends Controller
{
    /**
     * Display all endorsements for the head nurse's station
     */
    public function index()
    {
        $user = Auth::user();
        $headNurse = $user->nurse;

        // Get base query for stats calculation
        $baseQuery = Endorsment::where('station_id', $headNurse->station_id);

        // Calculate stats from full query
        $allEndorsments = $baseQuery
            ->with([
                'admission.patient',
                'station',
                'outgoingNurse',
                'incomingNurse.user',
                'submittedBy',
                'notes',
                'viewers'
            ])
            ->get();

        $stats = [
            'total' => $allEndorsments->count(),
            'submitted' => $allEndorsments->where('submitted_at', '!=', null)->count(),
            'drafts' => $allEndorsments->where('submitted_at', null)->count(),
            'highSevere' => $allEndorsments->whereIn('code_status', ['High', 'Severe'])->count(),
        ];

        // Get paginated results
        $endorsments = $baseQuery
            ->with([
                'admission.patient',
                'station',
                'outgoingNurse',
                'incomingNurse.user',
                'submittedBy',
                'notes',
                'viewers'
            ])
            ->latest('submitted_at')
            ->paginate(15);

        return view('nurse.headnurse.endorsments.index', [
            'title' => 'Station Endorsements',
            'endorsments' => $endorsments,
            'station' => $headNurse->station,
            'stats' => $stats,
        ]);
    }
}
