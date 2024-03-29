<?php

namespace App\Http\Controllers;

use App\Enums\AttendeeStatus;
use App\Events\BookingInvite;
use App\Http\Requests\InviteUserBookingRequest;
use App\Models\Attendance;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UserBookingInviteController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(User $user): View
    {
        Gate::authorize('view', $user);
        Gate::authorize('manage', Booking::class);

        $bookings = Booking::future()->notCancelled()
            ->whereDoesntHave('attendees', fn (Builder $query) => $query->where('user_id', $user->id))
            ->ordered()->get();

        return view('user.booking.invite', [
            'user' => $user,
            'bookings' => $bookings,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InviteUserBookingRequest $request, User $user)
    {
        Gate::authorize('view', $user);
        Gate::authorize('manage', Booking::class);

        $bookings = $user->bookings;
        $invites = collect($request->safe()->booking_ids)
            ->reject(
                fn (string $id) => $bookings->contains('id', $id)
            )->flatMap(
                fn ($id) => [
                    $id => [
                        'status' => AttendeeStatus::NeedsAction,
                        'token' => $user->hasVerifiedEmail() ? Attendance::generateToken() : null,
                    ],
                ]
            );

        $user->bookings()->syncWithoutDetaching($invites);
        $user->refresh();

        if (!($user instanceof MustVerifyEmail) || $user->hasVerifiedEmail()) {
            foreach (Booking::findMany($invites->keys()) as $booking) {
                event(new BookingInvite($booking, $user));
            }
        }

        return redirect()->route('user.booking.index', $user)
            ->with('alert.info', __('Invitations sent.'));
    }
}
