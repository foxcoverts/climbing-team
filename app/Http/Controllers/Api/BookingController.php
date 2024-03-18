<?php

namespace App\Http\Controllers\Api;

use App\Enums\AttendeeStatus;
use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ListBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListBookingRequest $request)
    {
        Gate::authorize('viewAny', Booking::class);

        $bookings = Booking::query()
            ->whereDate('start_at', '>=', $request->input('start'))
            ->whereDate('end_at', '<=', $request->input('end'));

        $user = $request->user();
        if ($user->can('manage', Booking::class)) {
            // no filter needed
        } else if ($user->isGuest()) {
            $bookings->whereHas('attendees', function (Builder $query) use ($user) {
                $query
                    ->where('user_id', $user->id)
                    ->whereNot('status', AttendeeStatus::Declined);
            });
        } else {
            $bookings->where(function (Builder $query) use ($user) {
                $query
                    ->whereHas('attendees', function (Builder $query) use ($user) {
                        $query
                            ->where('user_id', $user->id)
                            ->whereNot('status', AttendeeStatus::Declined);
                    })
                    ->orWhereIn('status', [BookingStatus::Confirmed]);
            });
        }

        return BookingResource::collection($bookings->get());
    }
}
