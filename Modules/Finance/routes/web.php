<?php

use Illuminate\Support\Facades\Route;
use Modules\Finance\Http\Controllers\FinanceController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('finance', FinanceController::class)->names('finance');
});
Route::post('/user/buyer-withdraw', [FinanceController::class, 'storeBuyerWithdraw'])->name('user.userWithdrawRequest');
Route::get('/user/buyer-withdraw/list', [FinanceController::class, 'getBuyerWithdrawList'])->name('user.getBuyerWithdrawList');

Route::group(['middleware' => ['checkInstallerStatus', 'setLocale']], function () {
    Route::group(['prefix' => 'admin','middleware' => 'admin'], function () {
        Route::get('buyer-earning', [FinanceController::class, 'earning'])->name('admin.buyer-earning');
        Route::get('buyer-request', [FinanceController::class, 'buyerRequest'])->name('admin.buyer-request');
        Route::post('buyer-earning/list', [FinanceController::class, 'buyerTransaction'])->name('admin.earningList');
        Route::get('buyer-request/list', [FinanceController::class, 'getBuyerWithdrawListAdmin'])->name('admin.getBuyerWithdrawList');
        Route::get('bookings/refund', [FinanceController::class, 'bookingRefund'])->name('admin.booking-refund');
        Route::post('bookings/refund/list', [FinanceController::class, 'refundList'])->name('admin.refundList');
    });
});
