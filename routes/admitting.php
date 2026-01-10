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

    // Patients - Standard CRUD Resource
    Route::resource('patients', PatientController::class);

    // Clearance List for Discharge
    Route::get('/admissions/clearance', [AdmissionController::class, 'clearanceList'])
    ->name('admissions.clearance');

    // Admissions 
    Route::resource('admissions', AdmissionController::class)->only(['index', 'show', 'edit', 'update']);
    Route::get('/admissions/{patient_id}/create', [AdmissionController::class, 'create'])->name('admissions.create');
    Route::post('/admissions/{patient_id}/store', [AdmissionController::class, 'store'])->name('admissions.store');

    // Transfer Requests Management 
    Route::get('/transfers', [TransferController::class, 'index'])->name('transfers.index');
    Route::post('/transfers/{id}/approve', [TransferController::class, 'approve'])->name('transfers.approve');
    Route::post('/transfers/{id}/decline', [TransferController::class, 'decline'])->name('transfers.decline');

    // Discharge Admission
    Route::post('/admissions/{id}/discharge', [AdmissionController::class, 'discharge'])
    ->name('discharge');
});
