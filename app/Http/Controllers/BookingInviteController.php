<?php

namespace App\Http\Controllers;

use App\Enums\AttendeeStatus;
use App\Enums\BookingStatus;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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

        $user = $request->user();
        $bookings = Booking::query()
            ->whereNot('bookings.status', BookingStatus::Cancelled)
            ->whereDate('end_at', '>=', Carbon::now())
            ->whereHas('attendees', function (Builder $query) use ($user) {
                $query->where('user_id', $user->id)
                    ->whereIn('status', [AttendeeStatus::NeedsAction, AttendeeStatus::Tentative]);
            })
            ->get()
            ->groupBy(function (Booking $booking) {
                return $booking->start_at->startOfDay();
            });

        return view('booking.invite.index', [
            'bookings' => $bookings,
        ]);
    }
}
