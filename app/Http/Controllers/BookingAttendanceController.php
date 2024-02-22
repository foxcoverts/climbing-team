<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateBookingAttendanceRequest;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingAttendanceController extends Controller
{
    /**
     * Display the resource.
     */
    public function show(Request $request, Booking $booking): View
    {
        $attendee = $booking->attendees()
            ->find($request->user());

        return view('booking.attendance.edit', [
            'booking' => $booking,
            'attendance' => $attendee?->attendance,
        ]);
    }

    /**
     * Update the resource in storage.
     */
    public function update(UpdateBookingAttendanceRequest $request, Booking $booking)
    {
        if ($booking->isPast()) {
            return redirect()->back()
                ->with('error', __('You cannot change your attendance on bookings in the past.'));
        }
        if ($booking->isCancelled()) {
            return redirect()->back()
                ->with('error', __('You cannot change your attendance on cancelled bookings.'));
        }

        $booking->attendees()->syncWithoutDetaching([
            $request->user()->id => $request->validated()
        ]);

        return redirect()->back()
            ->with('status', __('Updated your attendance successfully.'));
    }
}
