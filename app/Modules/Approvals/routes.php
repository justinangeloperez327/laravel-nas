<?php

use App\Modules\Approvals\Http\Controllers\ApprovalController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'verified'])->prefix('administration/approvals')->group(function (): void {
    Route::get('/', [ApprovalController::class, 'index'])->name('approvals.index');
    Route::post('workflows', [ApprovalController::class, 'storeWorkflow']);
    Route::post('steps', [ApprovalController::class, 'storeStep']);
    Route::post('requests/{approvalRequest}/actions', [ApprovalController::class, 'act']);
});
