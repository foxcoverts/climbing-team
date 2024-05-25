<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRelatedBookingRequest;
use App\Models\Bookable;
use App\Models\Booking;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BookingRelatedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Booking $booking): View
    {
        Gate::authorize('viewAny', [Bookable::class, $booking]);

        $relatedBookings = $booking->related()
            ->forUser($request->user())
            ->with('attendees')
            ->ordered()->get()
            ->groupBy(function (Booking $booking) {
                return $booking->start_at->startOfDay();
            });

        return view('booking.related.index', [
            'booking' => $booking,
            'related' => $relatedBookings,
            'currentUser' => $request->user(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Booking $booking): View
    {
        Gate::authorize('create', [Bookable::class, $booking]);

        return view('booking.related.create', [
            'booking' => $booking,
            'bookings' => Booking::future()
                ->forUser($request->user())
                ->whereNot('id', $booking->id)
                ->notCancelled()
                ->ordered()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRelatedBookingRequest $request, Booking $booking)
    {
        $related = Booking::find($request->related_id);

        Gate::authorize('create', [Bookable::class, $booking]);
        Gate::authorize('create', [Bookable::class, $related]);

        if (! $booking->related->contains($related)) {
            $booking->related()->attach($related);
        }
        if (! $related->related->contains($booking)) {
            $related->related()->attach($booking);
        }

        return redirect()->route('booking.related.index', $booking)
            ->with('alert', [
                'message' => __('Added related booking.'),
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking, Booking $related)
    {
        Gate::authorize('destroy', [Bookable::class, $booking, $related]);

        $booking->related()->detach($related);
        $related->related()->detach($booking);

        return redirect()->route('booking.related.index', $booking)
            ->with('alert', [
                'message' => __('Related booking removed.'),
            ]);
    }
}
