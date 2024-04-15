<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyTrashedBookingRequest;
use App\Http\Requests\RestoreTrashedBookingRequest;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TrashedBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewTrashed', Booking::class);

        $bookings = Booking::onlyTrashed()->future()
            ->with('attendees')
            ->ordered()->get()
            ->groupBy(function ($booking) {
                return $booking->start_at->startOfDay();
            });

        return view('booking.trashed.index', [
            'user' => $request->user(),
            'bookings' => $bookings,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking): View
    {
        Gate::authorize('view', $booking);

        return view('booking.trashed.show', [
            'booking' => $booking,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RestoreTrashedBookingRequest $request, Booking $booking): RedirectResponse
    {
        Gate::authorize('restore', $booking);

        $booking->restore();

        return redirect()->route('booking.show', $booking)
            ->with('alert.info', __('Booking restored successfully.'));
    }

    /**
     * Permanently delete the specified resource from storage.
     */
    public function destroy(DestroyTrashedBookingRequest $request, Booking $booking): RedirectResponse
    {
        Gate::authorize('forceDelete', $booking);

        $booking->forceDelete();

        return redirect()->route('trash.booking.index')
            ->with('alert.info', __('Booking permanently deleted.'));
    }
}
