<?php

use App\Modules\Users\Http\Controllers\RoleController;
use App\Modules\Users\Http\Controllers\UserController;
use App\Modules\Users\Http\Controllers\UserStatusController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['web', 'auth', 'verified'])->group(function (): void {
    Route::get('/dashboard', fn () => Inertia::render('dashboard'))
        ->name('dashboard');

    Route::prefix('administration')->group(function (): void {
        Route::resource('users', UserController::class)
            ->except(['show', 'destroy']);

        Route::patch('users/{user}/status', [UserStatusController::class, 'update'])
            ->name('users.status.update');

        Route::resource('roles', RoleController::class)
            ->except(['show', 'destroy']);
    });
});
