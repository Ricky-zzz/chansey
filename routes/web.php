<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DTRController;
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

// DTR KIOSK (Public - No Auth Required)
Route::prefix('dtr')->name('dtr.')->group(function () {
    Route::get('/', [DTRController::class, 'kiosk'])->name('kiosk');
    Route::post('/', [DTRController::class, 'store'])->name('store');
});

// AUTH
Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth'])
    ->name('dashboard');

// DTR My Records (Authenticated)
Route::middleware(['auth'])->group(function () {
    Route::get('/my-dtr', [DTRController::class, 'myDtr'])->name('dtr.my-dtr');
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Patient Reports
    Route::get('/patient/{id}/print-report', [\App\Http\Controllers\PatientReportController::class, 'printReport'])->name('patient.print-report');
    Route::get('/admission/{admission}/report', [\App\Http\Controllers\PatientReportController::class, 'admissionReport'])->name('admission.report');

    // DTR Reports (own DTR)
    Route::post('/my-dtr/report', [DTRController::class, 'myDtrReport'])->name('dtr.my-dtr-report');

    // Admin DTR Reports
    Route::post('/admin/nurses/{nurse}/dtr-report', [DTRController::class, 'adminNurseDtrReport'])->name('admin.nurses.dtrReport');
    Route::post('/admin/nurses/batch-dtr-report', [DTRController::class, 'adminBatchDtrReport'])->name('admin.nurses.batchDtrReport');
});

// Include role-based routes
require __DIR__ . '/admitting.php';
require __DIR__ . '/clinical.php';
require __DIR__ . '/headnurse.php';
require __DIR__ . '/physician.php';
require __DIR__ . '/accountant.php';
require __DIR__ . '/memo.php';
require __DIR__ . '/incident.php';

// File viewing route
Route::middleware(['auth'])->get('/document/view/{id}', [FileController::class, 'view'])->name('document.view');
Route::middleware(['auth'])->get('/documents/{id}/view', [FileController::class, 'view']);

require __DIR__ . '/auth.php';
