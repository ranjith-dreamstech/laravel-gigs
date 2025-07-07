<?php

use Illuminate\Support\Facades\Route;
use Modules\Booking\Http\Controllers\UserBookingController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::post('check-booking', [UserBookingController::class,'checkBooking']);
