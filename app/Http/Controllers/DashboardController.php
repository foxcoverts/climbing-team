<?php

namespace App\Http\Controllers;

use App\Enums\BookingAttendeeStatus;
use App\Models\Booking;
use App\Models\NewsPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        Gate::authorize('viewOwn', Booking::class);

        $nextBooking = Booking::future()->notCancelled()
            ->attendeeStatus($request->user(), [
                BookingAttendeeStatus::Accepted, BookingAttendeeStatus::Tentative,
            ])
            ->ordered()->first();

        $invite = Booking::future()->notCancelled()
            ->attendeeStatus($request->user(), [
                BookingAttendeeStatus::NeedsAction, BookingAttendeeStatus::Tentative,
            ])
            ->ordered()->first();

        $newsPost = NewsPost::orderByDesc('created_at')->first();

        return view('dashboard', [
            'next' => $nextBooking,
            'invite' => $invite,
            'post' => $newsPost,
        ]);
    }
}
