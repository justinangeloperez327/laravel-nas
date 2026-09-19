<?php

use App\Modules\HumanResources\Http\Controllers\HumanResourcesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'verified'])->prefix('human-resources')->group(function (): void {
    Route::get('/', [HumanResourcesController::class, 'index'])->name('human-resources.index');
    Route::post('employees', [HumanResourcesController::class, 'store']);
});
