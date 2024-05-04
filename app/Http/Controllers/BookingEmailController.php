<?php

namespace App\Http\Controllers;

use App\Mail\BookingDetails;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BookingEmailController extends Controller
{
    public function __invoke(Request $request, Booking $booking): RedirectResponse
    {
        $attendee = $request->user();

        Mail::to($attendee->email)
            ->send(new BookingDetails($booking, $attendee));

        return redirect()->route('booking.show', $booking)
            ->with('alert.message', __('Booking details have been sent to your email address.'));
    }
}
