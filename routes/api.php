<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\PublicEventController;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Public API routes (no authentication required)
Route::prefix('events')->group(function () {
    Route::get('/', [PublicEventController::class, 'index']);
    Route::get('/{slug}', [PublicEventController::class, 'show']);
});

// Admin API routes (authentication required)
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::apiResource('events', EventController::class);
});
