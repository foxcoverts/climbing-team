<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookingIcsController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Booking::class, 'booking');
    }

    /**
     * Display an iCal listing of the resource.
     */
    public function index(Request $request): Response
    {
        $ics = $this->ics(
            Booking::all()->load('attendees', 'lead_instructor'),
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
