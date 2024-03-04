<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRespondRequest;
use App\Models\Booking;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Symfony\Component\HttpFoundation\Response;

class RespondController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Booking $booking, User $attendee)
    {
        $this->authorize('update', $attendee->attendance);

        if ($booking->isPast() || $booking->isCancelled()) {
            abort(Response::HTTP_FORBIDDEN, 'Invitation expired');
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

        if ($booking->isPast() || $booking->isCancelled()) {
            abort(Response::HTTP_FORBIDDEN, 'Invitation expired');
        }

        $booking->attendees()->syncWithoutDetaching([
            $attendee->id => $request->validated(),
        ]);

        $request->session()->put('alert.info', __('Thank-you. Your response has been recorded.'));
        return redirect()->route('booking.invite');
    }
}
