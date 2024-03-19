<?php

namespace App\Http\Controllers;

use App\Enums\AttendeeStatus;
use App\Enums\BookingStatus;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        $now = Carbon::now();
        $user = $request->user();
        $nextBooking = Booking::query()
            ->whereNot('bookings.status', BookingStatus::Cancelled)
            ->where('end_at', '>=', $now)
            ->whereHas('attendees', function (Builder $query) use ($user) {
                $query->where('user_id', $user->id)
                    ->whereIn('status', [AttendeeStatus::Accepted, AttendeeStatus::Tentative]);
            })
            ->orderBy('start_at')->orderBy('end_at')
            ->first();

        $invite = Booking::query()
            ->whereNot('bookings.status', BookingStatus::Cancelled)
            ->whereDate('end_at', '>=', Carbon::now())
            ->whereHas('attendees', function (Builder $query) use ($user) {
                $query->where('user_id', $user->id)
                    ->whereIn('status', [AttendeeStatus::NeedsAction, AttendeeStatus::Tentative]);
            })
            ->orderBy('start_at')->orderBy('end_at')
            ->first();

        return view('dashboard', [
            'next' => $nextBooking,
            'invite' => $invite,
        ]);
    }
}
