<?php

use App\Http\Controllers\Admission\PatientController;
use App\Http\Controllers\Admission\AdmissionController;
use App\Http\Controllers\Admission\TransferController;
use App\Http\Controllers\Admission\DashboardController as AdmissionDash;
use Illuminate\Support\Facades\Route;

//  ADMITTING NURSES 
Route::middleware(['auth'])->prefix('nurse/admitting')->name('nurse.admitting.')->group(function () {

    // Admitting Dashboard
    Route::get('/dashboard', [AdmissionDash::class, 'index'])->name('dashboard');

    // Patient index 
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');

    // Patient create form
    Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');

    // Patient store action
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');

    // View Patient Profile
    Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');

    // view patient edit form
    Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');

    // view patient update action
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');

    // Admission index
    Route::get('/admissions', [AdmissionController::class, 'index'])->name('admissions.index');

    // View Admission Profile
    Route::get('/admissions/{admission}', [AdmissionController::class, 'show'])->name('admissions.show');

    Route::get('/admissions/{patient_id}/create', [AdmissionController::class, 'create'])->name('admissions.create');

    Route::post('/admissions/{patient_id}/store', [AdmissionController::class, 'store'])->name('admissions.store');

    // admission edit form
    Route::get('/admissions/{admission}/edit', [AdmissionController::class, 'edit'])->name('admissions.edit');

    Route::put('/admissions/{admission}', [AdmissionController::class, 'update'])->name('admissions.update');

    // Transfer Requests Management
    Route::get('/transfers', [TransferController::class, 'index'])->name('transfers.index');

    Route::post('/transfers/{id}/approve', [TransferController::class, 'approve'])->name('transfers.approve');
    
    Route::post('/transfers/{id}/decline', [TransferController::class, 'decline'])->name('transfers.decline');
});
