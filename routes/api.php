<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PharmacistController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PrescriptionController;
use Illuminate\Support\Facades\Mail;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/reset_password', [PasswordResetController::class, 'requestOtp']);
Route::post('/reset_password_check', [PasswordResetController::class, 'checkOtp']);
Route::post('/update_password', [PasswordResetController::class, 'updatePassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function () {
        return auth()->user();
    });

    Route::post('/logout', [UserController::class, 'logout']);

    Route::get('/patient/prescriptions', [PatientController::class, 'archive']);
    Route::get('/doctor/prescriptions', [DoctorController::class, 'archive']);
    Route::get('/pharmacist/prescriptions', [PharmacistController::class, 'archive']);

    Route::apiResource('/prescriptions', PrescriptionController::class)->except('index');

    Route::get('/prescription_item/{id}', [PrescriptionController::class, 'updateDate']);

    Route::get('/active_prescription_items', [PrescriptionController::class, 'activePrescriptionItems']);

    Route::delete('/prescription_item/{id}', [PrescriptionController::class, 'destroyPrescriptionItem']);

    Route::get('/patient/{id}', [PatientController::class, 'show']);

    Route::post('/purchase/{id}', [PharmacistController::class, 'purchase']);

    Route::get('/patient_prescriptions/{id}', [PrescriptionController::class, 'patientPrescriptions']);



});





