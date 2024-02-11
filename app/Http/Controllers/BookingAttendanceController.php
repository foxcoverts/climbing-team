<?php

namespace App\Http\Controllers;

use App\Enums\AttendeeStatus;
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
        $attendee = $booking->attendees()->find($request->user());
        return view('booking.attendance.edit', [
            'booking' => $booking,
            'status' => $attendee?->attendance->status ?? AttendeeStatus::NeedsAction,
        ]);
    }

    /**
     * Update the resource in storage.
     */
    public function update(UpdateBookingAttendanceRequest $request, Booking $booking)
    {
        $booking->attendees()->syncWithoutDetaching([
            $request->user()->id => $request->validated()
        ]);

        return redirect(route('booking.show', $booking))
            ->with('status', __('Updated your attendance successfully.'));
    }
}
