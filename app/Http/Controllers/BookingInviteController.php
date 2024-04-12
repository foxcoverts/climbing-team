<?php

namespace App\Http\Controllers;

use App\Enums\AttendeeStatus;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BookingInviteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        Gate::authorize('viewOwn', Booking::class);

        $invites = $this->futureBookingsWithAttendeeStatus($request->user(), AttendeeStatus::NeedsAction);
        $maybes = $this->futureBookingsWithAttendeeStatus($request->user(), AttendeeStatus::Tentative);

        return view('booking.invite.index', [
            'user' => $request->user(),
            'invites' => $invites,
            'maybes' => $maybes,
        ]);
    }

    protected function futureBookingsWithAttendeeStatus(User $user, AttendeeStatus $status): Collection
    {
        return Booking::future()->notCancelled()
            ->attendeeStatus($user, [$status])
            ->with('attendees')
            ->ordered()->get()
            ->groupBy(function (Booking $booking) {
                return $booking->start_at->startOfDay();
            });
    }
}
