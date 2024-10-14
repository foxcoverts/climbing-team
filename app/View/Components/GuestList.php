<?php

namespace App\View\Components;

use App\Models\Booking;
use App\Models\BookingAttendance;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class GuestList extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public User $currentUser,
        public Booking $booking,
        public bool $showTools = true,
    ) {
        $booking->attendees->load('keys', 'qualifications');
    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return true;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.guest-list.index');
    }

    public function attendance(): ?BookingAttendance
    {
        return $this->booking->attendees->find($this->currentUser)?->attendance;
    }

    public function attendees(): Collection
    {
        $attendees = $this->booking->attendees;
        if ($this->currentUser->isGuest()) {
            $attendees = $attendees->where('id', $this->currentUser->id);
        }

        return $attendees
            ->where('id', '!=', $this->booking->lead_instructor_id)
            ->sortBy([
                fn ($a, $b) => $a->attendance->status->compare($b->attendance->status),
                'name',
            ])
            ->groupBy('attendance.status');
    }
}
