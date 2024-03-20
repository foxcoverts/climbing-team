<?php

namespace App\Http\Controllers;

use App\Enums\AttendeeStatus;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class BookingIcsController extends Controller
{
    /**
     * Display an iCal listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Booking::class);

        $bookings = Booking::forUser($request->user());

        $ics = $this->ics(
            $bookings->get(),
            $request->user()
        );

        if (config('app.debug') && $request->get('debug')) {
            return $ics
                ->header('Content-Type', 'text/plain; charset=utf-8')
                ->header('Content-Disposition', 'inline');
        }
        return $ics;
    }

    /**
     * Display an iCal listing for the user's rota.
     *
     * This only includes bookings that the current user has responded
     * 'ACCEPTED' or 'TENTATIVE'. In contrast to `index` which includes all
     * bookings the current user has permission to view.
     */
    public function rota(Request $request): Response
    {
        Gate::authorize('viewOwn', Booking::class);

        $bookings = Booking::attendeeStatus($request->user(), [
            AttendeeStatus::Accepted, AttendeeStatus::Tentative
        ]);

        $ics = $this->ics(
            $bookings->get(),
            $request->user()
        );

        if (config('app.debug') && $request->get('debug')) {
            return $ics
                ->header('Content-Type', 'text/plain; charset=utf-8')
                ->header('Content-Disposition', 'inline');
        }
        return $ics;
    }


    /**
     * Display an iCal listing for the specified resource.
     */
    public function show(Request $request, Booking $booking): Response
    {
        Gate::authorize('view', $booking);

        $ics = $this->ics(
            [$booking],
            $request->user(),
            filename: sprintf("booking-%s", $booking->id)
        );

        if (config('app.debug') && $request->get('debug')) {
            return $ics
                ->header('Content-Type', 'text/plain; charset=utf-8')
                ->header('Content-Disposition', 'inline');
        }
        return $ics;
    }

    /**
     * Turn bookings into an ICS file.
     *
     * @param array<Booking> $bookings
     * @param string $filename
     * @return Response
     */
    protected function ics($bookings, User $user, string $filename = 'booking'): Response
    {
        return response()
            ->view('booking.ics', ['bookings' => $bookings, 'user' => $user])
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', sprintf('inline; filename="%s.ics"', $filename));
    }
}
