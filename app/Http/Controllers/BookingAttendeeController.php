<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;

class BookingAttendeeController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Booking $booking, User $attendee): View
    {
        Gate::authorize('view', $attendee->attendance);

        return view('booking.attendee.show', [
            'booking' => $booking,
            'attendee' => $attendee->load('qualifications', 'qualifications.detail'),
        ]);
    }
}
