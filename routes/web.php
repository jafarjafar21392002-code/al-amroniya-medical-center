<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index']);
use App\Http\Controllers\AppointmentController;

Route::get('/appointment/{id}', [AppointmentController::class, 'create']);
Route::post('/appointment', [AppointmentController::class, 'store']);
