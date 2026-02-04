<?php

use App\Http\Controllers\HeadNurse\ShiftScheduleController;
use App\Http\Controllers\HeadNurse\NurseController;
use Illuminate\Support\Facades\Route;

// HEAD NURSE ROUTES
Route::middleware(['auth', 'headnurse'])->prefix('nurse/headnurse')->name('nurse.headnurse.')->group(function () {

    // My Nurses
    Route::get('/nurses', [NurseController::class, 'index'])->name('nurses.index');
    Route::put('/nurses/{nurse}/schedule', [NurseController::class, 'updateSchedule'])->name('nurses.updateSchedule');

    // Shift Schedules CRUD
    Route::get('/shifts', [ShiftScheduleController::class, 'index'])->name('shifts.index');
    Route::post('/shifts', [ShiftScheduleController::class, 'store'])->name('shifts.store');
    Route::put('/shifts/{schedule}', [ShiftScheduleController::class, 'update'])->name('shifts.update');
    Route::delete('/shifts/{schedule}', [ShiftScheduleController::class, 'destroy'])->name('shifts.destroy');
});
