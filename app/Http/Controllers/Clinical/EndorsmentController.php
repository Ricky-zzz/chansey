<?php

namespace App\Http\Controllers\Clinical;

use App\Http\Controllers\Controller;
use App\Models\Endorsment;
use App\Models\Admission;
use App\Services\EndorsmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EndorsmentController extends Controller
{
    public function __construct(private EndorsmentService $service) {}

    /**
     * Display endorsements dashboard with two views:
     * - My Created Endorsements (paginated)
     * - My Incoming Endorsements
     */
    public function index()
    {
        $user = Auth::user();
        $nurse = $user->nurse;

        // My Created Endorsements (where I'm the outgoing nurse)
        $myCreatedEndorsments = Endorsment::where('outgoing_nurse_id', $user->id)
            ->with(['admission.patient', 'station', 'incomingNurse.user', 'viewers.user'])
            ->latest('created_at')
            ->paginate(10, ['*'], 'created_page');

        // My Incoming Endorsements (where I'm the incoming nurse and it's submitted)
        $myIncomingEndorsments = Endorsment::where('incoming_nurse_id', $nurse->id)
            ->whereNotNull('submitted_at')
            ->with(['admission.patient', 'station', 'outgoingNurse', 'viewers.user'])
            ->latest('submitted_at')
            ->paginate(10, ['*'], 'incoming_page');

        return view('nurse.clinical.endorsments.index', [
            'title' => 'Endorsements',
            'myCreatedEndorsments' => $myCreatedEndorsments,
            'myIncomingEndorsments' => $myIncomingEndorsments,
        ]);
    }

    /**
     * Show form to create new endorsement for a specific admission
     */
    public function create(Admission $admission)
    {
        $user = Auth::user();
        $nurse = $user->nurse;

        // Verify the admission is in the current nurse's station
        if ($admission->station_id !== $nurse->station_id) {
            abort(403, 'You can only endorse patients in your station.');
        }

        // Load admission with related data
        $admission->load('treatmentPlan');

        // Get incoming nurses from the same station
        $incomingNurses = \App\Models\Nurse::where('station_id', $nurse->station_id)
            ->where('user_id', '!=', $user->id)
            ->with('user')
            ->get();

        // Pre-fill data from admission or treatment plan
        $prefillData = [
            'diagnosis' => $admission->treatmentPlan?->main_problem ?? $admission->chief_complaint ?? null,
            'known_allergies' => $admission->known_allergies ?? [],
            'medication_history' => $admission->medication_history ?? [],
            'past_medical_history' => $admission->past_medical_history ?? [],
        ];

        return view('nurse.clinical.endorsments.create', [
            'title' => 'Create Endorsement',
            'admission' => $admission,
            'incomingNurses' => $incomingNurses,
            'prefillData' => $prefillData,
        ]);
    }

    /**
     * Store the endorsement (as draft)
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $nurse = $user->nurse;

        $validated = $request->validate([
            'admission_id' => 'required|exists:admissions,id',
            'incoming_nurse_id' => 'required|exists:nurses,id',
            'diagnosis' => 'nullable|string|max:255',
            'current_condition' => 'nullable|string',
            'code_status' => 'required|in:Low,Moderate,High,Severe',
            // Allergies and history
            'known_allergies' => 'nullable|array',
            'known_allergies.*' => 'string',
            'medication_history' => 'nullable|array',
            'medication_history.*' => 'string',
            'past_medical_history' => 'nullable|array',
            'past_medical_history.*' => 'string',
            // Assessment
            'latest_vitals' => 'nullable|array',
            'pain_scale' => 'nullable|string',
            'iv_lines' => 'nullable|array',
            'iv_lines.*' => 'string',
            'wounds' => 'nullable|array',
            'wounds.*' => 'string',
            'labs_pending' => 'nullable|array',
            'labs_pending.*' => 'string',
            'abnormal_findings' => 'nullable|array',
            'abnormal_findings.*' => 'string',
            // Recommendations
            'upcoming_medications' => 'nullable|array',
            'upcoming_medications.*' => 'string',
            'labs_follow_up' => 'nullable|array',
            'labs_follow_up.*' => 'string',
            'monitor_instructions' => 'nullable|array',
            'monitor_instructions.*' => 'string',
            'special_precautions' => 'nullable|array',
            'special_precautions.*' => 'string',
            // Ward level
            'bed_occupancy' => 'nullable|string',
            'equipment_issues' => 'nullable|array',
            'equipment_issues.*' => 'string',
            'pending_admissions' => 'nullable|array',
            'pending_admissions.*' => 'string',
            'station_issues' => 'nullable|array',
            'station_issues.*' => 'string',
            'critical_incidents' => 'nullable|array',
            'critical_incidents.*' => 'string',
            'auto_submit' => 'nullable|boolean',
        ]);

        $admission = Admission::findOrFail($validated['admission_id']);

        if ($admission->station_id !== $nurse->station_id) {
            abort(403, 'You can only endorse patients in your station.');
        }

        // Create the endorsement
        $endorsment = $this->service->createEndorsement(
            stationId: $nurse->station_id,
            admissionId: $admission->id,
            outgoingNurseId: $user->id,
            incomingNurseId: $validated['incoming_nurse_id'],
            data: $validated
        );

        // Auto-submit if requested
        if ($validated['auto_submit'] ?? false) {
            $this->service->submitEndorsement($endorsment->id, $user->id);
            return redirect()->route('nurse.clinical.endorsments.show', $endorsment)
                ->with('success', 'Endorsement created and submitted successfully!');
        }

        return redirect()->route('nurse.clinical.endorsments.show', $endorsment)
            ->with('success', 'Endorsement saved as draft. Submit when ready.');
    }

    /**
     * Show specific endorsement
     */
    public function show(Endorsment $endorsment)
    {
        $user = Auth::user();
        $nurse = $user->nurse;

        // Verify access: creator, incoming nurse, or station head nurse
        if ($endorsment->outgoing_nurse_id !== $user->id &&
            $endorsment->incoming_nurse_id !== $nurse->id &&
            $endorsment->station_id !== $nurse->station_id) {
            abort(403, 'Unauthorized access.');
        }

        // Log view if incoming nurse or head nurse (station supervisor) viewing it
        if ($endorsment->incoming_nurse_id === $nurse->id || $endorsment->station_id === $nurse->station_id) {
            \App\Models\EndorsmentViewer::firstOrCreate([
                'endorsment_id' => $endorsment->id,
                'user_id' => $user->id,
            ], [
                'viewed_at' => now(),
            ]);
        }

        $endorsment->load([
            'admission.patient',
            'station',
            'outgoingNurse',
            'incomingNurse.user',
            'submittedBy',
            'notes.user',
            'viewers.user',
        ]);

        $isCreator = $endorsment->outgoing_nurse_id === $user->id;
        $isIncoming = $endorsment->incoming_nurse_id === $nurse->id;

        return view('nurse.clinical.endorsments.show', [
            'title' => 'View Endorsement',
            'endorsment' => $endorsment,
            'isCreator' => $isCreator,
            'isIncoming' => $isIncoming,
        ]);
    }

    /**
     * Submit (lock) the endorsement
     */
    public function submit(Request $request, Endorsment $endorsment)
    {
        $user = Auth::user();

        if ($endorsment->outgoing_nurse_id !== $user->id) {
            abort(403, 'Only the creator can submit this endorsement.');
        }

        $this->service->submitEndorsement($endorsment->id, $user->id);

        return redirect()->route('nurse.clinical.endorsments.show', $endorsment)
            ->with('success', 'Endorsement submitted successfully!');
    }

    /**
     * Add a note/amendment to endorsement
     */
    public function storeNote(Request $request, Endorsment $endorsment)
    {
        $user = Auth::user();
        $nurse = $user->nurse;

        // Verify access
        if ($endorsment->outgoing_nurse_id !== $user->id &&
            $endorsment->incoming_nurse_id !== $nurse->id) {
            abort(403, 'Unauthorized access.');
        }

        if (!$endorsment->isLocked()) {
            return redirect()->route('nurse.clinical.endorsments.show', $endorsment)
                ->with('error', 'Notes can only be added to submitted endorsements.');
        }

        $validated = $request->validate([
            'note' => 'required|string|max:1000',
        ]);

        // Determine note type
        $noteType = $endorsment->outgoing_nurse_id === $user->id ? 'correction' : 'observation';

        $this->service->addNote(
            endorsementId: $endorsment->id,
            userId: $user->id,
            note: $validated['note'],
            noteType: $noteType
        );

        return redirect()->route('nurse.clinical.endorsments.show', $endorsment)
            ->with('success', 'Note added successfully!');
    }
}
