<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $user = Auth::user();

    if (in_array($user->user_type, ['admin', 'general_service'])) {
        return redirect('/admin'); 
    }

    if ($user->user_type === 'nurse') {
        return redirect()->route('nurse.station');
    }

    if ($user->user_type === 'physician') {
        return redirect()->route('doctor.rounds');
    }

})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    
    Route::get('/nurse/station', function() {
        return "<h1>Nurse Station - Built with DaisyUI</h1>"; 
    })->name('nurse.station');

    Route::get('/doctor/rounds', function() {
        return "<h1>Doctor Rounds - Built with DaisyUI</h1>";
    })->name('doctor.rounds');

});

require __DIR__.'/auth.php';