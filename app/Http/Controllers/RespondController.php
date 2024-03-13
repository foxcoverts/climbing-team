<?php

namespace App\Http\Controllers;

use App\Actions\RespondToBookingAction;
use App\Http\Requests\StoreRespondRequest;
use App\Models\Booking;
use App\Models\User;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class RespondController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Booking $booking, User $attendee)
    {
        $this->authorize('update', $attendee->attendance);

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
        $this->authorize('update', $attendee->attendance);

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
