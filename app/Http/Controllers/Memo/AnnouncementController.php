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

        // Get unit: from nurse record OR from station (for Head Nurses)
        $unitId = (int)($me->getUnitId() ?? 0);
        $stationId = (int)$me->station_id;
        $roleLevel = $me->role_level;

        // Query memos/announcements relevant to this nurse
        $memos = Memo::query()
            // Don't show my own memos in inbox (if I'm a Head Nurse who created memos)
            ->where('created_by_user_id', '!=', $me->user_id)
            ->where(function($q) use ($unitId, $stationId, $roleLevel) {

                // STRICT MATCHING: All specified constraints must match

                // Constraint 1: Role must match (if specified)
                $q->where(function($roleCheck) use ($roleLevel) {
                    $roleCheck->whereJsonContains('target_roles', $roleLevel)
                              ->orWhereNull('target_roles')
                              ->orWhereJsonLength('target_roles', 0);
                })

                // Constraint 2: Unit must match (if specified)
                ->where(function($unitCheck) use ($unitId) {
                    $unitCheck->whereJsonContains('target_units', $unitId)
                              ->orWhereNull('target_units')
                              ->orWhereJsonLength('target_units', 0);
                })

                // Constraint 3: Station must match (if specified)
                ->where(function($stationCheck) use ($stationId) {
                    $stationCheck->whereJsonContains('target_stations', $stationId)
                                 ->orWhereNull('target_stations')
                                 ->orWhereJsonLength('target_stations', 0);
                });
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

        // Get unit: from nurse record OR from station (for Head Nurses)
        $unitId = (int)($me->getUnitId() ?? 0);
        $stationId = (int)$me->station_id;
        $roleLevel = $me->role_level;

        // Find the memo and verify this nurse should see it
        $memo = Memo::query()
            ->where('id', $id)
            ->where(function($q) use ($unitId, $stationId, $roleLevel, $me) {
                // Owner can always see their own memo
                $q->where('created_by_user_id', $me->user_id)
                  // Or it matches the targeting criteria
                  ->orWhere(function($targetCheck) use ($unitId, $stationId, $roleLevel) {
                      $targetCheck->where(function($roleCheck) use ($roleLevel) {
                          $roleCheck->whereJsonContains('target_roles', $roleLevel)
                                    ->orWhereNull('target_roles')
                                    ->orWhereJsonLength('target_roles', 0);
                      })
                      ->where(function($unitCheck) use ($unitId) {
                          $unitCheck->whereJsonContains('target_units', $unitId)
                                    ->orWhereNull('target_units')
                                    ->orWhereJsonLength('target_units', 0);
                      })
                      ->where(function($stationCheck) use ($stationId) {
                          $stationCheck->whereJsonContains('target_stations', $stationId)
                                       ->orWhereNull('target_stations')
                                       ->orWhereJsonLength('target_stations', 0);
                      });
                  });
            })
            ->firstOrFail();

        $title = 'View Announcement';

        return view('nurse.announcement.show', compact('memo', 'title'));
    }
}
