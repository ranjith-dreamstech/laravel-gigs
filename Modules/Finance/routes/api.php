<?php

use Illuminate\Support\Facades\Route;
use Modules\Finance\Http\Controllers\FinanceController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('finance', FinanceController::class)->names('finance');
});
Route::post('payout/store', [FinanceController::class, 'storePayoutHistroy'])->name('admin.storePayoutHistroy');
Route::post('request/update', [FinanceController::class, 'updateProviderRequest'])->name('admin.updateProviderRequest');
Route::post('/refund/upload', [FinanceController::class, 'uploadRefundProof'])->name('admin.uploadRefundProof');
