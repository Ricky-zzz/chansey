<?php

namespace App\Http\Controllers\HeadNurse;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateIncidentStatusRequest;
use App\Models\Incident;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    /**
     * Display incidents for head nurse's station
     * Three tabs: All, Reported by Me, I'm Involved
     */
    public function index()
    {
        $user = Auth::user();
        $headNurse = $user->nurse;

        // All incidents in station
        $allIncidents = Incident::where('station_id', $headNurse->station_id)
            ->with(['station', 'admission.patient', 'createdBy', 'resolvedBy', 'involvedStaff'])
            ->latest('time_of_incident')
            ->paginate(15, ['*'], 'all_page');

        // Incidents reported by me
        $myReports = Incident::where('station_id', $headNurse->station_id)
            ->where('created_by_id', $user->id)
            ->with(['station', 'admission.patient', 'resolvedBy', 'involvedStaff'])
            ->latest('time_of_incident')
            ->paginate(15, ['*'], 'reports_page');

        // Incidents I'm involved in
        $myInvolvement = Incident::where('station_id', $headNurse->station_id)
            ->whereHas('involvedStaff', function ($query) use ($user) {
                $query->where('staff_id', $user->id);
            })
            ->with(['station', 'admission.patient', 'createdBy', 'resolvedBy', 'involvedStaff'])
            ->latest('time_of_incident')
            ->paginate(15, ['*'], 'involved_page');

        // Stats
        $stats = [
            'total' => Incident::where('station_id', $headNurse->station_id)->count(),
            'unresolved' => Incident::where('station_id', $headNurse->station_id)->where('status', 'unresolved')->count(),
            'investigating' => Incident::where('station_id', $headNurse->station_id)->where('status', 'investigating')->count(),
            'severeCount' => Incident::where('station_id', $headNurse->station_id)
                ->whereIn('severity_level', ['High', 'Severe'])
                ->count(),
        ];

        return view('nurse.headnurse.incident.index', [
            'title' => 'Station Incident Reports',
            'allIncidents' => $allIncidents,
            'myReports' => $myReports,
            'myInvolvement' => $myInvolvement,
            'totalCount' => $stats['total'],
            'unresolvedCount' => $stats['unresolved'],
            'investigatingCount' => $stats['investigating'],
            'severeCount' => $stats['severeCount'],
            'myInvolvementCount' => $myInvolvement->total(),
            'station' => $headNurse->station,
        ]);
    }

    /**
     * Show incident details and allow status update
     */
    public function show(Incident $incident)
    {
        $user = Auth::user();
        $headNurse = $user->nurse;

        if ($incident->station_id !== $headNurse->station_id) {
            abort(403, 'Unauthorized access.');
        }

        $incident->load(['station', 'admission.patient', 'createdBy', 'resolvedBy', 'involvedStaff']);

        return view('nurse.headnurse.incident.show', [
            'title' => 'Incident Report Details',
            'incident' => $incident,
        ]);
    }

    /**
     * Update incident status (unresolved -> investigating -> resolved)
     */
    public function updateStatus(UpdateIncidentStatusRequest $request, Incident $incident)
    {
        $user = Auth::user();
        $headNurse = $user->nurse;

        if ($incident->station_id !== $headNurse->station_id) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validated();

        $incident->update([
            'status' => $validated['status'],
            'resolved_by_id' => $user->id,
            'resolved_at' => $validated['status'] === 'resolved' ? now() : $incident->resolved_at,
        ]);

        return redirect()->route('nurse.headnurse.incident.show', $incident)
            ->with('success', 'Incident status updated successfully!');
    }
}
