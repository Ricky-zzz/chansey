<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    $user = Auth::user();

    $redirects = [
        'admin' => '/admin',
        'general_service' => '/maintenance',
        //'nurse' => route('nurse.station'),
        //'physician' => route('doctor.rounds'),
    ];

    if (isset($redirects[$user->user_type])) {
        return redirect($redirects[$user->user_type]);
    }

    abort(403, 'Unauthorized user type.');
        
})->middleware(['auth'])->name('dashboard');


Route::middleware(['auth'])->group(function () {
    
    //Route::get('/nurse/station', [NurseController::class, 'station'])->name('nurse.station');
    //Route::get('/doctor/rounds', [DoctorController::class, 'rounds'])->name('doctor.rounds');

});

require __DIR__.'/auth.php';