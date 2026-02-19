<?php

use App\Http\Controllers\Clinical\IncidentController;
use Illuminate\Support\Facades\Route;

// SHARED INCIDENT ROUTES - Accessible to all authenticated nurses
Route::middleware(['auth'])->prefix('nurse/incidents')->name('nurse.incidents.')->group(function () {
    Route::get('/', [IncidentController::class, 'index'])->name('index');
    Route::get('/create', [IncidentController::class, 'create'])->name('create');
    Route::post('/', [IncidentController::class, 'store'])->name('store');
    Route::get('/{incident}', [IncidentController::class, 'show'])->name('show');
});

// HEAD NURSE - Incident Status Updates
Route::middleware(['auth'])->prefix('nurse/incidents')->name('nurse.incidents.')->group(function () {
    Route::patch('/{incident}/status-update', [IncidentController::class, 'updateStatus'])->name('status-update');
});
