<?php

namespace App\Http\Controllers;

use App\Enums\AttendeeStatus;
use App\Http\Requests\StoreBookingAttendeeRequest;
use App\Http\Requests\UpdateBookingAttendeeRequest;
use App\Models\Attendance;
use App\Models\Booking;
use App\Models\Change;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        if ($booking->isPast()) {
            return redirect()->back()
                ->with('alert.error', __('You cannot change attendance on past bookings.'));
        }
        if ($booking->isCancelled()) {
            return redirect()->back()
                ->with('alert.error', __('You cannot change attendance on cancelled bookings.'));
        }

        $user_id = $request->safe()->user_id;
        $options = $request->safe()->except(['user_id']);

        $booking->attendees()->syncWithoutDetaching([
            $user_id => $options,
        ]);

        $change = new Change();
        $change->author()->associate($request->user());
        $booking->changes()->save($change);

        $change_attendee = new Change\Attendee;
        $change_attendee->attendee_id = $user_id;
        $change_attendee->attendee_status = $options['status'];
        $change->attendees()->save($change_attendee);

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

        if ($booking->isPast()) {
            return redirect()->back()
                ->with('alert.error', __('You cannot change attendance on past bookings.'));
        }
        if ($booking->isCancelled()) {
            return redirect()->back()
                ->with('alert.error', __('You cannot change attendance on cancelled bookings.'));
        }

        $booking->attendees()->syncWithoutDetaching([
            $attendee->id => $request->validated(),
        ]);

        $change = new Change();
        $change->author()->associate($request->user());
        $booking->changes()->save($change);

        $change_attendee = new Change\Attendee;
        $change_attendee->attendee()->associate($attendee);
        $change_attendee->attendee_status = $request->validated()['status'];
        $change->attendees()->save($change_attendee);

        return redirect()->route('booking.show', $booking)
            ->with('alert.info', __('Updated attendance successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Booking $booking, User $attendee): RedirectResponse
    {
        $this->authorize('delete', $attendee->attendance);

        $booking->attendees()->detach($attendee);

        if ($attendee->attendance != AttendeeStatus::Declined) {
            $change = new Change();
            $change->author()->associate($request->user());
            $booking->changes()->save($change);

            $change_attendee = new Change\Attendee;
            $change_attendee->attendee()->associate($attendee);
            $change_attendee->attendee_status = AttendeeStatus::Declined;
            $change->attendees()->save($change_attendee);
        }

        return redirect()->route('booking.show', $booking)
            ->with('alert.info', __('Removed attendee successfully.'));
    }
}
