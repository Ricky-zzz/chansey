<?php

namespace App\Http\Controllers\Admission;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Bed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->nurse->designation !== 'Admitting') {
            return redirect('/login')->with('error', 'Access Restricted to Admitting Staff');
        }

        $stats = [
            'Private'      => $this->getBedStats('Private'),
            'Ward'         => $this->getBedStats('Ward'), 
            'ICU'          => $this->getBedStats('ICU'),
            'ER'           => $this->getBedStats('ER'),
        ];

        $recentAdmissions = Admission::with(['patient', 'bed.room']) 
            ->orderBy('created_at', 'desc') 
            ->take(5)
            ->get();

        return view('nurse.admitting.dashboard', compact('stats', 'recentAdmissions'));
    }

    private function getBedStats($roomType)
    {

        $total = Bed::whereHas('room', function($q) use ($roomType) {
            $q->where('room_type', $roomType);
        })->count();

        $available = Bed::where('status', 'Available')
            ->whereHas('room', function($q) use ($roomType) {
                $q->where('room_type', $roomType);
            })->count();

        return [
            'available' => $available,
            'total'     => $total,
            'status_color' => $available === 0 ? 'text-error' : ($available < 3 ? 'text-warning' : 'text-success'),
            'border_color' => $available === 0 ? 'border-error' : ($available < 3 ? 'border-warning' : 'border-primary'),
            'bg_icon'      => $available === 0 ? 'bg-red-50 text-error' : 'bg-cyan-50 text-primary',
            'badge_text'   => $available === 0 ? 'Full Capacity' : 'Available',
            'badge_class'  => $available === 0 ? 'badge-error text-white' : 'badge-success text-white',
        ];
    }
}