<?php

use App\Enums\BookingPeriod;
use App\Http\Controllers\BookingAttendanceController;
use App\Http\Controllers\BookingAttendeeController;
use App\Http\Controllers\BookingAttendeeInviteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
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

Route::get('/', function () {
    return redirect('/dashboard');
})->middleware(['auth', 'verified']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::singleton('booking.attendance', BookingAttendanceController::class)->except(['edit']);

    Route::get('booking/{booking}/attendee/invite', [BookingAttendeeInviteController::class, 'create'])->name('booking.attendee.invite');
    Route::post('booking/{booking}/attendee/invite', [BookingAttendeeInviteController::class, 'store'])->name('booking.attendee.invite.store');

    Route::resource('booking.attendee', BookingAttendeeController::class)->scoped()->except(['edit']);

    Route::get('/booking.ics', [BookingController::class, 'index_ics'])->name('booking.ics');
    Route::get('/booking/{booking}.ics', [BookingController::class, 'show_ics'])->name('booking.show.ics');
    Route::get('/booking/{period}', [BookingController::class, 'index'])->name('booking.period')
        ->whereIn('period', [BookingPeriod::Past->value]);
    Route::resource('booking', BookingController::class);

    Route::prefix('trash')->name('trash.')->group(function () {
        Route::resource('booking', TrashedBookingController::class)
            ->only(['index', 'show', 'update', 'destroy'])
            ->withTrashed(['show', 'update', 'destroy']);
    });

    Route::resource('user', UserController::class);
});

require __DIR__ . '/auth.php';
