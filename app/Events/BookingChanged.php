<?php

namespace App\Events;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Queue\SerializesModels;

class BookingChanged
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Booking $booking,
        public User $author,
        public array $changes,
    ) {
    }
}
