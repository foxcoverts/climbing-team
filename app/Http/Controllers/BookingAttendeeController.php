<?php

namespace App\Http\Controllers;

use App\Actions\RespondToBookingAction;
use App\Enums\BookingAttendeeStatus;
use App\Models\Booking;
use App\Models\BookingAttendance;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BookingAttendeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Booking $booking): View
    {
        Gate::authorize('rollcall', [BookingAttendance::class, $booking]);

        $attendees = $booking->attendees
            ->where('id', '!=', $booking->lead_instructor_id)
            ->sortBy([
                fn ($a, $b) => $a->attendance->status->compare($b->attendance->status),
                'name',
            ])
            ->groupBy('attendance.status');
        $nonAttendees = User::whereNotIn('id', $booking->attendees->pluck('id'))
            ->orderBy('name')
            ->get();

        return view('booking.attendee.index', [
            'booking' => $booking,
            'lead_instructor' => $booking->lead_instructor,
            'attendees' => $attendees,
            'nonAttendees' => $nonAttendees,
        ]);
    }

    public function updateMany(Request $request, Booking $booking): RedirectResponse
    {
        Gate::authorize('rollcall', [BookingAttendance::class, $booking]);

        $validated = $request->validate([
            'attendee_ids' => ['required', 'list'],
            'attendee_ids.*' => ['string', 'exists:App\\Models\\User,id'],
        ]);

        $respondToBooking = new RespondToBookingAction($booking, $request->user());
        foreach ($validated['attendee_ids'] as $id) {
            $respondToBooking($id, BookingAttendeeStatus::Accepted);
        }

        return redirect()->route('booking.show', $booking)
            ->with('alert.info', __('Attendance recorded.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking, User $attendee): View
    {
        Gate::authorize('view', $attendee->attendance);

        return view('booking.attendee.show', [
            'booking' => $booking,
            'attendee' => $attendee->load('qualifications', 'qualifications.detail'),
        ]);
    }
}
