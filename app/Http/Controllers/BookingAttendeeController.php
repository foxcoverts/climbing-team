<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingAttendeeRequest;
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
    public function index(Booking $booking)
    {
        //
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
            ->with('status', 'Added attendee successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking, User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking, User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking, User $user)
    {
        //
    }
}
