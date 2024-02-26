<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Booking::class, 'booking');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('booking.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('booking.create', [
            'booking' => new Booking([
                'start_date' => Carbon::now(),
                'start_time' => '09:30',
                'end_time' => '11:30',
            ]),
            'activity_suggestions' => Booking::distinct()->orderBy('activity')->get(['activity'])->pluck('activity'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $booking = Booking::create($request->validated());

        // event(new Registered($booking));

        return redirect()->route('booking.show', $booking)
            ->with('success', __('Booking created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Booking $booking): View
    {
        $attendees = $booking->attendees()
            ->with('user_accreditations')
            ->orderBy('booking_user.status')
            ->orderBy('users.name');
        $attendee = $booking->attendees()
            ->with('user_accreditations')
            ->find($request->user());

        return view('booking.show', [
            'booking' => $booking,
            'attendees' => $attendees->get(),
            'attendance' => $attendee?->attendance,
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
            ->with('alert.info', __('Booking updated successfully'));
    }

    /**
     * Mark the specified resource as deleted.
     */
    public function destroy(Booking $booking): RedirectResponse
    {
        if ($booking->status != BookingStatus::Cancelled) {
            return redirect()->back()
                ->with('alert.error', __('You must cancel this booking before you can delete it.'));
        }

        $booking->delete();

        return redirect()->route('booking.index')
            ->with('alert.info', __('Booking deleted.'))
            ->with('restore', route('trash.booking.update', $booking));
    }
}
