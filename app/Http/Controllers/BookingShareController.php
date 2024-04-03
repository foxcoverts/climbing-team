<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BookingShareController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Booking $booking): View
    {
        Gate::check('view', $booking);

        return view('booking.share', [
            'booking' => $booking,
            'link' => $this->shareLink($booking),
            'post' => $this->shareText($booking),
        ]);
    }

    protected function shareText(Booking $booking): string
    {
        $message = match ($booking->status) {
            BookingStatus::Tentative => 'New enquiry for :activity on :when. View details and respond at :link',
            BookingStatus::Confirmed => 'Booking for :activity on :when. View details and respond at :link',
            BookingStatus::Cancelled => 'Booking cancelled on :start.',
        };

        $start_at = localDate($booking->start_at);
        $end_at = localDate($booking->end_at);
        if ($start_at->isSameDay($end_at)) {
            $when = __(':start_date from :start_time to :end_time', [
                'start_date' => $start_at->toFormattedDayDateString(),
                'start_time' => $start_at->format('H:i'),
                'end_time' => $end_at->format('H:i'),
            ]);
        } else {
            $when = __(':start to :end', [
                'start' => $start_at->toDayDateTimeString(),
                'end' => $end_at->toDayDateTimeString(),
            ]);
        }

        return __($message, [
            'activity' => $booking->activity,
            'link' => $this->shareLink($booking),
            'start' => $start_at->toDayDateTimeString(),
            'when' => $when,
        ]);
    }

    protected function shareLink(Booking $booking): string
    {
        return route('booking.show', $booking);
    }
}
