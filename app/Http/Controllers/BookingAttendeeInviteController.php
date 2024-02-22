<?php

namespace App\Http\Controllers;

use App\Enums\AttendeeStatus;
use App\Http\Requests\InviteBookingAttendeeRequest;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class BookingAttendeeInviteController extends Controller
{
    /**
     * Show the form for creating the new resource.
     */
    public function create(Booking $booking): View
    {
        if ($booking->isPast() || $booking->isCancelled()) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('booking.attendee.invite', [
            'booking' => $booking,
            'users' => User::whereDoesntHave('bookings', function (Builder $query) use ($booking) {
                $query->where('booking_id', $booking->id);
            })->orderBy('name')->get(),
        ]);
    }

    /**
     * Store the newly created resource in storage.
     */
    public function store(InviteBookingAttendeeRequest $request, Booking $booking): RedirectResponse
    {
        if ($booking->isPast()) {
            return redirect()->back()
                ->with('error', __('You cannot invite people to past bookings.'));
        }
        if ($booking->isCancelled()) {
            return redirect()->back()
                ->with('error', __('You cannot invite people to cancelled bookings.'));
        }

        $attendees = $booking->attendees;
        $user_ids = collect($request->safe()['user_ids']);

        $user_ids->reject(function (string $id) use ($attendees): bool {
            return $attendees->contains('id', $id);
        });

        $booking->attendees()->syncWithPivotValues(
            $user_ids,
            ['status' => AttendeeStatus::NeedsAction],
            false
        );

        // @todo fire invited event & background job.

        return redirect()->route('booking.show', $booking)
            ->with('status', 'Attendees invited successfully.');
    }
}
