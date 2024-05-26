<?php

namespace App\View\Components;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class RelatedBookingsList extends Component
{
    public Collection $bookings;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public User $currentUser,
        public Booking $booking,
    ) {
        $this->bookings = $booking->related()
            ->forUser($currentUser)
            ->notCancelled()
            ->ordered()->get();
    }

    /**
     * Whether the component should be rendered.
     */
    public function shouldRender(): bool
    {
        return ! $this->bookings->isEmpty();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.related-bookings-list.index');
    }
}
