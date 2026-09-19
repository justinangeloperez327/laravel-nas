<?php

use App\Modules\DocumentControl\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'verified'])->prefix('documents')->group(function (): void {
    Route::get('/', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/', [DocumentController::class, 'store']);
    Route::post('revisions', [DocumentController::class, 'storeRevision']);
    Route::post('revisions/{documentRevision}/submit', [DocumentController::class, 'submit']);
    Route::post('revisions/{documentRevision}/decision', [DocumentController::class, 'decide']);
});
