<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UserBookingController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(User $user): View
    {
        Gate::authorize('view', $user);
        Gate::authorize('manage', Booking::class);

        $bookings = $user->bookings()->future()->notCancelled()->ordered()
            ->with('attendees')->get()
            ->groupBy(function (Booking $booking) {
                return $booking->start_at->startOfDay();
            });

        return view('user.booking.index', [
            'user' => $user,
            'bookings' => $bookings,
        ]);
    }
}
