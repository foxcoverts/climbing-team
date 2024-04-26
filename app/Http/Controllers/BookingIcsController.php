<?php

namespace App\Http\Controllers;

use App\Enums\AttendeeStatus;
use App\Models\Booking;
use App\Models\User;
use DateInterval;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class BookingIcsController extends Controller
{
    /**
     * Display an iCal listing of the resource.
     */
    public function index(Request $request, User $user): Response
    {
        Gate::authorize('viewAny', Booking::class);

        $bookings = Booking::forUser($request->user());

        return $this->ics(
            $bookings->get(),
            $request->user(),
            title: __(':Name Bookings', ['name' => config('app.name', 'Fox Coverts Climbing')]),
            description: __('All bookings for the team.'),
            debug: config('app.debug') && $request->get('debug'),
        );
    }

    /**
     * Display an iCal listing for the user's rota.
     *
     * This only includes bookings that the current user has responded
     * 'ACCEPTED' or 'TENTATIVE'. In contrast to `index` which includes all
     * bookings the current user has permission to view.
     */
    public function rota(Request $request, User $user): Response
    {
        Gate::authorize('viewOwn', Booking::class);

        $bookings = Booking::attendeeStatus($request->user(), [
            AttendeeStatus::Accepted, AttendeeStatus::Tentative,
        ]);

        return $this->ics(
            $bookings->get(),
            $request->user(),
            title: __(':Name Rota', ['name' => config('app.name', 'Fox Coverts Climbing')]),
            description: __('All of the bookings you are attending.'),
            debug: config('app.debug') && $request->get('debug'),
        );
    }

    /**
     * Display an iCal listing for the specified resource.
     */
    public function show(Request $request, User $user, Booking $booking): Response
    {
        Gate::authorize('view', $booking);

        return $this->ics(
            [$booking],
            $request->user(),
            title: __(':Name Bookings', ['name' => config('app.name', 'Fox Coverts Climbing')]),
            filename: sprintf('booking-%s', $booking->id),
            debug: config('app.debug') && $request->get('debug'),
        );
    }

    /**
     * Turn bookings into an ICS file.
     *
     * @param  array<Booking>  $bookings
     */
    protected function ics($bookings, User $user, string $title, string $description = '', string $filename = 'booking', bool $debug = false): Response
    {
        $ics = response()->view('booking.ics', [
            'bookings' => $bookings,
            'user' => $user,
            'name' => $title,
            'description' => $description,
            'refreshInterval' => DateInterval::createFromDateString('7 days'),
        ]);

        if ($debug) {
            return $ics
                ->header('Content-Type', 'text/plain; charset=utf-8')
                ->header('Content-Disposition', 'inline');
        } else {
            return $ics
                ->header('Content-Type', 'text/calendar; charset=utf-8')
                ->header('Content-Disposition', sprintf('inline; filename="%s.ics"', $filename));
        }
    }
}
