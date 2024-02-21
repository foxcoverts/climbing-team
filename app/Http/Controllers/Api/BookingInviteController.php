<?php

namespace App\Http\Controllers\Api;

use App\Enums\AttendeeStatus;
use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ListBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder;

class BookingInviteController extends Controller
{
    public function __invoke(ListBookingRequest $request)
    {
        $user = $request->user();
        $bookings = Booking::query()
            ->whereNot('bookings.status', BookingStatus::Cancelled)
            ->whereDate('start_at', '>=', $request->input('start'))
            ->whereDate('end_at', '<=', $request->input('end'))
            ->where(function (Builder $query) use ($user) {
                $query->whereHas('attendees', function (Builder $query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->whereIn('status', [AttendeeStatus::NeedsAction, AttendeeStatus::Tentative]);
                })->orWhereDoesntHave('attendees', function (Builder $query) use ($user) {
                    $query->where('user_id', $user->id);
                });
            });

        return BookingResource::collection($bookings->get());
    }
}
