<?php

use Illuminate\Support\Facades\Route;
use Modules\Gigs\Http\Controllers\GigsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('gigs', GigsController::class)->names('gigs');
});

Route::group(['middleware' => ['checkInstallerStatus', 'setLocaleUser', 'customer', 'maintenance']], function () {
    Route::get('add-gigs', [GigsController::class, 'craeteGigs'])->name('index.craeteGigs');
    Route::post('store-gigs', [GigsController::class, 'storeGigs'])->name('index.storeGigs');
    Route::post('edit-gigs', [GigsController::class, 'editGigs'])->name('index.storeGigs');
    Route::get('get-sub_category', [GigsController::class, 'getSub']);
});

Route::group(['middleware' => ['checkInstallerStatus', 'setLocale']], function () {
    Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
        Route::get('gigs-list', [GigsController::class, 'listIndex'])->name('admin.gigs.index');
        Route::get('gigs/datatable', [GigsController::class, 'list'])->name('admin.gigs');
        Route::post('update-status', [GigsController::class, 'orderStatus'])->name('admin.order-status');
        Route::get('gigs-details/{id}', [GigsController::class, 'gigsDetails'])->name('admin.gigsdetails');
        Route::get('gigs-detail/{slug}', [GigsController::class, 'gigDetails'])->name('admin.gigsdetail');
    });
    Route::post('list-gigs', [GigsController::class,'listApi']);
    Route::post('list-details-gigs', [GigsController::class,'listDetailsApi']);
    Route::post('recent-list-gigs', [GigsController::class,'recentlistApi']);
});
