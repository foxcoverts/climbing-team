<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
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

    public function confirmed(Request $request): View
    {
        return $this->index($request, BookingStatus::Confirmed);
    }

    public function tentative(Request $request): View
    {
        return $this->index($request, BookingStatus::Tentative);
    }

    public function cancelled(Request $request): View
    {
        return $this->index($request, BookingStatus::Cancelled);
    }

    /**
     * Display list of Bookings for the given status.
     */
    protected function index(Request $request, BookingStatus $status): View
    {
        Gate::authorize('viewAny', [Booking::class, $status]);

        $bookings = Booking::future()
            ->forUser($request->user())
            ->where('bookings.status', $status)
            ->with('attendees')
            ->ordered()->get()
            ->groupBy(function (Booking $booking) {
                return $booking->start_at->startOfDay();
            });

        return view('booking.index', [
            'user' => $request->user(),
            'bookings' => $bookings,
            'status' => $status,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Booking $booking): Response|View
    {
        if (Gate::check('view', $booking)) {
            return view('booking.show', [
                'booking' => $booking->load('changes'),
                'currentUser' => $request->user(),
            ]);
        } elseif (Auth::guest()) {
            return view('booking.preview', [
                'booking' => $booking,
                'responded' => $request->get('responded'),
            ]);
        } else {
            return response()->view('booking.forbidden', [
                'booking' => $booking,
            ], Response::HTTP_FORBIDDEN);
        }
    }
}
