<?php

namespace App\Http\Controllers;

use App\Actions\RespondToBookingAction;
use App\Http\Requests\UpdateBookingAttendanceRequest;
use App\Models\Attendance;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

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

        if ($request->session()->has('url.referer')) {
            $request->session()->reflash('url.referer');
        } else if ($request->headers->get('referer')) {
            $referer_host = parse_url($request->headers->get('referer'), PHP_URL_HOST);
            if ($request->getHttpHost() == $referer_host) {
                $request->session()->flash('url.referer', $request->headers->get('referer'));
            }
        }
        if (!$request->session()->has('url.referer')) {
            $request->session()->flash('url.referer', route('booking.invite'));
        }

        $attendee = $booking->attendees()->find($request->user());

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

        try {
            $respondToBooking = new RespondToBookingAction($booking);
        } catch (InvalidArgumentException $e) {
            return redirect()->back()->with('alert.error', $e->getMessage());
        }

        $respondToBooking(
            $request->user(),
            $request->validated('status'),
        );

        return redirect($request->session()->get('url.referer', url()->previous()))
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
