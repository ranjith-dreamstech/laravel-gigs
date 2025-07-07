<?php

use Illuminate\Support\Facades\Route;
use Modules\Gigs\Http\Controllers\GigsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('gigs', GigsController::class)->names('gigs');
});

Route::post('list-gigs', [GigsController::class,'listApi']);
Route::post('recent-list-gigs', [GigsController::class,'recentlistApi']);
Route::post('list-details-gigs', [GigsController::class,'listDetailsApi']);
