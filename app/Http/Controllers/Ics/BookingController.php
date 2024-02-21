<?php

namespace App\Http\Controllers\Ics;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Response;

class BookingController extends Controller
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
    public function index(): Response
    {
        return $this->ics(
            Booking::all()->load('attendees')
        );
    }

    /**
     * Display an iCal listing for the specified resource.
     */
    public function show(Booking $booking): Response
    {
        return $this->ics(
            [$booking],
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
    protected function ics($bookings, string $filename = 'booking'): Response
    {

        return response()
            ->view('booking.ics', ['bookings' => $bookings])
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', sprintf('attachment; filename="%s.ics"', $filename));
    }
}
