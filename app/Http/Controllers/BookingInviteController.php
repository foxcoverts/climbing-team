<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\View\View;

class BookingInviteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        $this->authorize('viewAny', Booking::class);
        return view('booking.invite.index');
    }
}
