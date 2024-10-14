<?php

namespace App\Http\Controllers;

use App\Actions\RespondToBookingAction;
use App\Enums\BookingAttendeeStatus;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class RespondController extends Controller
{
    /**
     * Display the invitation.
     */
    public function show(Request $request, Booking $booking, User $attendee): View
    {
        Gate::authorize('update', $attendee->attendance);

        if ($request->input('invite') != $attendee->attendance->token) {
            return redirect()->route('booking.attendance.edit', $booking);
        }

        try {
            new RespondToBookingAction($booking);
        } catch (InvalidArgumentException $e) {
            abort(Response::HTTP_FORBIDDEN, __('Invitation expired'));
        }

        return view('respond.show', [
            'booking' => $booking,
            'user' => $attendee,
        ]);
    }

    /**
     * Accept the invitation.
     */
    public function accept(Request $request, Booking $booking, User $attendee): View
    {
        return $this->viewAction($request, $booking, $attendee, BookingAttendeeStatus::Accepted);
    }

    /**
     * Respond tentatively to the invitation.
     */
    public function tentative(Request $request, Booking $booking, User $attendee): View
    {
        return $this->viewAction($request, $booking, $attendee, BookingAttendeeStatus::Tentative);
    }

    /**
     * Decline the invitation.
     */
    public function decline(Request $request, Booking $booking, User $attendee): View
    {
        return $this->viewAction($request, $booking, $attendee, BookingAttendeeStatus::Declined);
    }

    /**
     * Record the attendee's response.
     */
    public function store(Request $request, Booking $booking, User $attendee): RedirectResponse
    {
        Gate::authorize('update', $attendee->attendance);

        if ($request->input('invite') != $attendee->attendance->token) {
            abort(Response::HTTP_FORBIDDEN, __('Invitation invalid'));
        }

        if ($request->input('sequence') != $booking->sequence) {
            return redirect()
                ->route('respond', [
                    $booking, $attendee,
                    'invite' => $attendee->attendance->token,
                ])
                ->with(['alert' => [
                    'message' => __('This booking has changed. Check the details below before you confirm your attendance.'),
                    'type' => 'error',
                ]]);
        }

        try {
            $respondToBooking = new RespondToBookingAction($booking);
        } catch (InvalidArgumentException $e) {
            abort(Response::HTTP_FORBIDDEN, __('Invitation expired'));
        }

        $respondToBooking(
            $attendee,
            BookingAttendeeStatus::tryFrom($request->input('status')),
        );

        $request->session()->put('alert', [
            'message' => __('Thank-you. Your response has been recorded.'),
            'type' => 'info',
        ]);

        return redirect()->route('booking.show', [$booking, 'responded' => 1]);
    }

    /**
     * View the action form that will auto-submit the response.
     */
    protected function viewAction(Request $request, Booking $booking, User $attendee, BookingAttendeeStatus $status): View
    {
        Gate::authorize('update', $attendee->attendance);

        if ($request->input('invite') != $attendee->attendance->token) {
            abort(Response::HTTP_FORBIDDEN, __('Invitation invalid'));
        }

        if ($request->input('sequence') != $booking->sequence) {
            return redirect()
                ->route('respond', [
                    $booking, $attendee,
                    'invite' => $attendee->attendance->token,
                ])
                ->with(['alert' => [
                    'message' => __('This booking has changed. Check the details below before you confirm your attendance.'),
                    'type' => 'error',
                ]]);
        }

        try {
            new RespondToBookingAction($booking);
        } catch (InvalidArgumentException $e) {
            abort(Response::HTTP_FORBIDDEN, __('Invitation expired'));
        }

        return view('respond.action', [
            'booking' => $booking,
            'user' => $attendee,
            'status' => $status,
        ]);
    }
}
