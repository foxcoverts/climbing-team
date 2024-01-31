<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('booking.index', [
            'bookings' => Booking::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('booking.create', [
            'booking' => new Booking([
                'start_at' => Carbon::now()->setTime(9, 30, 0, 0),
                'end_at' => Carbon::now()->setTime(11, 30, 0, 0),
                'location' => 'Fox Coverts Campsite'
            ])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $booking = Booking::create(
            $request->safe()
                ->only(['start_at', 'end_at', 'location', 'group_name', 'notes'])
        );

        // event(new Registered($booking));

        return redirect()->route('booking.show', $booking)
            ->with('success', __('Booking created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking): View
    {
        return view('booking.show', [
            'booking' => $booking,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking): View
    {
        return view('booking.edit', [
            'booking' => $booking,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, Booking $booking): RedirectResponse
    {
        $booking->fill($request->validated());
        $booking->save();

        return redirect()->route('booking.show', $booking)
            ->with('status', __('Booking updated successfully'));
    }

    /**
     * Mark the specified resource as deleted.
     */
    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();

        return redirect()->route('booking.index')
            ->with('status', __('Booking deleted.'))
            ->with('restore', route('trash.booking.update', $booking));
    }
}
