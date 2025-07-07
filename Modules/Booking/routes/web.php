<?php

use Illuminate\Support\Facades\Route;
use Modules\Booking\Http\Controllers\UserBookingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::post('create/booking', [UserBookingController::class, 'createBooking']);
Route::post('/gigs-checkout/{slug}', [UserBookingController::class, 'index'])->name('booking.checkout');
Route::post('/create/payments', [UserBookingController::class, 'userPayments']);
Route::get('/get-states/{country_id}', [UserBookingController::class, 'getStates']);
Route::get('/get-cities/{state_id}', [UserBookingController::class, 'getCities']);
Route::get('/booking/payment-success/{transaction_id}', [UserBookingController::class, 'paymentSuccess'])->name('payment.success.page');
Route::get('/strip-payment-success', [UserBookingController::class, 'stripPaymentSuccess'])->name('strip.payment.success');
Route::get('/paypal-payment-success', [UserBookingController::class, 'paypalPaymentSuccess'])->name('paypal.payment.success');
