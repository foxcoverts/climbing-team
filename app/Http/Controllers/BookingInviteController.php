<?php

namespace App\Http\Controllers;

use App\Enums\AttendeeStatus;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BookingInviteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        Gate::authorize('viewOwn', Booking::class);

        $bookings = Booking::future()->notCancelled()
            ->attendeeStatus($request->user(), [
                AttendeeStatus::NeedsAction, AttendeeStatus::Tentative
            ])
            ->with('attendees')
            ->ordered()->get()
            ->groupBy(function (Booking $booking) {
                return $booking->start_at->startOfDay();
            });

        return view('booking.invite.index', [
            'user' => $request->user(),
            'bookings' => $bookings,
        ]);
    }
}
