<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Response;

class BookingSvgController
{
    public function __invoke(Booking $booking): Response
    {
        return response()
            ->view('booking.svg', ['date' => $booking->start_at])
            ->header('Content-Type', 'image/svg+xml; charset=utf-8')
            ->header('Content-Disposition', sprintf('inline; filename="%s.svg"', $booking->id));
    }
}
