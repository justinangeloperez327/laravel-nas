<?php

use App\Modules\Projects\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'verified'])->prefix('projects')->group(function (): void {
    Route::get('/', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/', [ProjectController::class, 'store']);
    Route::put('{project}', [ProjectController::class, 'update']);
});
