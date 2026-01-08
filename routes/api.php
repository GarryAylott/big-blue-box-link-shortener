<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LinkController;
use Illuminate\Support\Facades\Route;

// Token generation (no auth required)
Route::post('/tokens', [AuthController::class, 'createToken']);

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/links', [LinkController::class, 'index']);
    Route::post('/links', [LinkController::class, 'store']);
    Route::get('/links/{slug}', [LinkController::class, 'show']);
    Route::put('/links/{slug}', [LinkController::class, 'update']);
    Route::delete('/links/{slug}', [LinkController::class, 'destroy']);
    Route::get('/links/{slug}/stats', [LinkController::class, 'stats']);
});
