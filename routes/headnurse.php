<?php

use App\Http\Controllers\DTRController;
use App\Http\Controllers\HeadNurse\ShiftScheduleController;
use App\Http\Controllers\HeadNurse\DateScheduleController;
use App\Http\Controllers\HeadNurse\NurseController;
use App\Http\Controllers\HeadNurse\MemoController;
use App\Http\Controllers\HeadNurse\StationTaskController;
use App\Http\Controllers\HeadNurse\FloaterController;
use App\Http\Controllers\HeadNurse\PatientLoadController;
use Illuminate\Support\Facades\Route;

// HEAD NURSE ROUTES
Route::middleware(['auth', 'headnurse'])->prefix('nurse/headnurse')->name('nurse.headnurse.')->group(function () {

    // My Nurses
    Route::get('/nurses', [NurseController::class, 'index'])->name('nurses.index');
    Route::get('/nurses/{nurse}/date-schedules', [NurseController::class, 'getNurseDateSchedules'])->name('nurses.getDateSchedules');
    Route::get('/nurses/scheduled', [NurseController::class, 'getScheduledNurses'])->name('nurses.getScheduled');
    Route::put('/nurses/{nurse}/schedule', [NurseController::class, 'updateSchedule'])->name('nurses.updateSchedule');

    // DTR Reports
    Route::post('/nurses/{nurse}/dtr-report', [DTRController::class, 'nurseDtrReport'])->name('nurses.dtrReport');
    Route::post('/nurses/batch-dtr-report', [DTRController::class, 'batchDtrReport'])->name('nurses.batchDtrReport');

    // Shift Schedules CRUD - COMMENTED OUT (now using date-specific scheduling)
    // Route::get('/shifts', [ShiftScheduleController::class, 'index'])->name('shifts.index');
    // Route::post('/shifts', [ShiftScheduleController::class, 'store'])->name('shifts.store');
    // Route::put('/shifts/{schedule}', [ShiftScheduleController::class, 'update'])->name('shifts.update');
    // Route::delete('/shifts/{schedule}', [ShiftScheduleController::class, 'destroy'])->name('shifts.destroy');

    // Date Schedules CRUD (Date-Specific Nurse Scheduling)
    Route::post('/date-schedules', [DateScheduleController::class, 'store'])->name('date-schedules.store');
    Route::put('/date-schedules/{dateSchedule}', [DateScheduleController::class, 'update'])->name('date-schedules.update');
    Route::delete('/date-schedules/{dateSchedule}', [DateScheduleController::class, 'destroy'])->name('date-schedules.destroy');

    // Patient Load CRUD (Patient-Nurse Assignments)
    Route::post('/patient-loads', [PatientLoadController::class, 'store'])->name('patient-loads.store');
    Route::put('/patient-loads/{patientLoad}', [PatientLoadController::class, 'update'])->name('patient-loads.update');
    Route::delete('/patient-loads/{patientLoad}', [PatientLoadController::class, 'destroy'])->name('patient-loads.destroy');
    Route::get('/patients/{patient}/nurses', [PatientLoadController::class, 'getPatientNurses'])->name('patient-loads.getNurses');
    Route::get('/nurses/{nurse}/patients', [PatientLoadController::class, 'getNursePatients'])->name('patient-loads.getPatients');

    // Memos CRUD
    Route::get('/memos', [MemoController::class, 'index'])->name('memos.index');
    Route::get('/memos/create', [MemoController::class, 'create'])->name('memos.create');
    Route::post('/memos', [MemoController::class, 'store'])->name('memos.store');
    Route::get('/memos/{memo}', [MemoController::class, 'show'])->name('memos.show');
    Route::get('/memos/{memo}/edit', [MemoController::class, 'edit'])->name('memos.edit');
    Route::put('/memos/{memo}', [MemoController::class, 'update'])->name('memos.update');
    Route::delete('/memos/{memo}', [MemoController::class, 'destroy'])->name('memos.destroy');

    // Station Tasks CRUD
    Route::get('/tasks', [StationTaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [StationTaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{task}', [StationTaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [StationTaskController::class, 'destroy'])->name('tasks.destroy');

    // Floating Nurse Management
    Route::get('/floaters', [FloaterController::class, 'index'])->name('floaters.index');
    Route::post('/floaters/{nurse}/recruit', [FloaterController::class, 'recruit'])->name('floaters.recruit');
    Route::post('/floaters/{nurse}/release', [FloaterController::class, 'release'])->name('floaters.release');
});
