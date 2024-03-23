<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingPreviewController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Booking $booking): View
    {
        return view('booking.preview.index', [
            'booking' => $booking,
        ]);
    }
}
