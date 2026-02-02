<?php

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\AppointmentRequestController;
use App\Http\Controllers\Public\DoctorController;


// Landing Page
Route::get('/', [AppointmentRequestController::class, 'create'])->name('welcome');

// Public Booking Flow
Route::prefix('book')->name('public.')->group(function () {
    // Step 1: View doctors in a department
    Route::get('/department/{id}/doctors', [DoctorController::class, 'index'])->name('doctors.index');
    
    // Step 2: View available slots for a doctor
    Route::get('/doctor/{id}', [DoctorController::class, 'book'])->name('doctors.book');
    
    // Step 3: Submit booking
    Route::post('/appointment', [AppointmentRequestController::class, 'store'])->name('appointment.store');
    
    // Step 4: Booking success page
    Route::get('/success/{id}', [AppointmentRequestController::class, 'success'])->name('booking.success');
});

// AUTH
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->user_type === 'admin') {
        return redirect('/admin');
    }

    if ($user->user_type === 'general_service') {
        return redirect('/maintenance');
    }

    if ($user->user_type === 'pharmacist') {
        return redirect('/pharmacy');
    }

    if ($user->user_type === 'nurse') {
        if (! $user->nurse) {
            return redirect('/login')->with('error', 'Nurse profile not found.');
        }

        if ($user->nurse->designation === 'Admitting') {
            return redirect()->route('nurse.admitting.dashboard');
        }

        return redirect()->route('nurse.clinical.dashboard');
    }

    if ($user->user_type === 'physician') {
        return redirect()->route('physician.dashboard');
    }

    if ($user->user_type === 'accountant') {
        return redirect()->route('accountant.dashboard');
    }

    return redirect('/login')->with('error', 'Unauthorized user type.');
})->middleware(['auth'])->name('dashboard');

// Include role-based routes
require __DIR__ . '/admitting.php';
require __DIR__ . '/clinical.php';
require __DIR__ . '/physician.php';
require __DIR__ . '/accountant.php';

// File viewing route
Route::middleware(['auth'])->get('/document/view/{id}', [FileController::class, 'view'])->name('document.view');
Route::middleware(['auth'])->get('/documents/{id}/view', [FileController::class, 'view']);

require __DIR__ . '/auth.php';
