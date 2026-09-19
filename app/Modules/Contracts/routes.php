<?php

use App\Modules\Contracts\Http\Controllers\ContractController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'verified'])->prefix('contracts')->group(function (): void {
    Route::get('/', [ContractController::class, 'index'])->name('contracts.index');
    Route::post('/', [ContractController::class, 'store']);
    Route::put('{contract}', [ContractController::class, 'update']);
});
