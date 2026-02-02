<?php

use App\Http\Controllers\Physician\DashboardController as PhysicianDash;
use App\Http\Controllers\Physician\OrderController;
use App\Http\Controllers\Physician\TreatmentPlanController;
use App\Http\Controllers\Physician\MyPatientController;
use App\Http\Controllers\Physician\AppointmentController;
use App\Http\Controllers\Physician\SlotController;
use Illuminate\Support\Facades\Route;

//  Physicians
Route::middleware(['auth'])->prefix('physician')->name('physician.')->group(function () {

    // Physician Dashboard
    Route::get('/dashboard', [PhysicianDash::class, 'index'])->name('dashboard');

    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');

    // Appointment Slots Management
    Route::get('/slots', [SlotController::class, 'index'])->name('slots.index');
    Route::post('/slots', [SlotController::class, 'store'])->name('slots.store');
    Route::get('/slots/{id}', [SlotController::class, 'show'])->name('slots.show');
    Route::patch('/slots/{id}/cancel', [SlotController::class, 'cancel'])->name('slots.cancel');
    Route::delete('/slots/{id}', [SlotController::class, 'destroy'])->name('slots.destroy');

    // My Patients - Standard CRUD Resource 
    Route::resource('mypatients', MyPatientController::class)->only(['index', 'show'])->parameter('mypatients', 'id');

    // Orders - Standard CRUD Resource 
    Route::resource('orders', OrderController::class)->only(['store', 'destroy']);

    Route::post('/appointments/{id}/approve', [AppointmentController::class, 'approve'])
        ->name('appointments.approve');
    
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
