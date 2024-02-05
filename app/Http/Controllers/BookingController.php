<?php

namespace App\Http\Controllers;

use App\Enums\BookingPeriod;
use App\Enums\BookingStatus;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
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
    public function index(Request $request, BookingPeriod $period = BookingPeriod::Future): View
    {
        $bookings = Booking::query();
        switch ($period) {
            case BookingPeriod::Past:
                $bookings->past()->latest('end_at')->latest('start_at');
                break;

            default:
                $bookings->future()->oldest('start_at')->oldest('end_at');
                break;
        }

        if ($request->get('status') == 'all') {
            $status = collect(BookingStatus::cases());
        } else {
            $status = collect($request->get('status'))
                ->map(
                    fn (string $item) => BookingStatus::tryFrom($item)
                )
                ->reject(
                    fn ($status) => is_null($status)
                )
                ->unique();
        }
        if ($status->isEmpty()) {
            $bookings->ofStatus(BookingStatus::Tentative, BookingStatus::Confirmed);
        } else {
            $bookings->ofStatus(...$status->all());
        }

        return view('booking.index', [
            'bookings' => $bookings->get(),
            'period' => $period,
            'status' => $status,
        ]);
    }

    /**
     * Display an iCal listing of the resource.
     */
    public function index_ics(): Response
    {
        return response()
            ->view('booking.ics', [
                'bookings' => Booking::all(),
            ])
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="booking.ics"');
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
    public function show(Booking $booking): View
    {
        return view('booking.show', [
            'booking' => $booking,
        ]);
    }

    /**
     * Display an iCal listing for the specified resource.
     */
    public function show_ics(Booking $booking): Response
    {
        return response()
            ->view('booking.ics', [
                'bookings' => [$booking],
            ])
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', sprintf('attachment; filename="booking-%s.ics"', $booking->id));
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
