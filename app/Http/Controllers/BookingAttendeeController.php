<?php

namespace App\Http\Controllers;

use App\Actions\RespondToBookingAction;
use App\Enums\AttendeeStatus;
use App\Http\Requests\StoreBookingAttendeeRequest;
use App\Http\Requests\UpdateBookingAttendeeRequest;
use App\Models\Attendance;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class BookingAttendeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Booking $booking): RedirectResponse
    {
        $this->authorize('viewAny', [Attendance::class, $booking]);

        return redirect(status: Response::HTTP_SEE_OTHER)->route('booking.show', $booking);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Booking $booking): View
    {
        $this->authorize('create', [Attendance::class, $booking]);

        if ($booking->isPast() || $booking->isCancelled()) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('booking.attendee.create', [
            'booking' => $booking,
            'users' => User::query()
                ->whereDoesntHave('bookings', function (Builder $query) use ($booking) {
                    $query->where('booking_id', $booking->id);
                })
                ->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingAttendeeRequest $request, Booking $booking): RedirectResponse
    {
        $this->authorize('create', [Attendance::class, $booking]);

        $user_id = $request->safe()->user_id;
        $options = $request->safe()->except(['user_id']);

        try {
            $respondToBooking = new RespondToBookingAction($booking, $request->user());
        } catch (InvalidArgumentException $e) {
            return redirect()->back()->with('alert.error', $e->getMessage());
        }

        $respondToBooking(
            $user_id,
            $options['status']
        );

        return redirect()->route('booking.show', $booking)
            ->with('alert.info', __('Added attendee successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking, User $attendee): View
    {
        $this->authorize('view', $attendee->attendance);

        return view('booking.attendee.show', [
            'booking' => $booking,
            'attendee' => $attendee,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingAttendeeRequest $request, Booking $booking, User $attendee): RedirectResponse
    {
        $this->authorize('update', $attendee->attendance);

        try {
            $respondToBooking = new RespondToBookingAction($booking, $request->user());
        } catch (InvalidArgumentException $e) {
            return redirect()->back()->with('alert.error', $e->getMessage());
        }

        $respondToBooking(
            $attendee,
            $request->validated('status'),
        );

        return redirect()->route('booking.show', $booking)
            ->with('alert.info', __('Updated attendance successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Booking $booking, User $attendee): RedirectResponse
    {
        $this->authorize('delete', $attendee->attendance);

        if ($attendee->attendance != AttendeeStatus::Declined) {
            try {
                $respondToBooking = new RespondToBookingAction($booking, $request->user());
            } catch (InvalidArgumentException $e) {
                return redirect()->back()->with('alert.error', $e->getMessage());
            }
            $respondToBooking($attendee, AttendeeStatus::Declined);
        }
        $booking->attendees()->detach($attendee);

        return redirect()->route('booking.show', $booking)
            ->with('alert.info', __('Removed attendee successfully.'));
    }
}
