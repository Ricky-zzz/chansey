<?php

use App\Http\Controllers\Clinical\DashboardController as ClinicDash;
use \App\Http\Controllers\Clinical\WardController;
use App\Http\Controllers\Clinical\CarePlanController;
use App\Http\Controllers\Clinical\ClinicalLogController;
use \App\Http\Controllers\Clinical\OrderExecutionController;
use App\Http\Controllers\Clinical\MyTaskController;
use App\Http\Controllers\Clinical\PatientLoadController;
use App\Http\Controllers\Clinical\EndorsmentController;
use Illuminate\Support\Facades\Route;

//  Clinical NURSES
Route::middleware(['auth'])->prefix('nurse/clinical')->name('nurse.clinical.')->group(function () {

    // Clinical Dashboard
    Route::get('/dashboard', [ClinicDash::class, 'index'])->name('dashboard');

    // WARD LIST
    Route::get('/my-ward', [WardController::class, 'index'])
        ->name('ward.index');

    // PATIENT LOAD (My Assigned Patients)
    Route::get('/my-patient-load', [PatientLoadController::class, 'index'])->name('patient-load.index');
    Route::get('/patient-loads/{patientLoad}/details', [PatientLoadController::class, 'getAssignmentDetails'])->name('patient-load.details');

    // PATIENT CHART
    Route::get('/patient/{id}', [WardController::class, 'show'])
        ->name('ward.show');

    // NURSING CARE PLAN ROUTES
    Route::get('/patient/{admission}/care-plan', [CarePlanController::class, 'edit'])->name('care-plan.edit');
    Route::put('/patient/{admission}/care-plan', [CarePlanController::class, 'update'])->name('care-plan.update');

    // CLINICAL LOG add
    Route::post('/patient/{admission}/logs', [ClinicalLogController::class, 'store'])->name('logs.store');

    // Upload Lab Result
    Route::post('/orders/upload-result', [OrderExecutionController::class, 'uploadLabResult'])
        ->name('orders.upload_result');

    // Request Transfer
    Route::post('/orders/transfer', [OrderExecutionController::class, 'requestTransfer'])
        ->name('orders.transfer');

    // Charge Supply Item
    Route::post('/supplies/charge', [\App\Http\Controllers\Clinical\SupplyController::class, 'store'])
    ->name('supplies.store');

    // ENDORSEMENTS
    Route::get('/endorsments', [EndorsmentController::class, 'index'])->name('endorsments.index');
    Route::get('/endorsments/create/{admission}', [EndorsmentController::class, 'create'])->name('endorsments.create');
    Route::post('/endorsments', [EndorsmentController::class, 'store'])->name('endorsments.store');
    Route::get('/endorsments/{endorsment}', [EndorsmentController::class, 'show'])->name('endorsments.show');
    Route::post('/endorsments/{endorsment}/submit', [EndorsmentController::class, 'submit'])->name('endorsments.submit');
    Route::post('/endorsments/{endorsment}/notes', [EndorsmentController::class, 'storeNote'])->name('endorsments.notes.store');
});

// MY TASKS (for all nurses)
Route::middleware(['auth'])->prefix('nurse')->name('nurse.')->group(function () {
    Route::get('/my-tasks', [MyTaskController::class, 'index'])->name('mytasks.index');
    Route::patch('/my-tasks/{task}/done', [MyTaskController::class, 'markDone'])->name('mytasks.markDone');
    Route::patch('/my-tasks/{task}/in-progress', [MyTaskController::class, 'markInProgress'])->name('mytasks.markInProgress');
});
