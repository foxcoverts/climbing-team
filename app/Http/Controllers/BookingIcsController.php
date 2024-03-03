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
        return $this->ics(
            Booking::all()->load('attendees', 'lead_instructor'),
            $request->user()
        );
    }

    /**
     * Display an iCal listing for the specified resource.
     */
    public function show(Request $request, Booking $booking): Response
    {
        return $this->ics(
            [$booking],
            $request->user(),
            filename: sprintf("booking-%s", $booking->id)
        );
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
            ->header('Content-Disposition', sprintf('attachment; filename="%s.ics"', $filename));
    }
}
