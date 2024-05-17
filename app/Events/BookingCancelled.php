<?php

namespace App\Events;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Queue\SerializesModels;

class BookingCancelled extends BookingChanged
{
    use SerializesModels;

    public function __construct(
        public Booking $booking,
        public User $author,
        public string $reason,
    ) {
        parent::__construct($booking, $author, [
            'status' => BookingStatus::Cancelled->value,
            'reason' => $reason,
        ]);
    }
}
