<?php

namespace App\Http\Controllers;

use App\Enums\AttendeeStatus;
use App\Enums\BookingStatus;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BookingRotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request)
    {
        $this->authorize('viewOwn', Booking::class);

        $user = $request->user();
        $bookings = Booking::query()
            ->whereNot('bookings.status', BookingStatus::Cancelled)
            ->whereDate('end_at', '>=', Carbon::now())
            ->whereHas('attendees', function (Builder $query) use ($user) {
                $query->where('user_id', $user->id)
                    ->whereIn('status', [AttendeeStatus::Accepted, AttendeeStatus::Tentative]);
            })
            ->get()
            ->groupBy(function (Booking $booking) {
                return $booking->start_at->startOfDay();
            });

        return view('booking.rota.index', [
            'bookings' => $bookings,
        ]);
    }
}
