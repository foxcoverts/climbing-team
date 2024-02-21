<?php

use App\Http\Controllers\BookingAttendanceController;
use App\Http\Controllers\BookingAttendeeController;
use App\Http\Controllers\BookingAttendeeInviteController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingIcsController;
use App\Http\Controllers\BookingInviteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrashedBookingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        return redirect('/dashboard');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::singleton('profile', ProfileController::class)->destroyable()->except(['edit']);

    Route::singleton('booking.attendance', BookingAttendanceController::class)->except(['edit']);

    Route::controller(BookingAttendeeInviteController::class)->group(function () {
        Route::get('booking/{booking}/attendee/invite', 'create')->name('booking.attendee.invite');
        Route::post('booking/{booking}/attendee/invite', 'store')->name('booking.attendee.invite.store');
    });

    Route::resource('booking.attendee', BookingAttendeeController::class)->scoped()->except(['edit']);

    Route::get('/booking/invite', BookingInviteController::class)->name('booking.invite');

    Route::controller(BookingIcsController::class)->group(function () {
        Route::get('/booking.ics', 'index')->name('booking.ics');
        Route::get('/booking/{booking}.ics', 'show')->name('booking.show.ics');
    });

    Route::resource('booking', BookingController::class);

    Route::prefix('trash')->name('trash.')->group(function () {
        Route::resource('booking', TrashedBookingController::class)
            ->only(['index', 'show', 'update', 'destroy'])
            ->withTrashed(['show', 'update', 'destroy']);
    });

    Route::resource('user', UserController::class);
});

require __DIR__ . '/auth.php';
