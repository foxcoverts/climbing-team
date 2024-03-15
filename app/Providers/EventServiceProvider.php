<?php

namespace App\Providers;

use App\Events\BookingCancelled;
use App\Events\BookingChanged;
use App\Events\BookingConfirmed;
use App\Events\BookingInvite;
use App\Events\BookingRestored;
use App\Events\Registered;
use App\Listeners\RecordBookingChanges;
use App\Listeners\SendBookingCancelledEmail;
use App\Listeners\SendBookingChangedEmail;
use App\Listeners\SendBookingConfirmedEmail;
use App\Listeners\SendBookingInviteEmail;
use App\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        BookingInvite::class => [
            SendBookingInviteEmail::class,
        ],
        BookingChanged::class => [
            RecordBookingChanges::class,
            SendBookingChangedEmail::class,
        ],
        BookingConfirmed::class => [
            RecordBookingChanges::class,
            SendBookingConfirmedEmail::class,
        ],
        BookingCancelled::class => [
            RecordBookingChanges::class,
            SendBookingCancelledEmail::class,
        ],
        BookingRestored::class => [
            RecordBookingChanges::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
