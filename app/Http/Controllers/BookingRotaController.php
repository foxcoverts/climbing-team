<?php

namespace App\Http\Controllers;

use App\Enums\BookingAttendeeStatus;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BookingRotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request): View
    {
        Gate::authorize('viewOwn', Booking::class);

        $bookings = Booking::future()->notCancelled()
            ->attendeeStatus($request->user(), [
                BookingAttendeeStatus::Accepted, BookingAttendeeStatus::Tentative
            ])
            ->with('attendees')
            ->ordered()->get()
            ->groupBy(function (Booking $booking) {
                return $booking->start_at->startOfDay();
            });

        return view('booking.rota.index', [
            'user' => $request->user(),
            'bookings' => $bookings,
        ]);
    }
}
