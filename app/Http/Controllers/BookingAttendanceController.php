<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateBookingAttendanceRequest;
use App\Models\Attendance;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingAttendanceController extends Controller
{
    protected function authorizeAttendance(string $action, Booking $booking, User $user)
    {
        if ($attendee = $booking->attendees()->find($user)) {
            $attendance = $attendee->attendance;
        } else {
            $attendance = Attendance::build($booking, $user);
        }
        return $this->authorize($action, $attendance);
    }

    /**
     * Display the resource.
     */
    public function show(Request $request, Booking $booking): View
    {
        $this->authorizeAttendance('view', $booking, $request->user());

        $attendee = $booking->attendees()
            ->find($request->user());

        return view('booking.attendance.show', [
            'booking' => $booking,
            'attendance' => $attendee?->attendance,
            'attendees' => $this->getGuestListAttendees($booking),
        ]);
    }

    /**
     * Update the resource in storage.
     */
    public function update(UpdateBookingAttendanceRequest $request, Booking $booking)
    {
        $this->authorizeAttendance('update', $booking, $request->user());

        if ($booking->isPast()) {
            return redirect()->back()
                ->with('alert.error', __('You cannot change your attendance on bookings in the past.'));
        }
        if ($booking->isCancelled()) {
            return redirect()->back()
                ->with('alert.error', __('You cannot change your attendance on cancelled bookings.'));
        }

        $booking->attendees()->syncWithoutDetaching([
            $request->user()->id => $request->validated()
        ]);

        return redirect()->back()
            ->with('alert.info', __('Updated your attendance successfully.'));
    }


    protected function getGuestListAttendees(Booking $booking): Collection
    {
        $attendees = $booking->attendees()
            ->with('user_accreditations');
        if ($booking->lead_instructor) {
            $attendees->whereNot('users.id', $booking->lead_instructor_id);
        }
        return $attendees
            ->orderBy('booking_user.status')
            ->orderBy('users.name')
            ->get();
    }
}
