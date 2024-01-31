<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyTrashedBookingRequest;
use App\Http\Requests\RestoreTrashedBookingRequest;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TrashedBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('trash.booking.index', [
            'bookings' => Booking::onlyTrashed()->get(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking): View
    {
        return view('trash.booking.show', [
            'booking' => $booking,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RestoreTrashedBookingRequest $request, Booking $booking): RedirectResponse
    {
        $booking->restore();

        return redirect(route('booking.show', $booking))
            ->with('status', __('Booking restored successfully.'));
    }

    /**
     * Permanently delete the specified resource from storage.
     */
    public function destroy(DestroyTrashedBookingRequest $request, Booking $booking): RedirectResponse
    {
        $booking->forceDelete();

        return redirect(route('booking.index'))
            ->with('status', __('Booking permanently deleted.'));
    }
}
