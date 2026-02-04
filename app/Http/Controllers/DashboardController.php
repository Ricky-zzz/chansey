<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        $user = Auth::user();

        return match ($user->user_type) {
            'admin' => redirect('/admin'),
            'general_service' => redirect('/maintenance'),
            'pharmacist' => redirect('/pharmacy'),
            'nurse' => $this->redirectNurse($user),
            'physician' => redirect()->route('physician.dashboard'),
            'accountant' => redirect()->route('accountant.dashboard'),
            default => redirect('/login')->with('error', 'Unauthorized user type.'),
        };
    }

    private function redirectNurse($user): RedirectResponse
    {
        if (!$user->nurse) {
            return redirect('/login')->with('error', 'Nurse profile not found.');
        }

        return $user->nurse->designation === 'Admitting'
            ? redirect()->route('nurse.admitting.dashboard')
            : redirect()->route('nurse.clinical.dashboard');
    }
}
