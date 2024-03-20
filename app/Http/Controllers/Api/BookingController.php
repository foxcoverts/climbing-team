<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ListBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Illuminate\Support\Facades\Gate;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListBookingRequest $request)
    {
        Gate::authorize('viewAny', Booking::class);

        $bookings = Booking::forUser($request->user())
            ->between($request->input('start'), $request->input('end'))
            ->with('attendees');

        return BookingResource::collection($bookings->get());
    }
}
