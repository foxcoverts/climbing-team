<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ListBookingRequest;
use App\Http\Resources\TrashedBookingResource;
use App\Models\Booking;

class TrashedBookingController extends Controller
{
    public function __invoke(ListBookingRequest $request)
    {
        $bookings = Booking::onlyTrashed()
            ->whereDate('start_at', '>=', $request->input('start'))
            ->whereDate('end_at', '<=', $request->input('end'));

        return TrashedBookingResource::collection($bookings->get());
    }
}
