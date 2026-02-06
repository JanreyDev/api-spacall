<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('/entry', [AuthController::class, 'loginEntry']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/register-profile', [AuthController::class, 'registerProfile']);
    Route::post('/login-pin', [AuthController::class, 'loginPin']);
Route::post('/forgot-pin', [AuthController::class, 'forgotPin']);
    Route::post('/reset-pin', [AuthController::class, 'resetPin']);
});

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // Services
    Route::get('/services', [\App\Http\Controllers\Api\ServiceController::class, 'index']);
    Route::get('/services/{slug}', [\App\Http\Controllers\Api\ServiceController::class, 'show']);
    
    // Therapists
    Route::get('/therapists', [\App\Http\Controllers\Api\TherapistController::class, 'index']);
    Route::get('/therapists/{uuid}', [\App\Http\Controllers\Api\TherapistController::class, 'show']);
    Route::get('/therapist/profile', [\App\Http\Controllers\Api\TherapistController::class, 'profile']);
});
