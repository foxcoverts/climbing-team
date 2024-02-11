<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingAttendeeRequest;
use App\Http\Requests\UpdateBookingAttendanceRequest;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookingAttendeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Booking $booking): RedirectResponse
    {
        return redirect(route('booking.show', $booking));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Booking $booking): View
    {
        return view('booking.attendee.create', [
            'booking' => $booking,
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingAttendeeRequest $request, Booking $booking): RedirectResponse
    {
        $user_id = $request->safe()->only('user_id')['user_id'];
        $options = $request->safe()->except(['user_id']);

        $booking->attendees()->syncWithoutDetaching([
            $user_id => $options,
        ]);

        return redirect(route('booking.show', $booking))
            ->with('status', __('Added attendee successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking, User $attendee): View
    {
        return view('booking.attendee.show', [
            'booking' => $booking,
            'attendee' => $attendee,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking, User $attendee): View
    {
        return view('booking.attendee.edit', [
            'booking' => $booking,
            'attendee' => $attendee,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingAttendanceRequest $request, Booking $booking, User $attendee): RedirectResponse
    {
        $booking->attendees()->syncWithoutDetaching([
            $attendee->id => $request->validated(),
        ]);

        return redirect(route('booking.show', $booking))
            ->with('status', __('Updated attendance successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking, User $attendee): RedirectResponse
    {
        $booking->attendees()->detach($attendee);

        return redirect(route('booking.show', $booking))
            ->with('status', __('Removed attendee successfully.'));
    }
}
