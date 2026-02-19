<?php

namespace App\Http\Controllers\Clinical;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIncidentRequest;
use App\Http\Requests\UpdateIncidentStatusRequest;
use App\Models\Incident;
use App\Models\Admission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    /**
     * Display incidents for the nurse's station
     * Three tabs: All, Reported by Me, I'm Involved
     */
    public function index()
    {
        $user = Auth::user();
        $nurse = $user->nurse;

        // All incidents in station
        $allIncidents = Incident::where('station_id', $nurse->station_id)
            ->with(['station', 'admission.patient', 'createdBy', 'resolvedBy', 'involvedStaff'])
            ->latest('time_of_incident')
            ->paginate(15, ['*'], 'all_page');

        // Incidents reported by me
        $myReports = Incident::where('station_id', $nurse->station_id)
            ->where('created_by_id', $user->id)
            ->with(['station', 'admission.patient', 'resolvedBy', 'involvedStaff'])
            ->latest('time_of_incident')
            ->paginate(15, ['*'], 'reports_page');

        // Incidents I'm involved in (via pivot)
        $myInvolvement = Incident::where('station_id', $nurse->station_id)
            ->whereHas('involvedStaff', function ($query) use ($user) {
                $query->where('staff_id', $user->id);
            })
            ->with(['station', 'admission.patient', 'createdBy', 'revolvedBy', 'involvedStaff'])
            ->latest('time_of_incident')
            ->paginate(15, ['*'], 'involved_page');

        // Stats
        $stats = [
            'total' => Incident::where('station_id', $nurse->station_id)->count(),
            'unresolved' => Incident::where('station_id', $nurse->station_id)->where('status', 'unresolved')->count(),
            'investigating' => Incident::where('station_id', $nurse->station_id)->where('status', 'investigating')->count(),
            'myInvolvement' => $myInvolvement->total(),
        ];

        return view('nurse.incident.index', [
            'title' => 'Incident Reports',
            'allIncidents' => $allIncidents,
            'myReports' => $myReports,
            'myInvolvement' => $myInvolvement,
            'stats' => $stats,
            'station' => $nurse->station,
        ]);
    }

    /**
     * Show form to create new incident report
     */
    public function create()
    {
        $user = Auth::user();
        $nurse = $user->nurse;

        // Get admissions in the station
        $admissions = Admission::where('station_id', $nurse->station_id)
            ->with('patient')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get staff in the station for checkboxes
        $staffInStation = User::whereHas('nurse', function ($query) use ($nurse) {
            $query->where('station_id', $nurse->station_id);
        })->with('nurse')->get();

        return view('nurse.incident.create', [
            'title' => 'Report Incident',
            'admissions' => $admissions,
            'staffInStation' => $staffInStation,
        ]);
    }

    /**
     * Store the incident report
     */
    public function store(StoreIncidentRequest $request)
    {
        $user = Auth::user();
        $nurse = $user->nurse;

        $validated = $request->validated();

        // Create incident
        $incident = Incident::create([
            'station_id' => $nurse->station_id,
            'admission_id' => $validated['admission_id'] ?? null,
            'created_by_id' => $user->id,
            'time_of_incident' => $validated['time_of_incident'],
            'time_reported' => now(),
            'location_details' => $validated['location_details'] ?? null,
            'incident_category' => $validated['incident_category'],
            'severity_level' => $validated['severity_level'],
            'narrative' => $validated['narrative'] ?? null,
            'what_happened' => $validated['what_happened'] ?? null,
            'how_discovered' => $validated['how_discovered'] ?? null,
            'action_taken' => $validated['action_taken'] ?? null,
            'injury' => $validated['injury'] ?? false,
            'injury_type' => $validated['injury_type'] ?? null,
            'vitals' => $validated['vitals'] ?? null,
            'doctor_notified' => $validated['doctor_notified'] ?? false,
            'family_notified' => $validated['family_notified'] ?? false,
            'root_cause' => $validated['root_cause'] ?? null,
            'follow_up_actions' => $validated['follow_up_actions'] ?? null,
            'follow_up_instructions' => $validated['follow_up_instructions'] ?? null,
            'status' => 'unresolved',
        ]);

        // Add involved staff
        if (!empty($validated['involved_staff'])) {
            $incident->involvedStaff()->attach($validated['involved_staff'], [
                'role_in_incident' => 'involved',
            ]);
        }

        return redirect()->route('nurse.incident.show', $incident)
            ->with('success', 'Incident report created successfully!');
    }

    /**
     * Show incident details (read-only for staff)
     */
    public function show(Incident $incident)
    {
        $user = Auth::user();
        $nurse = $user->nurse;

        // Verify access: creator, involved staff, or head nurse
        if ($incident->station_id !== $nurse->station_id) {
            abort(403, 'Unauthorized access.');
        }

        $incident->load(['station', 'admission.patient', 'createdBy', 'resolvedBy', 'involvedStaff']);

        $isCreator = $incident->created_by_id === $user->id;
        $isInvolved = $incident->isStaffInvolved($user->id);

        return view('nurse.incident.show', [
            'title' => 'Incident Report Details',
            'incident' => $incident,
            'isCreator' => $isCreator,
            'isInvolved' => $isInvolved,
        ]);
    }
}
