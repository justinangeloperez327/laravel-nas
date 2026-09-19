<?php

use App\Modules\Inventory\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'verified'])->prefix('inventory')->group(function (): void {
    Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('items', [InventoryController::class, 'storeItem']);
    Route::post('warehouses', [InventoryController::class, 'storeWarehouse']);
    Route::post('movements', [InventoryController::class, 'move']);
});
