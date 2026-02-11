<?php

use App\Http\Controllers\Memo\AnnouncementController;
use Illuminate\Support\Facades\Route;

// Shared routes for both Head Nurses and Staff Nurses
Route::middleware(['auth'])->prefix('nurse/announcement')->name('nurse.announcement.')->group(function () {
    Route::get('/', [AnnouncementController::class, 'index'])->name('index');
    Route::get('/{id}', [AnnouncementController::class, 'show'])->name('show');
});
