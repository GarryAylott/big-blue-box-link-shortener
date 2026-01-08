<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LinkController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [LoginController::class, 'login']);
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('logout');

// Admin routes (protected)
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Link CRUD
    Route::get('/links', [LinkController::class, 'index'])->name('admin.links.index');
    Route::get('/links/create', [LinkController::class, 'create'])->name('admin.links.create');
    Route::post('/links', [LinkController::class, 'store'])->name('admin.links.store');
    Route::get('/links/{link}/edit', [LinkController::class, 'edit'])->name('admin.links.edit');
    Route::put('/links/{link}', [LinkController::class, 'update'])->name('admin.links.update');
    Route::delete('/links/{link}', [LinkController::class, 'destroy'])->name('admin.links.destroy');

    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'summary'])->name('admin.analytics.summary');
    Route::get('/clicks', [AnalyticsController::class, 'clicks'])->name('admin.analytics.clicks');
});

// Catch-all redirect route (MUST be last)
Route::get('/{slug}', [RedirectController::class, 'redirect'])->where('slug', '[a-zA-Z0-9\-_]+');
