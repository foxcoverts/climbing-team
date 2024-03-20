<?php

namespace App\Http\Controllers;

use App\Enums\AttendeeStatus;
use App\Enums\BookingStatus;
use App\Events\BookingCancelled;
use App\Events\BookingChanged;
use App\Events\BookingConfirmed;
use App\Events\BookingInvite;
use App\Events\BookingRestored;
use App\Forms\BookingForm;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Attendance;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

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
     *
     * @param BookingStatus $status
     * @return View
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

        return view('booking.create', [
            'form' => new BookingForm(new Booking([
                'start_at' => Carbon::parse('Saturday 09:30', $request->user()->timezone)->utc(),
                'end_at' => Carbon::parse('Saturday 11:30', $request->user()->timezone)->utc(),
                'status' => BookingStatus::Tentative,
            ])),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request): RedirectResponse
    {
        Gate::authorize('create', Booking::class);

        $attributes = $request->safe()->except('start_date', 'start_time', 'end_time');
        $attributes['start_at'] = Carbon::parse(
            $request->safe()->start_date . 'T' . $request->safe()->start_time,
            $request->user()->timezone
        )->utc();
        $attributes['end_at'] = Carbon::parse(
            $request->safe()->start_date . 'T' . $request->safe()->end_time,
            $request->user()->timezone
        )->utc();

        $booking = Booking::create($attributes);

        return redirect()->route('booking.show', $booking)
            ->with('alert.info', __('Booking created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Booking $booking): View
    {
        Gate::authorize('view', $booking);

        $attendee = $booking->attendees()
            ->with('user_accreditations')
            ->find($request->user());

        return view('booking.show', [
            'booking' => $booking,
            'guest_list' => $this->getGuestListAttendees($booking),
            'attendance' => $attendee?->attendance,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking): View
    {
        Gate::authorize('update', $booking);

        return view('booking.edit', [
            'form' => new BookingForm($booking),
            'guest_list' => $this->getGuestListAttendees($booking),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, Booking $booking): RedirectResponse
    {
        Gate::authorize('update', $booking);

        $originalStatus = $booking->status;
        $booking->fill($request->safe()->except('start_date', 'start_time', 'end_time'));
        if ($request->safe()->has('start_time')) {
            $booking->start_at = Carbon::parse(
                $request->safe()->start_date . 'T' . $request->safe()->start_time,
                $request->user()->timezone
            )->utc();
        }
        if ($request->safe()->has('end_time')) {
            $booking->end_at = Carbon::parse(
                $request->safe()->start_date . 'T' . $request->safe()->end_time,
                $request->user()->timezone
            )->utc();
        }
        if (($originalStatus == BookingStatus::Cancelled) && ($booking->status != BookingStatus::Cancelled)) {
            $booking->lead_instructor_id = null;
        }
        $booking->save();

        if ($originalStatus != $booking->status) {
            if ($originalStatus == BookingStatus::Cancelled) {
                // When restoring a cancelled booking, re-invite any 'Going' and 'Maybe' attendees.
                $attendees = $booking->attendees->mapWithKeys(function ($attendee) {
                    if ($attendee->attendance->status == AttendeeStatus::Declined) {
                        return [$attendee->id => ['status' => AttendeeStatus::Declined]];
                    }
                    if ($attendee->hasVerifiedEmail()) {
                        return [$attendee->id => ['status' => AttendeeStatus::NeedsAction]];
                    }
                    return [];
                });
                $booking->attendees()->sync(
                    $attendees->all()
                );
                $booking->refresh();
                $invites = $attendees->filter(function ($meta) {
                    return $meta['status'] == AttendeeStatus::NeedsAction;
                })->keys()->all();
                foreach (User::find($invites) as $user) {
                    event(new BookingInvite($booking, $user));
                }
                event(new BookingRestored($booking, $booking->getChanges(), $request->user()));
            } else if ($booking->isConfirmed()) {
                event(new BookingConfirmed($booking, $booking->getChanges(), $request->user()));
            } else if ($booking->isCancelled()) {
                // Remove attendees with outstanding invites.
                Attendance::where('booking_id', $booking->id)
                    ->where('status', AttendeeStatus::NeedsAction)
                    ->delete();
                $booking->refresh();
                event(new BookingCancelled($booking, $booking->getChanges(), $request->user()));
            }
        } else if ($booking->wasChanged(['sequence'])) {
            event(new BookingChanged($booking, $booking->getChanges(), $request->user()));
        }

        return redirect()->route('booking.show', $booking)
            ->with('alert.info', __('Booking updated successfully'));
    }

    /**
     * Mark the specified resource as deleted.
     */
    public function destroy(Booking $booking): RedirectResponse
    {
        Gate::authorize('delete', $booking);

        if ($booking->status != BookingStatus::Cancelled) {
            return redirect()->back()
                ->with('alert.error', __('You must cancel this booking before you can delete it.'));
        }

        $booking->delete();

        return redirect()->route('booking.calendar')
            ->with('alert.info', __('Booking deleted.'))
            ->with('restore', route('trash.booking.update', $booking));
    }

    protected function getGuestListAttendees(Booking $booking): Collection
    {
        $attendees = $booking->attendees()
            ->with('user_accreditations');
        if ($booking->lead_instructor) {
            $attendees->whereNot('users.id', $booking->lead_instructor_id);
        }
        return $attendees
            ->orderBy('booking_user.status')
            ->orderBy('users.name')
            ->get();
    }
}
