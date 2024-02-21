<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class BookingInviteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        return view('booking.invite.index');
    }
}
