<?php

use App\Modules\Clients\Http\Controllers\ClientAddressController;
use App\Modules\Clients\Http\Controllers\ClientContactController;
use App\Modules\Clients\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'verified'])->prefix('clients')->group(function (): void {
    Route::get('/', [ClientController::class, 'index'])->name('clients.index');
    Route::post('/', [ClientController::class, 'store']);
    Route::put('{client}', [ClientController::class, 'update']);
    Route::patch('{client}/status', [ClientController::class, 'status']);
    Route::post('contacts', [ClientContactController::class, 'store']);
    Route::post('addresses', [ClientAddressController::class, 'store']);
});
