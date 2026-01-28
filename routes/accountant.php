<?php

use App\Http\Controllers\Accountant\DashboardController;
use \App\Http\Controllers\Accountant\BillingController;
use \App\Http\Controllers\Accountant\HospitalFeeController;
use \App\Http\Controllers\Accountant\BillingInfoController;
use Illuminate\Support\Facades\Route;

//  Accounting and billing routes
Route::middleware(['auth'])->prefix('accountant')->name('accountant.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/history', [DashboardController::class, 'history'])->name('history');

    Route::get('/billingInfo', [BillingInfoController::class, 'index'])->name('billinginfo.index');
    Route::get('/billingInfo/{admission}', [BillingInfoController::class, 'show'])->name('billinginfo.show');
    Route::put('/billingInfo/{admission}', [BillingInfoController::class, 'update'])->name('billinginfo.update');


    Route::resource('/fees', HospitalFeeController::class)
        ->names('fees')
        ->except(['create', 'show', 'edit']);

    Route::get('/billing/{id}', [BillingController::class, 'show'])->name('billing.show');
    Route::post('/billing/add-fee', [BillingController::class, 'addFee'])->name('billing.add_fee');
    Route::post('/billing/pay', [BillingController::class, 'store'])->name('billing.store');

    Route::delete('/billing/item/{id}', [BillingController::class, 'removeItem'])
        ->name('billing.remove_item');

    Route::get('/billing/{id}/print', [BillingController::class, 'print'])->name('billing.print');

    Route::get('/billing/{id}/bill', [BillingController::class, 'bill'])->name('billing.bill');
});
