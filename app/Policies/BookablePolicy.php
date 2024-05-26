<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookablePolicy
{
    public function viewAny(User $user, Booking $booking): bool
    {
        return $user->can('view', $booking);
    }

    public function create(User $user, Booking $booking): bool
    {
        return $user->can('update', $booking);
    }

    public function destroy(User $user, Booking $booking, Booking $related): bool
    {
        return $user->can('update', $booking) && $user->can('update', $related);
    }
}
