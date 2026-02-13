<?php

namespace App\Http\Controllers\HeadNurse;

use App\Http\Controllers\Controller;
use App\Models\Nurse;
use App\Models\NurseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FloaterController extends Controller
{
    public function index()
    {
        $nurse = Auth::user()->nurse;
        $stationId = $nurse->station_id;

        // Get the Floating Nurse type
        $floatingNurseType = NurseType::where('name', 'Floating Nurse')->first();

        if (!$floatingNurseType) {
            return redirect()->back()->with('error', 'Floating Nurse type not configured.');
        }

        // Available Pool: Floaters with no station assigned
        $availableFloaters = Nurse::where('nurse_type_id', $floatingNurseType->id)
            ->whereNull('station_id')
            ->where('status', 'Active')
            ->with('user')
            ->paginate(10, ['*'], 'available_page');

        // My Recruits: Floaters assigned to current user's station
        $myRecruitedFloaters = Nurse::where('nurse_type_id', $floatingNurseType->id)
            ->where('station_id', $stationId)
            ->where('status', 'Active')
            ->with(['user', 'shiftSchedule'])
            ->paginate(10, ['*'], 'recruited_page');

        return view('nurse.headnurse.floaters.index', [
            'title' => 'Floating Nurse Management',
            'availableFloaters' => $availableFloaters,
            'myRecruitedFloaters' => $myRecruitedFloaters,
        ]);
    }

    public function recruit($nurse_id)
    {
        $currentNurse = Auth::user()->nurse;
        $stationId = $currentNurse->station_id;

        $floater = Nurse::findOrFail($nurse_id);

        // Verify this is a floating nurse
        $floatingNurseType = NurseType::where('name', 'Floating Nurse')->first();
        if ($floater->nurse_type_id !== $floatingNurseType->id) {
            return redirect()->back()->with('error', 'This nurse is not a floating nurse.');
        }

        // Verify floater is available (not already assigned)
        if ($floater->station_id !== null) {
            return redirect()->back()->with('error', 'This nurse is already assigned to a station.');
        }

        // Recruit to station
        $floater->update([
            'station_id' => $stationId,
        ]);

        return redirect()->route('nurse.headnurse.floaters.index')
            ->with('success', 'Nurse recruited to your station successfully.');
    }

    public function release($nurse_id)
    {
        $currentNurse = Auth::user()->nurse;
        $stationId = $currentNurse->station_id;

        $floater = Nurse::findOrFail($nurse_id);

        // Verify this floater belongs to current user's station
        if ($floater->station_id !== $stationId) {
            abort(403, 'This nurse is not assigned to your station.');
        }

        // Verify this is a floating nurse
        $floatingNurseType = NurseType::where('name', 'Floating Nurse')->first();
        if ($floater->nurse_type_id !== $floatingNurseType->id) {
            return redirect()->back()->with('error', 'This nurse is not a floating nurse.');
        }

        // Release back to pool
        $floater->update([
            'station_id' => null,
            'shift_schedule_id' => null,
        ]);

        return redirect()->route('nurse.headnurse.floaters.index')
            ->with('success', 'Nurse returned to pool successfully.');
    }
}
