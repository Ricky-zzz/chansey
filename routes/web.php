<?php

use App\Http\Controllers\Admission\PatientController;
use App\Http\Controllers\Admission\AdmissionController;
use App\Http\Controllers\Admission\DashboardController as AdmissionDash;
use App\Http\Controllers\Clinical\DashboardController as ClinicDash;
use App\Http\Controllers\Physician\DashboardController as PhysicianDash;
use App\Http\Controllers\Physician\OrderController;
use \App\Http\Controllers\Clinical\WardController;
use App\Http\Controllers\Clinical\CarePlanController;
use App\Http\Controllers\Clinical\ClinicalLogController;
use App\Http\Controllers\FileController;
use \App\Http\Controllers\Clinical\OrderExecutionController;
use App\Http\Controllers\Physician\TreatmentPlanController;
use App\Http\Controllers\Physician\MyPatientController;
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

    if ($user->user_type === 'pharmacist') {
        return redirect('/pharmacy');
    }

    if ($user->user_type === 'nurse') {
        if (! $user->nurse) {
            return redirect('/login')->with('error', 'Nurse profile not found.');
        }

        if ($user->nurse->designation === 'Admitting') {
            return redirect()->route('nurse.admitting.dashboard');
        }

        return redirect()->route('nurse.clinical.dashboard');
    }

    if ($user->user_type === 'physician') {
        return redirect()->route('physician.dashboard');
    }

    return redirect('/login')->with('error', 'Unauthorized user type.');
})->middleware(['auth'])->name('dashboard');


//  ADMITTING NURSES 
Route::middleware(['auth'])->prefix('nurse/admitting')->name('nurse.admitting.')->group(function () {

    // Admitting Dashboard
    Route::get('/dashboard', [AdmissionDash::class, 'index'])->name('dashboard');

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

//  Clinical NURSES 
Route::middleware(['auth'])->prefix('nurse/clinical')->name('nurse.clinical.')->group(function () {

    // Clinical Dashboard
    Route::get('/dashboard', [ClinicDash::class, 'index'])->name('dashboard');

    // WARD LIST
    Route::get('/my-ward', [WardController::class, 'index'])
        ->name('ward.index');

    // PATIENT CHART 
    Route::get('/patient/{admission}', [WardController::class, 'show'])
        ->name('ward.show');

    // NURSING CARE PLAN ROUTES
    Route::get('/patient/{admission}/care-plan', [CarePlanController::class, 'edit'])->name('care-plan.edit');
    Route::put('/patient/{admission}/care-plan', [CarePlanController::class, 'update'])->name('care-plan.update');

    // CLINICAL LOG add
    Route::post('/patient/{admission}/logs', [ClinicalLogController::class, 'store'])->name('logs.store');

    // Upload Lab Result
    Route::post('/orders/upload-result', [OrderExecutionController::class, 'uploadLabResult'])
        ->name('orders.upload_result');

    // Request Transfer
    Route::post('/orders/transfer', [OrderExecutionController::class, 'requestTransfer'])
        ->name('orders.transfer');
});

//  Physicians
Route::middleware(['auth'])->prefix('physician')->name('physician.')->group(function () {

    // Physician Dashboard
    Route::get('/dashboard', [PhysicianDash::class, 'index'])->name('dashboard');

    // my patient list
    Route::get('/mypatients', [MyPatientController::class, 'index'])->name('patients.index');

    // patient chart view
    Route::get('/mypatients/{admission}', [MyPatientController::class, 'show'])->name('patients.show');

    // patient order make
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // discontinue order
    Route::patch('/orders/{order}/discontinue', [OrderController::class, 'discontinue'])
        ->name('orders.discontinue');

    // delete order
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Treatment Plan Routes
    Route::get('/admission/{id}/plan', [TreatmentPlanController::class, 'edit'])
        ->name('treatment-plan.edit');

    Route::get('/admission/{id}/plan/create', [TreatmentPlanController::class, 'edit'])
        ->name('treatment-plan.create');

    Route::put('/admission/{id}/plan', [TreatmentPlanController::class, 'update'])
        ->name('treatment-plan.update');
});


// File viewing route
Route::middleware(['auth'])->get('/document/view/{id}', [FileController::class, 'view'])->name('document.view');
Route::middleware(['auth'])->get('/documents/{id}/view', [FileController::class, 'view']);




require __DIR__ . '/auth.php';
