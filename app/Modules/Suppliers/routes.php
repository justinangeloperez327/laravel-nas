<?php

use App\Modules\Suppliers\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'verified'])->prefix('suppliers')->group(function (): void {
    Route::get('/', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/', [SupplierController::class, 'store']);
    Route::put('{supplier}', [SupplierController::class, 'update']);
    Route::post('{supplier}/approve', [SupplierController::class, 'approve']);
});
