<?php

use App\Http\Controllers\Physician\DashboardController as PhysicianDash;
use App\Http\Controllers\Physician\OrderController;
use App\Http\Controllers\Physician\TreatmentPlanController;
use App\Http\Controllers\Physician\MyPatientController;
use Illuminate\Support\Facades\Route;

//  Physicians
Route::middleware(['auth'])->prefix('physician')->name('physician.')->group(function () {

    // Physician Dashboard
    Route::get('/dashboard', [PhysicianDash::class, 'index'])->name('dashboard');

    // My Patients - Standard CRUD Resource (index & show only)
    Route::resource('mypatients', MyPatientController::class)->only(['index', 'show']);

    // Orders - Standard CRUD Resource (store & destroy only)
    Route::resource('orders', OrderController::class)->only(['store', 'destroy']);
    
    // Custom order action
    Route::patch('/orders/{order}/discontinue', [OrderController::class, 'discontinue'])
        ->name('orders.discontinue');

    // Treatment Plan Routes
    Route::get('/admission/{id}/plan', [TreatmentPlanController::class, 'edit'])
        ->name('treatment-plan.edit');

    Route::get('/admission/{id}/plan/create', [TreatmentPlanController::class, 'edit'])
        ->name('treatment-plan.create');

    Route::put('/admission/{id}/plan', [TreatmentPlanController::class, 'update'])
        ->name('treatment-plan.update');
});
