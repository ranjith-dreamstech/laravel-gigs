<?php

use App\Http\Controllers\CountryController;
use Illuminate\Support\Facades\Route;

Route::get('/countries', [CountryController::class, 'getCountries']);
Route::post('/states', [CountryController::class, 'getStates']);
Route::post('/cities', [CountryController::class, 'getCities']);
