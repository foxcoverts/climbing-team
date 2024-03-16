<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyTrashedBookingRequest;
use App\Http\Requests\RestoreTrashedBookingRequest;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TrashedBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->authorize('viewTrashed', Booking::class);

        $bookings = Booking::onlyTrashed()
            ->whereDate('end_at', '>=', Carbon::now())
            ->get()
            ->groupBy(function ($booking) {
                return $booking->start_at->startOfDay();
            });

        return view('booking.trashed.index', [
            'bookings' => $bookings,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking): View
    {
        $this->authorize('view', $booking);
        return view('booking.trashed.show', [
            'booking' => $booking,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RestoreTrashedBookingRequest $request, Booking $booking): RedirectResponse
    {
        $this->authorize('restore', $booking);
        $booking->restore();

        return redirect()->route('booking.show', $booking)
            ->with('alert.info', __('Booking restored successfully.'));
    }

    /**
     * Permanently delete the specified resource from storage.
     */
    public function destroy(DestroyTrashedBookingRequest $request, Booking $booking): RedirectResponse
    {
        $this->authorize('forceDelete', $booking);
        $booking->forceDelete();

        return redirect()->route('trash.booking.trash')
            ->with('alert.info', __('Booking permanently deleted.'));
    }
}
