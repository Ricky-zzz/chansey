<?php

namespace App\Http\Controllers;

use App\Models\DailyTimeRecord;
use App\Models\Nurse;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
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

            // Determine if late based on shift schedule
            $shiftSchedule = $nurse->shiftSchedule;
            $status = 'Present';

            if ($shiftSchedule) {
                $scheduledStartTime = Carbon::parse($shiftSchedule->start_time);
                $actualStartTime = Carbon::parse($hangingDtr->time_in);

                // Compare only the time portion (ignore date)
                $actualTimeOfDay = $actualStartTime->format('H:i');
                $scheduledTimeOfDay = $scheduledStartTime->format('H:i');

                if ($actualTimeOfDay > $scheduledTimeOfDay) {
                    $status = 'Late';
                }
            }

            $hangingDtr->update([
                'time_out' => $end,
                'total_hours' => round($hours, 2),
                'status' => $status
            ]);

            return back()->with('success', 'Time Out Recorded. Total Hours: ' . round($hours, 2));
        }

        return back()->with('error', 'Invalid action.');
    }

    public function myDtr(Request $request)
    {
        $user = Auth::user();
        $nurse = $user->nurse;

        $currentMonth = $request->query('month', now()->month);
        $currentYear = $request->query('year', now()->year);

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

        // Get nurse's shift schedule
        $shiftSchedule = $nurse ? $nurse->shiftSchedule : null;

        // Get scheduled days for display
        $scheduledDays = [];
        if ($shiftSchedule) {
            $dayMap = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            foreach ($dayMap as $day) {
                if ($shiftSchedule->{$day}) {
                    $scheduledDays[] = ucfirst($day);
                }
            }
        }

        return view('dtr.my-dtr', [
            'dtrMap' => $dtrMap,
            'daysInMonth' => $daysInMonth,
            'startingDayOfWeek' => $startingDayOfWeek,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'monthName' => $firstDay->format('F Y'),
            'shiftSchedule' => $shiftSchedule,
            'scheduledDays' => $scheduledDays
        ]);
    }

    /**
     * Generate DTR report PDF for the authenticated nurse
     */
    public function myDtrReport(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        $user = Auth::user();
        $nurse = $user->nurse;

        if (!$nurse) {
            return back()->with('error', 'Only nurses can generate DTR reports.');
        }

        $dateFrom = Carbon::parse($request->date_from)->startOfDay();
        $dateTo = Carbon::parse($request->date_to)->endOfDay();

        return $this->generateDtrPdf($nurse, $dateFrom, $dateTo);
    }

    /**
     * Generate DTR report PDF for a specific nurse (Head Nurse)
     */
    public function nurseDtrReport(Request $request, Nurse $nurse)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        $dateFrom = Carbon::parse($request->date_from)->startOfDay();
        $dateTo = Carbon::parse($request->date_to)->endOfDay();

        return $this->generateDtrPdf($nurse, $dateFrom, $dateTo);
    }

    /**
     * Generate batch DTR report PDF for all nurses under head nurse
     */
    public function batchDtrReport(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        $me = Auth::user()->nurse;
        $dateFrom = Carbon::parse($request->date_from)->startOfDay();
        $dateTo = Carbon::parse($request->date_to)->endOfDay();

        if ($me->designation === 'Clinical') {
            $nurses = Nurse::with(['user', 'station', 'shiftSchedule'])
                ->where('station_id', $me->station_id)
                ->where('id', '!=', $me->id)
                ->orderBy('last_name')
                ->get();
        } else {
            $nurses = Nurse::with(['user', 'shiftSchedule'])
                ->where('designation', 'Admitting')
                ->where('id', '!=', $me->id)
                ->orderBy('last_name')
                ->get();
        }

        return $this->buildBatchPdf($nurses, $dateFrom, $dateTo);
    }

    /**
     * Generate DTR report PDF for a specific nurse (Admin)
     */
    public function adminNurseDtrReport(Request $request, Nurse $nurse)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        $dateFrom = Carbon::parse($request->date_from)->startOfDay();
        $dateTo = Carbon::parse($request->date_to)->endOfDay();

        return $this->generateDtrPdf($nurse, $dateFrom, $dateTo);
    }

    /**
     * Generate batch DTR report PDF for all nurses (Admin - no filtering)
     */
    public function adminBatchDtrReport(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        $dateFrom = Carbon::parse($request->date_from)->startOfDay();
        $dateTo = Carbon::parse($request->date_to)->endOfDay();

        // Get all nurses (no filtering)
        $nurses = Nurse::with(['user', 'station', 'shiftSchedule'])
            ->orderBy('last_name')
            ->get();

        return $this->buildBatchPdf($nurses, $dateFrom, $dateTo);
    }

    /**
     * Build batch DTR PDF for given nurses and date range
     */
    private function buildBatchPdf($nurses, $dateFrom, $dateTo)
    {
        // Build data for each nurse
        $allReports = [];
        foreach ($nurses as $nurse) {
            $records = DailyTimeRecord::where('user_id', $nurse->user_id)
                ->whereBetween('time_in', [$dateFrom, $dateTo])
                ->orderBy('time_in')
                ->get();

            $allReports[] = [
                'nurse' => $nurse,
                'shiftSchedule' => $nurse->shiftSchedule,
                'records' => $records,
                'summary' => $this->buildSummary($records, $nurse->shiftSchedule, $dateFrom, $dateTo),
            ];
        }

        // Format dates for view
        $dateFromFormatted = $dateFrom->format('F d, Y');
        $dateToFormatted = $dateTo->format('F d, Y');
        $dateFromFormattedShort = $dateFrom->format('M d, Y');
        $dateToFormattedShort = $dateTo->format('M d, Y');

        $pdf = Pdf::loadView('dtr.dtr-batch-report', compact('allReports', 'dateFromFormatted', 'dateToFormatted', 'dateFromFormattedShort', 'dateToFormattedShort'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('dtr-batch-report-' . $dateFrom->format('Ymd') . '-' . $dateTo->format('Ymd') . '.pdf');
    }

    /**
     * Generate a single nurse DTR PDF
     */
    private function generateDtrPdf(Nurse $nurse, Carbon $dateFrom, Carbon $dateTo)
    {
        $records = DailyTimeRecord::where('user_id', $nurse->user_id)
            ->whereBetween('time_in', [$dateFrom, $dateTo])
            ->orderBy('time_in')
            ->get();

        $shiftSchedule = $nurse->shiftSchedule;
        $summary = $this->buildSummary($records, $shiftSchedule, $dateFrom, $dateTo);

        // Format dates for view
        $dateFromFormatted = $dateFrom->format('M d, Y');
        $dateToFormatted = $dateTo->format('M d, Y');

        $pdf = Pdf::loadView('dtr.dtr-report', compact('nurse', 'records', 'shiftSchedule', 'summary', 'dateFromFormatted', 'dateToFormatted'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('dtr-report-' . $nurse->employee_id . '-' . $dateFrom->format('Ymd') . '.pdf');
    }

    /**
     * Build summary statistics for DTR records
     */
    private function buildSummary($records, $shiftSchedule, Carbon $dateFrom, Carbon $dateTo)
    {
        $totalRecords = $records->count();
        $totalHours = $records->sum('total_hours');
        $presentDays = $records->where('status', 'Present')->count();
        $lateDays = $records->where('status', 'Late')->count();
        $incompleteDays = $records->where('status', 'Incomplete')->count();

        // Calculate expected hours based on shift schedule and date range
        $expectedHours = 0;
        if ($shiftSchedule) {
            $dayMap = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            $current = $dateFrom->copy();
            while ($current->lte($dateTo)) {
                $dayName = $dayMap[$current->dayOfWeek];
                if ($shiftSchedule->{$dayName}) {
                    $start = Carbon::parse($shiftSchedule->start_time);
                    $end = Carbon::parse($shiftSchedule->end_time);
                    $expectedHours += $end->gt($start)
                        ? $start->diffInMinutes($end) / 60
                        : (1440 - $start->diffInMinutes($end)) / 60;
                }
                $current->addDay();
            }
        }

        $overtime = max(0, $totalHours - $expectedHours);
        $deficit = max(0, $expectedHours - $totalHours);

        return compact('totalRecords', 'totalHours', 'presentDays', 'lateDays', 'incompleteDays', 'expectedHours', 'overtime', 'deficit');
    }
}
