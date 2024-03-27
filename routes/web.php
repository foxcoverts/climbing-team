<?php

use App\Enums\BookingStatus;
use App\Http\Controllers\BookingAttendanceController;
use App\Http\Controllers\BookingAttendeeController;
use App\Http\Controllers\BookingAttendeeInviteController;
use App\Http\Controllers\BookingCommentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingIcsController;
use App\Http\Controllers\BookingInviteController;
use App\Http\Controllers\BookingPreviewController;
use App\Http\Controllers\BookingRotaController;
use App\Http\Controllers\BookingShareController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MailLogController;
use App\Http\Controllers\NewsPostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RespondController;
use App\Http\Controllers\TrashedBookingController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('/privacy-policy', 'privacy-policy')->name('privacy-policy');

Route::get('booking/{booking}/preview', BookingPreviewController::class)->name('booking.preview');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('profile', 'edit')->name('profile.edit');
        Route::patch('profile', 'update')->name('profile.update');
        Route::delete('profile', 'destroy')->name('profile.destroy');
    });

    Route::get('booking', [BookingController::class, 'calendar'])->name('booking.calendar');
    foreach (BookingStatus::cases() as $status) {
        Route::get('booking/' . $status->value, [BookingController::class, $status->value])
            ->name('booking.' . $status->value);
    }

    Route::controller(BookingAttendanceController::class)->group(function () {
        Route::get('booking/{booking}/attendance', 'edit')->name('booking.attendance.edit');
        Route::put('booking/{booking}/attendance', 'update')->name('booking.attendance.update');
    });

    Route::controller(BookingAttendeeInviteController::class)->group(function () {
        Route::get('booking/{booking}/attendee/invite', 'create')->name('booking.attendee.invite');
        Route::post('booking/{booking}/attendee/invite', 'store')->name('booking.attendee.invite.store');
    });

    Route::resource('booking.attendee', BookingAttendeeController::class)->scoped()->except('show');

    Route::resource('booking.comment', BookingCommentController::class)->shallow()->only('store', 'update', 'destroy');

    Route::get('booking/{booking}/share', BookingShareController::class)->name('booking.share');

    Route::controller(BookingIcsController::class)->group(function () {
        Route::get('booking.ics', 'index')->name('booking.ics');
        Route::get('booking/{booking}.ics', 'show')->name('booking.show.ics');
        Route::get('rota.ics', 'rota')->name('booking.rota.ics');
    });

    Route::resource('booking', BookingController::class)->except('index');

    Route::get('invite', BookingInviteController::class)->name('booking.invite');

    Route::get('rota', BookingRotaController::class)->name('booking.rota');

    Route::prefix('trash')->name('trash.')->group(function () {
        Route::resource('booking', TrashedBookingController::class)
            ->only(['index', 'show', 'update', 'destroy'])
            ->withTrashed(['show', 'update', 'destroy']);
    });

    Route::get('mail/{mail}/raw', [MailLogController::class, 'raw']);
    Route::resource('mail', MailLogController::class)->except(['create', 'store', 'edit', 'update']);

    Route::resource('news', NewsPostController::class)
        ->parameters(['news' => 'post'])
        ->only('index', 'show');

    Route::post('user/{user}/invite', [UserController::class, 'sendInvite'])->name('user.invite');
    Route::resource('user', UserController::class);
});

Route::middleware(Authenticate::fromParam('attendee'))
    ->controller(RespondController::class)
    ->group(function () {
        Route::get('respond/{booking}/{attendee}', 'show')->scopeBindings()->name('respond');
        Route::post('respond/{booking}/{attendee}', 'store')->scopeBindings();
    });

require __DIR__ . '/auth.php';
