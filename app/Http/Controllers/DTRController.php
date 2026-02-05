<?php

namespace App\Http\Controllers;

use App\Models\DailyTimeRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DTRController extends Controller
{
    /**
     * Show the DTR Kiosk form (Public)
     */
    public function kiosk()
    {
        return view('dtr.kiosk');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'badge_id' => 'required|string',
            'password' => 'required|string',
            'action' => 'required|in:time_in,time_out'
        ]);

        $user = User::where('badge_id', $validated['badge_id'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return back()->with('error', 'Invalid badge ID or password.');
        }

        $nurse = $user->nurse;

        if (!$nurse) {
            return back()->with('error', 'Only nurses can use the time clock system.');
        }

        if (!$nurse->shift_schedule_id) {
            return back()->with('error', 'You have no assigned schedule. Contact Head Nurse.');
        }

        $hangingDtr = DailyTimeRecord::where('user_id', $user->id)
            ->whereNull('time_out')
            ->first();

        if ($validated['action'] === 'time_in') {

            if ($hangingDtr) {
                $hangingDtr->update([
                    'status' => 'Incomplete',
                    'time_out' => $hangingDtr->time_in,
                    'total_hours' => 0
                ]);
            }

            DailyTimeRecord::create([
                'user_id' => $user->id,
                'time_in' => now(),
                'status' => 'Ongoing'
            ]);

            return back()->with('success', 'Time In Recorded: ' . now()->format('h:i A'));
        }

        if ($validated['action'] === 'time_out') {
            if (!$hangingDtr) {
                return back()->with('error', 'No active shift found. Please Time In first.');
            }

            $start = Carbon::parse($hangingDtr->time_in);
            $end = now();
            $hours = $start->diffInMinutes($end) / 60;

            $hangingDtr->update([
                'time_out' => $end,
                'total_hours' => round($hours, 2),
                'status' => 'Present'
            ]);

            return back()->with('success', 'Time Out Recorded. Total Hours: ' . round($hours, 2));
        }

        return back()->with('error', 'Invalid action.');
    }

    public function myDtr(Request $request)
    {
        $user = Auth::user();

        // Get month and year from request, default to current
        $currentMonth = $request->query('month', now()->month);
        $currentYear = $request->query('year', now()->year);

        // Validate month and year
        $currentMonth = max(1, min(12, (int)$currentMonth));
        $currentYear = max(now()->year - 2, min(now()->year, (int)$currentYear));

        $records = DailyTimeRecord::where('user_id', $user->id)
            ->whereYear('time_in', $currentYear)
            ->whereMonth('time_in', $currentMonth)
            ->orderBy('time_in')
            ->get();

        $dtrMap = [];
        foreach ($records as $record) {
            $date = Carbon::parse($record->time_in)->format('Y-m-d');
            $dtrMap[$date] = $record;
        }

        $firstDay = Carbon::createFromDate($currentYear, $currentMonth, 1);
        $lastDay = $firstDay->copy()->endOfMonth();
        $daysInMonth = $lastDay->day;
        $startingDayOfWeek = $firstDay->dayOfWeek;

        return view('dtr.my-dtr', [
            'dtrMap' => $dtrMap,
            'daysInMonth' => $daysInMonth,
            'startingDayOfWeek' => $startingDayOfWeek,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'monthName' => $firstDay->format('F Y')
        ]);
    }
}
