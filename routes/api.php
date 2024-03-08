<?php

use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\TrashedBookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->name('api.')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('user');
    Route::apiResource('/booking', BookingController::class);
    Route::get('/trash/booking', TrashedBookingController::class)->name('trash.booking.index');
});
