<?php

use App\Http\Controllers\Accountant\DashboardController;

use Illuminate\Support\Facades\Route;

//  Accounting and billing routes
Route::middleware(['auth'])->prefix('accountant')->name('accountant.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

});
