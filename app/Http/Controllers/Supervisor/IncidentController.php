<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    /**
     * Display all incidents from supervisor's unit (all stations)
     * Merged single view with station column
     */
    public function index()
    {
        $user = Auth::user();
        $supervisor = $user->nurse;

        // Get all stations in supervisor's unit
        $stationIds = \App\Models\Station::where('unit_id', $supervisor->unit_id ?? null)
            ->pluck('id');

        // All incidents in unit (merged view)
        $incidents = Incident::whereIn('station_id', $stationIds)
            ->with(['station', 'admission.patient', 'createdBy', 'resolvedBy', 'involvedStaff'])
            ->latest('time_of_incident')
            ->paginate(20);

        // Stats
        $stats = [
            'total' => Incident::whereIn('station_id', $stationIds)->count(),
            'unresolved' => Incident::whereIn('station_id', $stationIds)->where('status', 'unresolved')->count(),
            'investigating' => Incident::whereIn('station_id', $stationIds)->where('status', 'investigating')->count(),
            'severe' => Incident::whereIn('station_id', $stationIds)
                ->whereIn('severity_level', ['High', 'Severe'])
                ->count(),
        ];

        return view('nurse.supervisor.incident.index', [
            'title' => 'Unit Incident Reports',
            'incidents' => $incidents,
            'stats' => $stats,
        ]);
    }

    /**
     * Show incident details (read-only for supervisor)
     */
    public function show(Incident $incident)
    {
        $user = Auth::user();
        $supervisor = $user->nurse;

        // Verify access: incident must be in supervisor's unit stations
        $stationIds = \App\Models\Station::where('unit_id', $supervisor->unit_id ?? null)
            ->pluck('id');

        if (!$stationIds->contains($incident->station_id)) {
            abort(403, 'Unauthorized access.');
        }

        $incident->load(['station', 'admission.patient', 'createdBy', 'resolvedBy', 'involvedStaff']);

        return view('nurse.supervisor.incident.show', [
            'title' => 'Incident Report Details',
            'incident' => $incident,
        ]);
    }
}
