<?php

use App\Modules\Procurement\Http\Controllers\ProcurementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'verified'])->prefix('procurement')->group(function (): void {
    Route::get('/', [ProcurementController::class, 'index'])->name('procurement.index');
    Route::post('purchase-requests', [ProcurementController::class, 'storePurchaseRequest']);
    Route::post('purchase-requests/{purchaseRequest}/submit', [ProcurementController::class, 'submitPurchaseRequest']);
    Route::post('purchase-requests/{purchaseRequest}/decision', [ProcurementController::class, 'decidePurchaseRequest']);
});
