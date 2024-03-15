<?php

namespace App\Http\Controllers\Api;

use App\Enums\AttendeeStatus;
use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ListBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListBookingRequest $request)
    {
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
