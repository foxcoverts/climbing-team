<?php

namespace App\Http\Controllers;

use App\Actions\RespondToBookingAction;
use App\Http\Requests\StoreRespondRequest;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class RespondController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Request $request, Booking $booking, User $attendee)
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
     * Update the specified resource in storage.
     */
    public function store(StoreRespondRequest $request, Booking $booking, User $attendee)
    {
        Gate::authorize('update', $attendee->attendance);

        if ($request->input('invite') != $attendee->attendance->token) {
            abort(Response::HTTP_FORBIDDEN, __('Invitation invalid'));
        }

        try {
            $respondToBooking = new RespondToBookingAction($booking);
        } catch (InvalidArgumentException $e) {
            abort(Response::HTTP_FORBIDDEN, __('Invitation expired'));
        }

        $respondToBooking(
            $attendee,
            $request->validated('status')
        );

        $request->session()->put('alert.info', __('Thank-you. Your response has been recorded.'));
        return redirect()->route('booking.invite');
    }
}
