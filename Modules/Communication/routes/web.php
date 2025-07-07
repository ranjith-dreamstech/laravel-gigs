<?php

use Illuminate\Support\Facades\Route;
use Modules\Communication\Http\Controllers\AnnouncementController;
use Modules\Communication\Http\Controllers\CommunicationController;
use Modules\Communication\Http\Controllers\ContactController;
use Modules\Communication\Http\Controllers\TicketController;

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
Route::group(['middleware' => ['checkInstallerStatus', 'setLocale']], function () {
    Route::group(['prefix' => 'admin','middleware' => 'admin'], function () {
        Route::resource('communication', CommunicationController::class)->names('communication');
        //announcement
        Route::get('announcement', [AnnouncementController::class, 'index'])->name('communication.announcement');
        Route::post('announcement/save', [AnnouncementController::class, 'store'])->name('communication.announcement.store');
        Route::get('announcement/list', [AnnouncementController::class, 'list'])->name('communication.announcement.list');
        Route::post('/annoncement/delete', [AnnouncementController::class, 'delete'])->name('communication.announcement.delete');
        //contact-message
        Route::get('contact-message', [ContactController::class, 'index'])->name('communication.contact-message');
        Route::get('contact-message/list', [ContactController::class, 'list'])->name('communication.contact-message.list');
        Route::post('contact-message/delete', [ContactController::class, 'delete'])->name('communication.contact-message.delete');
        //ticket
        Route::get('ticket', [TicketController::class, 'index'])->name('communication.ticket');
    });
    Route::prefix('user')->middleware('customer')->controller(TicketController::class)->group(function () {
        Route::get('ticket', 'userTicket')->name('user.ticket');
        Route::post('ticket/store', 'userTicketStore')->name('user.ticketstore');
    });
    Route::get('ticket/list', [TicketController::class, 'listTickets'])->name('communication.ticketlist');
    Route::post('ticket/update', [TicketController::class, 'ticketUpdate'])->name('communication.ticketUpdate');
    Route::post('ticket/delete', [TicketController::class, 'delete'])->name('communication.ticket.delete');
});
