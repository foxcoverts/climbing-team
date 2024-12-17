<?php

namespace App\Http\Controllers;

use App\Enums\BookingAttendeeStatus;
use App\Enums\BookingStatus;
use App\Events\BookingCancelled;
use App\Events\BookingChanged;
use App\Events\BookingConfirmed;
use App\Events\BookingInvite;
use App\Events\BookingRestored;
use App\Forms\BookingForm;
use App\Http\Requests\DestroyBookingRequest;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\BookingAttendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BookingController extends Controller
{
    /**
     * Display a calendar of the Bookings.
     */
    public function calendar(): View
    {
        Gate::authorize('viewAny', Booking::class);

        return view('booking.calendar');
    }

    public function confirmed(Request $request): View
    {
        return $this->index($request, BookingStatus::Confirmed);
    }

    public function tentative(Request $request): View
    {
        return $this->index($request, BookingStatus::Tentative);
    }

    public function cancelled(Request $request): View
    {
        return $this->index($request, BookingStatus::Cancelled);
    }

    /**
     * Display list of Bookings for the given status.
     */
    protected function index(Request $request, BookingStatus $status): View
    {
        Gate::authorize('viewAny', [Booking::class, $status]);

        $bookings = Booking::future()
            ->forUser($request->user())
            ->where('bookings.status', $status)
            ->with('attendees')
            ->ordered()->get()
            ->groupBy(function (Booking $booking) {
                return $booking->start_at->startOfDay();
            });

        return view('booking.index', [
            'user' => $request->user(),
            'bookings' => $bookings,
            'status' => $status,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', Booking::class);

        $timezone = $request->user()->timezone;

        return view('booking.create', [
            'form' => new BookingForm(new Booking([
                'start_at' => Carbon::parse('Saturday 09:30', $timezone)->utc(),
                'end_at' => Carbon::parse('Saturday 11:30', $timezone)->utc(),
                'status' => BookingStatus::Tentative,
                'timezone' => $timezone,
            ])),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request): RedirectResponse
    {
        Gate::authorize('create', Booking::class);

        $booking = new Booking(
            $request->safe()->except('start_date', 'start_time', 'end_time')
        );
        $booking->start_at = Carbon::parse(
            $request->safe()->start_date.'T'.$request->safe()->start_time,
            $booking->timezone
        )->utc();
        $booking->end_at = Carbon::parse(
            $request->safe()->start_date.'T'.$request->safe()->end_time,
            $booking->timezone
        )->utc();
        $booking->save();

        return redirect()->route('booking.show', $booking)
            ->with('alert.info', __('Booking created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Booking $booking): Response|View
    {
        if (Gate::check('view', $booking)) {
            return view('booking.show', [
                'booking' => $booking->load('changes'),
                'currentUser' => $request->user(),
            ]);
        } elseif (Auth::guest()) {
            return view('booking.preview', [
                'booking' => $booking,
                'responded' => $request->get('responded'),
            ]);
        } else {
            return response()->view('booking.forbidden', [
                'booking' => $booking,
            ], Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Booking $booking): View
    {
        Gate::authorize('update', $booking);

        return view('booking.edit', [
            'form' => new BookingForm($booking),
            'currentUser' => $request->user(),
        ]);
    }

    /**
     * Show the form for cancelling a booking.
     */
    public function cancel(Request $request, Booking $booking): View|RedirectResponse
    {
        Gate::authorize('update', $booking);

        if ($booking->isCancelled()) {
            return redirect()->route('booking.edit', $booking)->with('alert', [
                'message' => __('This booking has already been cancelled.'),
                'type' => 'error',
            ]);
        }

        return view('booking.cancel', [
            'ajax' => $request->ajax(),
            'booking' => $booking,
            'currentUser' => $request->user(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, Booking $booking): RedirectResponse
    {
        Gate::authorize('update', $booking);

        $originalStatus = $booking->status;
        $booking->fill($request->safe()->except('start_date', 'start_time', 'end_time', 'reason'));
        if ($request->safe()->has('start_time')) {
            $booking->start_at = Carbon::parse(
                $request->safe()->start_date.'T'.$request->safe()->start_time,
                $booking->timezone
            )->utc();
        }
        if ($request->safe()->has('end_time')) {
            $booking->end_at = Carbon::parse(
                $request->safe()->start_date.'T'.$request->safe()->end_time,
                $booking->timezone
            )->utc();
        }
        if (($originalStatus == BookingStatus::Cancelled) && ($booking->status != BookingStatus::Cancelled)) {
            $booking->lead_instructor_id = null;
        }
        $booking->save();

        $alertMessage = __('Booking updated');
        if ($originalStatus != $booking->status) {
            if ($originalStatus == BookingStatus::Cancelled) {
                // When restoring a cancelled booking, re-invite any 'Going' and 'Maybe' attendees.
                $attendees = $booking->attendees->mapWithKeys(function ($attendee) {
                    if ($attendee->attendance->status == BookingAttendeeStatus::Declined) {
                        return [$attendee->id => ['status' => BookingAttendeeStatus::Declined]];
                    }
                    if ($attendee->hasVerifiedEmail()) {
                        return [$attendee->id => ['status' => BookingAttendeeStatus::NeedsAction]];
                    }

                    return [];
                });
                $booking->attendees()->sync(
                    $attendees->all()
                );
                $booking->refresh();
                $invites = $attendees->filter(function ($meta) {
                    return $meta['status'] == BookingAttendeeStatus::NeedsAction;
                })->keys()->all();
                foreach (User::find($invites) as $user) {
                    event(new BookingInvite($booking, $user));
                }
                event(new BookingRestored($booking, $request->user(), $booking->getChanges()));
                $alertMessage = __('Booking restored');
            } elseif ($booking->isConfirmed()) {
                event(new BookingConfirmed($booking, $request->user(), $booking->getChanges()));
                $alertMessage = __('Booking confirmed');
            } elseif ($booking->isCancelled()) {
                // Remove attendees with outstanding invites.
                BookingAttendance::where('booking_id', $booking->id)
                    ->where('status', BookingAttendeeStatus::NeedsAction)
                    ->delete();
                $booking->refresh();
                event(new BookingCancelled($booking, $request->user(), $request->safe()->reason));
                $alertMessage = __('Booking cancelled');
            }
        } elseif ($booking->wasChanged(['sequence'])) {
            event(new BookingChanged($booking, $request->user(), $booking->getChanges()));
        }

        return redirect()->route('booking.show', $booking)->with('alert', ['message' => $alertMessage]);
    }

    /**
     * Mark the specified resource as deleted.
     */
    public function destroy(DestroyBookingRequest $request, Booking $booking): RedirectResponse
    {
        Gate::authorize('delete', $booking);

        if ($booking->status != BookingStatus::Cancelled) {
            return redirect()->back()
                ->with('alert.error', __('You must cancel this booking before you can delete it.'));
        }

        $booking->delete();

        return redirect()->route('booking.calendar')
            ->with('alert', [
                'info' => __('Booking deleted.'),
            ]);
    }
}
