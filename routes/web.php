<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

// AUTH
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->user_type === 'admin') {
        return redirect('/admin');
    }

    if ($user->user_type === 'general_service') {
        return redirect('/maintenance');
    }
 
    if ($user->user_type === 'nurse') {
        if (! $user->nurse) {
            abort(403, 'Nurse profile not found.');
        }

        if ($user->nurse->designation === 'Admitting') {
            return redirect()->route('nurse.admitting.dashboard');
        }

        return redirect()->route('nurse.clinical.dashboard');
    }

    if ($user->user_type === 'physician') {
        return "Doctor Dashboard Coming Soon";
    }

    abort(403, 'Unauthorized user type.');
})->middleware(['auth'])->name('dashboard');


//  ADMITTING NURSES 
Route::middleware(['auth'])->prefix('nurse/admitting')->name('nurse.admitting.')->group(function () {

    // Admitting Dashboard
    Route::get('/dashboard', function () {
        if (Auth::user()->nurse->designation !== 'Admitting') abort(403, 'Access Restricted to Admitting Staff');
        return view('nurse.admitting.dashboard');
    })->name('dashboard');

    // Patient index 
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');

    // Patient create form
    Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');

    // Patient store action
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');

    // View Patient Profile
    Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');

    // view patient edit form
    Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    
    // view patient update action
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');

    // Admission index
    Route::get('/admissions', [AdmissionController::class, 'index'])->name('admissions.index');
    
    // View Admission Profile
    Route::get('/admissions/{admission}', [AdmissionController::class, 'show'])->name('admissions.show');

    Route::get('/admissions/{patient_id}/create', [AdmissionController::class, 'create'])->name('admissions.create');

    Route::post('/admissions/{patient_id}/store', [AdmissionController::class, 'store'])->name('admissions.store');
    
    // admission edit form
    Route::get('/admissions/{admission}/edit', [AdmissionController::class, 'edit'])->name('admissions.edit');

    Route::put('/admissions/{admission}', [AdmissionController::class, 'update'])->name('admissions.update');
});

// File viewing route
Route::middleware(['auth'])->get('/documents/{id}/view', [FileController::class, 'view'])->name('document.view');




require __DIR__ . '/auth.php';
