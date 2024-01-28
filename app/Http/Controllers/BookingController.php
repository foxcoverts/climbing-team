<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use Illuminate\View\View;
use Carbon\Carbon;

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
    public function store(StoreBookingRequest $request)
    {
        $booking = Booking::create(
            $request->safe()
                ->only(['start_at', 'end_at', 'location', 'group_name', 'notes'])
        );

        // event(new Registered($booking));

        return redirect()->route('booking.show', $booking)
            ->with('success', 'Booking created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        return view('booking.show', [
            'booking' => $booking
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
