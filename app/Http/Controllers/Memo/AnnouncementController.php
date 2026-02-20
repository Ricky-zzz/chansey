<?php

namespace App\Http\Controllers\Memo;

use App\Http\Controllers\Controller;
use App\Models\Memo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $me = $user->nurse;

        if (!$me) {
            abort(403, 'Nurse profile not found');
        }

        $unitId = (int)($me->getUnitId() ?? 0);
        $stationId = (int)$me->station_id;
        $roleLevel = $me->role_level;

        // Query memos using indexed pivot tables (fast!)
        $memos = Memo::with(['creator', 'targetRoles', 'targetUnits', 'targetStations'])
            ->where('created_by_user_id', '!=', $me->user_id)
            // Role must match
            ->whereHas('targetRoles', fn($q) => $q->where('role', $roleLevel))
            // Station OR Unit must match (if specified)
            ->where(function($q) use ($unitId, $stationId) {
                $q->whereHas('targetStations', fn($sq) => $sq->where('station_id', $stationId))
                  ->orWhereHas('targetUnits', fn($uq) => $uq->where('unit_id', $unitId));
            })
            ->latest()
            ->paginate(15);

        $title = 'Announcements & Memos';

        return view('nurse.announcement.index', compact('memos', 'title'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $me = $user->nurse;

        if (!$me) {
            abort(403, 'Nurse profile not found');
        }

        $unitId = (int)($me->getUnitId() ?? 0);
        $stationId = (int)$me->station_id;
        $roleLevel = $me->role_level;

        // Find the memo and verify access
        $memo = Memo::with(['creator', 'targetRoles', 'targetUnits', 'targetStations'])
            ->where('id', $id)
            ->where(function($q) use ($unitId, $stationId, $roleLevel, $me) {
                // Owner can always see their own memo
                $q->where('created_by_user_id', $me->user_id)
                  // Or it matches the targeting criteria
                  ->orWhere(function($targetCheck) use ($unitId, $stationId, $roleLevel) {
                      $targetCheck->whereHas('targetRoles', fn($rq) => $rq->where('role', $roleLevel))
                          ->where(function($locCheck) use ($unitId, $stationId) {
                              $locCheck->whereHas('targetStations', fn($sq) => $sq->where('station_id', $stationId))
                                       ->orWhereHas('targetUnits', fn($uq) => $uq->where('unit_id', $unitId));
                          });
                  });
            })
            ->firstOrFail();

        $title = 'View Announcement';

        return view('nurse.announcement.show', compact('memo', 'title'));
    }
}
