<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BookingLinkController extends Controller
{
    public function __invoke(Request $request): View
    {
        $currentUser = $request->user();
        if (is_null($currentUser->ical_token)) {
            $currentUser->ical_token = User::generateToken();
            $currentUser->save();
        }

        return view('booking.links', [
            'currentUser' => $currentUser,
        ]);
    }
}
