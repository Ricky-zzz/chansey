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

    // my patient list
    Route::get('/mypatients', [MyPatientController::class, 'index'])->name('patients.index');

    // patient chart view
    Route::get('/mypatients/{admission}', [MyPatientController::class, 'show'])->name('patients.show');

    // patient order make
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // discontinue order
    Route::patch('/orders/{order}/discontinue', [OrderController::class, 'discontinue'])
        ->name('orders.discontinue');

    // delete order
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Treatment Plan Routes
    Route::get('/admission/{id}/plan', [TreatmentPlanController::class, 'edit'])
        ->name('treatment-plan.edit');

    Route::get('/admission/{id}/plan/create', [TreatmentPlanController::class, 'edit'])
        ->name('treatment-plan.create');

    Route::put('/admission/{id}/plan', [TreatmentPlanController::class, 'update'])
        ->name('treatment-plan.update');
});
