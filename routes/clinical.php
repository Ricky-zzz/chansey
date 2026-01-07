<?php

use App\Http\Controllers\Clinical\DashboardController as ClinicDash;
use \App\Http\Controllers\Clinical\WardController;
use App\Http\Controllers\Clinical\CarePlanController;
use App\Http\Controllers\Clinical\ClinicalLogController;
use \App\Http\Controllers\Clinical\OrderExecutionController;
use Illuminate\Support\Facades\Route;

//  Clinical NURSES 
Route::middleware(['auth'])->prefix('nurse/clinical')->name('nurse.clinical.')->group(function () {

    // Clinical Dashboard
    Route::get('/dashboard', [ClinicDash::class, 'index'])->name('dashboard');

    // WARD LIST
    Route::get('/my-ward', [WardController::class, 'index'])
        ->name('ward.index');

    // PATIENT CHART 
    Route::get('/patient/{admission}', [WardController::class, 'show'])
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
});
