<?php

use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\StoreMailLogController;
use App\Http\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(Authenticate::using('sanctum'))->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::name('api')->apiResource('/booking', BookingController::class);
});

Route::post('mail', StoreMailLogController::class);
