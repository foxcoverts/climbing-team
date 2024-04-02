<?php

namespace App\Http\Controllers;

use App\Actions\RespondToBookingAction;
use App\Http\Requests\UpdateBookingAttendanceRequest;
use App\Models\Attendance;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use InvalidArgumentException;

class BookingAttendanceController extends Controller
{
    /**
     * Display the resource.
     */
    public function edit(Request $request, Booking $booking): View
    {
        $this->authorizeAttendance('view', $booking, $request->user());

        if ($request->session()->has('url.referer')) {
            $request->session()->reflash('url.referer');
        } elseif ($request->headers->get('referer')) {
            $referer_host = parse_url($request->headers->get('referer'), PHP_URL_HOST);
            if ($request->getHttpHost() == $referer_host) {
                $request->session()->flash('url.referer', $request->headers->get('referer'));
            }
        }
        if (! $request->session()->has('url.referer')) {
            $request->session()->flash('url.referer', route('booking.invite'));
        }

        $attendance = Attendance::where([
            'booking_id' => $booking->id,
            'user_id' => $request->user()->id,
        ])->first();

        return view('booking.attendance', [
            'booking' => $booking,
            'attendance' => $attendance,
            'currentUser' => $request->user(),
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

    protected function authorizeAttendance(string $action, Booking $booking, User $user)
    {
        if ($attendee = $booking->attendees()->find($user)) {
            $attendance = $attendee->attendance;
        } else {
            $attendance = Attendance::build($booking, $user);
        }
        Gate::authorize($action, $attendance);
    }
}
