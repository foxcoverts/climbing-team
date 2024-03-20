<?php

namespace App\Http\Controllers;

use App\Enums\AttendeeStatus;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        Gate::authorize('viewOwn', Booking::class);

        $nextBooking = Booking::future()->notCancelled()
            ->attendeeStatus($request->user(), [
                AttendeeStatus::Accepted, AttendeeStatus::Tentative
            ])
            ->ordered()->first();

        $invite = Booking::future()->notCancelled()
            ->attendeeStatus($request->user(), [
                AttendeeStatus::NeedsAction, AttendeeStatus::Tentative
            ])
            ->ordered()->first();

        return view('dashboard', [
            'next' => $nextBooking,
            'invite' => $invite,
        ]);
    }
}
