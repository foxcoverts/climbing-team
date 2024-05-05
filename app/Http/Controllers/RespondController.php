<?php

namespace App\Http\Controllers;

use App\Actions\RespondToBookingAction;
use App\Enums\AttendeeStatus;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use InvalidArgumentException;
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Symfony\Component\HttpFoundation\Response;

class RespondController extends Controller
{
    /**
     * Display the invitation.
     */
    public function show(Request $request, Booking $booking, User $attendee)
    {
        Gate::authorize('update', $attendee->attendance);

        if ($request->input('invite') != $attendee->attendance->token) {
            return redirect()->route('booking.attendance.edit', $booking);
        }

        try {
            new RespondToBookingAction($booking);
        } catch (InvalidArgumentException $e) {
            abort(Response::HTTP_FORBIDDEN, __('Invitation expired'));
        }

        return view('respond.show', [
            'booking' => $booking,
            'user' => $attendee,
        ]);
    }

    /**
     * Accept the invitation.
     */
    public function accept(Request $request, Booking $booking, User $attendee): RedirectResponse
    {
        return $this->avoidBots($request, $booking, $attendee)
            ?? $this->store($request, $booking, $attendee, AttendeeStatus::Accepted);
    }

    /**
     * Respond tentatively to the invitation.
     */
    public function tentative(Request $request, Booking $booking, User $attendee): RedirectResponse
    {
        return $this->avoidBots($request, $booking, $attendee)
            ?? $this->store($request, $booking, $attendee, AttendeeStatus::Tentative);
    }

    /**
     * Decline the invitation.
     */
    public function decline(Request $request, Booking $booking, User $attendee): RedirectResponse
    {
        return $this->avoidBots($request, $booking, $attendee)
            ?? $this->store($request, $booking, $attendee, AttendeeStatus::Declined);
    }

    /**
     * Respond to the invitation.
     */
    public function respond(Request $request, Booking $booking, User $attendee): RedirectResponse
    {
        $status = AttendeeStatus::tryFrom($request->input('status'));

        return $this->store($request, $booking, $attendee, $status);
    }

    /**
     * Prevent bots from using the GET links to respond to a booking by
     * redirecting them to the form and requiring active input from them.
     *
     * These GET links are provided in emails, and some email services visit
     * every link to check they are not suspicious, sometimes causing actions
     * to be taken.
     */
    protected function avoidBots(Request $request, Booking $booking, User $attendee): ?RedirectResponse
    {
        $agent = new CrawlerDetect($request->server());

        if ($agent->isCrawler()) {
            return redirect()->route('respond', [
                $booking, $attendee,
                'invite' => $attendee->attendance->token,
            ]);
        }

        return null;
    }

    /**
     * Record the attendee's response.
     */
    protected function store(Request $request, Booking $booking, User $attendee, AttendeeStatus $status): RedirectResponse
    {
        Gate::authorize('update', $attendee->attendance);

        if ($request->input('invite') != $attendee->attendance->token) {
            abort(Response::HTTP_FORBIDDEN, __('Invitation invalid'));
        }

        if ($request->input('sequence') != $booking->sequence) {
            return redirect()
                ->route('respond', [
                    $booking, $attendee,
                    'invite' => $attendee->attendance->token,
                ])
                ->with(['alert' => [
                    'message' => __('This booking has changed. Check the details below before you confirm your attendance.'),
                    'type' => 'error',
                ]]);
        }

        try {
            $respondToBooking = new RespondToBookingAction($booking);
        } catch (InvalidArgumentException $e) {
            abort(Response::HTTP_FORBIDDEN, __('Invitation expired'));
        }

        $respondToBooking(
            $attendee,
            $status,
        );

        $request->session()->put('alert', [
            'message' => __('Thank-you. Your response has been recorded.'),
            'type' => 'info',
        ]);

        return redirect()->route('booking.show', [$booking, 'responded' => 1]);
    }
}
