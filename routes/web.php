<?php

use App\Enums\BookingStatus;
use App\Http\Controllers\BookingAttendanceController;
use App\Http\Controllers\BookingAttendeeController;
use App\Http\Controllers\BookingAttendeeInviteController;
use App\Http\Controllers\BookingCommentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingEmailController;
use App\Http\Controllers\BookingIcsController;
use App\Http\Controllers\BookingInviteController;
use App\Http\Controllers\BookingLinkController;
use App\Http\Controllers\BookingPreviewController;
use App\Http\Controllers\BookingRelatedController;
use App\Http\Controllers\BookingRotaController;
use App\Http\Controllers\BookingShareController;
use App\Http\Controllers\ChangeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\KeyController;
use App\Http\Controllers\KitCheckController;
use App\Http\Controllers\KitCheckUserController;
use App\Http\Controllers\MailLogController;
use App\Http\Controllers\NewsPostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QualificationController;
use App\Http\Controllers\RespondController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\TransferKeyController;
use App\Http\Controllers\TrashedBookingController;
use App\Http\Controllers\TrashedDocumentController;
use App\Http\Controllers\UserBookingController;
use App\Http\Controllers\UserBookingInviteController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('/privacy-policy', 'privacy-policy')->name('privacy-policy');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('booking', [BookingController::class, 'calendar'])->name('booking.calendar');
    foreach (BookingStatus::cases() as $status) {
        Route::get('booking/'.$status->value, [BookingController::class, $status->value])
            ->name('booking.'.$status->value);
    }
    Route::controller(BookingLinkController::class)
        ->group(function () {
            Route::get('booking/links', 'index')->name('booking.links');
            Route::delete('booking/links', 'destroy')->name('booking.links.reset');
        });

    Route::controller(BookingAttendanceController::class)->group(function () {
        Route::get('booking/{booking}/attendance', 'edit')->name('booking.attendance.edit');
        Route::put('booking/{booking}/attendance', 'update')->name('booking.attendance.update');
    });

    Route::controller(BookingAttendeeInviteController::class)->group(function () {
        Route::get('booking/{booking}/attendee/invite', 'create')->name('booking.attendee.invite');
        Route::post('booking/{booking}/attendee/invite', 'store')->name('booking.attendee.invite.store');
    });

    Route::patch('booking/{booking}/attendee', [BookingAttendeeController::class, 'updateMany'])->name('booking.attendee.updateMany');
    Route::resource('booking.attendee', BookingAttendeeController::class)->scoped()->except('edit');

    Route::resource('booking.related', BookingRelatedController::class)->scoped(['bookings'])->except('show', 'edit', 'update');

    Route::resource('booking.comment', BookingCommentController::class)->shallow()->only('store', 'update', 'destroy');

    Route::get('booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');

    Route::get('booking/{booking}/email', BookingEmailController::class)->name('booking.email');

    Route::get('booking/{booking}/share', BookingShareController::class)->name('booking.share');

    Route::get('booking/{booking}.ics', [BookingIcsController::class, 'show'])->name('booking.show.ics');

    Route::resource('booking', BookingController::class)->except('index', 'show');

    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('invite', BookingInviteController::class)->name('booking.invite');

    Route::get('rota', BookingRotaController::class)->name('booking.rota');

    Route::get('change', ChangeController::class)->name('change.index');

    Route::resource('document', DocumentController::class);

    Route::controller(IncidentController::class)->group(function () {
        Route::get('incident', 'create')->name('incident.create');
        Route::post('incident', 'store');
    });

    Route::controller(TransferKeyController::class)->group(function () {
        Route::get('key/{key}/transfer', 'edit')->name('key.transfer');
        Route::put('key/{key}/transfer', 'update');
        Route::patch('key/{key}/transfer', 'update');
    });
    Route::resource('key', KeyController::class);

    Route::get('kit-check/user/{user}', [KitCheckUserController::class, 'index'])->name('kit-check.user.index');
    Route::resource('kit-check', KitCheckController::class);

    Route::get('mail/{mail}/raw', [MailLogController::class, 'raw']);
    Route::resource('mail', MailLogController::class)->except(['create', 'store', 'edit', 'update']);

    Route::resource('news', NewsPostController::class)
        ->except('show')
        ->parameters(['news' => 'post']);

    Route::resource('todo', TodoController::class);

    Route::middleware('password.confirm')->group(function () {
        Route::controller(ProfileController::class)->group(function () {
            Route::get('profile', 'edit')->name('profile.edit');
            Route::patch('profile', 'update')->name('profile.update');
            Route::delete('profile', 'destroy')->name('profile.destroy');
        });

        Route::prefix('trash')->name('trash.')->group(function () {
            Route::resource('booking', TrashedBookingController::class)
                ->only(['index', 'show', 'update', 'destroy'])
                ->withTrashed(['show', 'update', 'destroy']);

            Route::resource('document', TrashedDocumentController::class)
                ->only(['index', 'show', 'update', 'destroy'])
                ->withTrashed(['show', 'update', 'destroy']);
        });

        Route::controller(UserBookingInviteController::class)->group(function () {
            Route::get('user/{user}/booking/invite', 'create')->name('user.booking.invite');
            Route::post('user/{user}/booking/invite', 'store')->name('user.booking.invite.store');
        });
        Route::get('user/{user}/booking', UserBookingController::class)->name('user.booking.index');
        Route::post('user/{user}/invite', [UserController::class, 'sendInvite'])->name('user.invite');
        Route::resource('user.qualification', QualificationController::class);
        Route::resource('user', UserController::class);
    });
});

Route::get('booking/{booking}/preview', BookingPreviewController::class);
Route::get('booking/{booking}', [BookingController::class, 'show'])->name('booking.show');

Route::controller(BookingIcsController::class)
    ->middleware(Authenticate::fromParam('user'))
    ->group(function () {
        Route::get('ical/{user:ical_token}/booking.ics', 'index')->name('booking.ics');
        Route::get('ical/{user:ical_token}/rota.ics', 'rota')->name('booking.rota.ics');
    });

Route::get('news/{post}', [NewsPostController::class, 'show'])->name('news.show');

Route::controller(RespondController::class)
    ->middleware(Authenticate::fromParam('attendee'))
    ->group(function () {
        Route::get('respond/{booking}/{attendee}', 'show')->scopeBindings()->name('respond');
        Route::post('respond/{booking}/{attendee}', 'store')->scopeBindings()->name('respond.store');
        Route::get('respond/{booking}/{attendee}/accept', 'accept')->scopeBindings()->name('respond.accept');
        Route::get('respond/{booking}/{attendee}/tentative', 'tentative')->scopeBindings()->name('respond.tentative');
        Route::get('respond/{booking}/{attendee}/decline', 'decline')->scopeBindings()->name('respond.decline');
    });

require __DIR__.'/auth.php';
