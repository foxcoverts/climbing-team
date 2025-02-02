<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BookingController extends Controller
{
    /**
     * Display a calendar of the Bookings.
     */
    public function calendar(): View
    {
        Gate::authorize('viewAny', Booking::class);

        return view('booking.calendar');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Booking $booking): Response|View
    {
        if (Auth::guest()) {
            return view('booking.preview', [
                'booking' => $booking,
                'responded' => $request->get('responded'),
            ]);
        } elseif (Gate::check('view', $booking)) {
            return view('booking.show', [
                'booking' => $booking,
                'currentUser' => $request->user(),
            ]);
        } else {
            return response()->view('booking.forbidden', [
                'booking' => $booking,
            ], Response::HTTP_FORBIDDEN);
        }
    }
}
