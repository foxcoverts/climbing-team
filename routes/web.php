<?php

use App\Filament\Resources\BookingResource\Pages\ViewBooking;
use App\Filament\Resources\NewsPostResource\Pages\ViewNewsPost;
use App\Http\Controllers\BookingAttendanceController;
use App\Http\Controllers\BookingAttendeeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingEmailController;
use App\Http\Controllers\BookingIcsController;
use App\Http\Controllers\BookingRelatedController;
use App\Http\Controllers\BookingShareController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\TodoIcsController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('booking', [BookingController::class, 'calendar'])->name('booking.calendar');

    Route::controller(BookingAttendanceController::class)->group(function () {
        Route::get('booking/{booking}/attendance', 'edit')->name('booking.attendance.edit');
        Route::put('booking/{booking}/attendance', 'update')->name('booking.attendance.update');
    });

    Route::patch('booking/{booking}/attendee', [BookingAttendeeController::class, 'updateMany'])->name('booking.attendee.updateMany');
    Route::resource('booking.attendee', BookingAttendeeController::class)->scoped()->only('index', 'show');

    Route::resource('booking.related', BookingRelatedController::class)->scoped(['bookings'])->except('show', 'edit', 'update');

    Route::get('booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');

    Route::get('booking/{booking}/email', BookingEmailController::class)->name('booking.email');

    Route::get('booking/{booking}/share', BookingShareController::class)->name('booking.share');

    Route::get('booking/{booking}.ics', [BookingIcsController::class, 'show'])->name('booking.show.ics');

    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::controller(IncidentController::class)->group(function () {
        Route::get('incident', 'create')->name('incident.create');
        Route::post('incident', 'store');
    });

    Route::get('todo/{todo}.ics', [TodoIcsController::class, 'show'])->name('todo.show.ics');

    Route::resource('todo', TodoController::class);
});

Route::get('booking/{booking}', [BookingController::class, 'show'])->name('booking.show');

Route::controller(BookingIcsController::class)
    ->middleware(Authenticate::fromParam('user'))
    ->group(function () {
        Route::get('ical/{user:ical_token}/booking.ics', 'index')->name('booking.ics');
        Route::get('ical/{user:ical_token}/rota.ics', 'rota')->name('booking.rota.ics');
    });

Route::get('bookings/{record}', ViewBooking::class);
Route::get('news/{record}', ViewNewsPost::class);

require __DIR__.'/auth.php';
