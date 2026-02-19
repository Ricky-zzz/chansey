<?php

use App\Http\Controllers\Incident\IncidentController;
use Illuminate\Support\Facades\Route;

// INCIDENT ROUTES - Accessible to all authenticated nurses in their station
Route::middleware(['auth'])->prefix('nurse/incidents')->name('incident.')->group(function () {
    Route::get('/', [IncidentController::class, 'index'])->name('index');
    Route::get('/create', [IncidentController::class, 'create'])->name('create');
    Route::post('/', [IncidentController::class, 'store'])->name('store');
    Route::get('/{incident}', [IncidentController::class, 'show'])->name('show');
});

// STATUS UPDATE - Head Nurses Only
Route::middleware(['auth', 'headnurse'])->prefix('nurse/incidents')->name('incident.')->group(function () {
    Route::patch('/{incident}/status', [IncidentController::class, 'updateStatus'])->name('status-update');
});
