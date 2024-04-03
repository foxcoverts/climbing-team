<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class BookingPreviewController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Booking $booking): RedirectResponse
    {
        return redirect(status: Response::HTTP_PERMANENTLY_REDIRECT)
            ->route('booking.show', $booking);
    }
}
