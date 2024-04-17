<?php

namespace App\Http\Controllers;

use App\Enums\AttendeeStatus;
use App\Events\BookingInvite;
use App\Http\Requests\InviteBookingAttendeeRequest;
use App\Models\Attendance;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class BookingAttendeeInviteController extends Controller
{
    /**
     * Show the form for creating the new resource.
     */
    public function create(Booking $booking): View
    {
        Gate::authorize('create', [Attendance::class, $booking]);

        if ($booking->isPast() || $booking->isCancelled()) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('booking.attendee.invite', [
            'booking' => $booking,
            'users' => User::query()
                ->whereNotNull('email_verified_at')
                ->whereDoesntHave('bookings', fn (Builder $query) => $query->where('booking_id', $booking->id))
                ->with('qualifications', 'keys')
                ->orderBy('name')->get(),
        ]);
    }

    /**
     * Store the newly created resource in storage.
     */
    public function store(InviteBookingAttendeeRequest $request, Booking $booking): RedirectResponse
    {
        Gate::authorize('create', [Attendance::class, $booking]);

        if ($booking->isPast()) {
            return redirect()->back()
                ->with('alert.error', __('You cannot invite people to past bookings.'));
        }
        if ($booking->isCancelled()) {
            return redirect()->back()
                ->with('alert.error', __('You cannot invite people to cancelled bookings.'));
        }

        $attendees = $booking->attendees;
        $invites = collect($request->safe()->user_ids)
            ->reject(
                fn (string $id) => $attendees->contains('id', $id)
            )->flatMap(
                fn ($id) => [
                    $id => [
                        'status' => AttendeeStatus::NeedsAction,
                        'token' => Attendance::generateToken(),
                    ],
                ]
            );

        $booking->attendees()->syncWithoutDetaching($invites);
        $booking->refresh();

        foreach (User::findMany($invites->keys()) as $user) {
            event(new BookingInvite($booking, $user));
        }

        return redirect()->route('booking.show', $booking)
            ->with('alert.info', __('Invitations sent.'));
    }
}
