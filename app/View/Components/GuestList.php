<?php

namespace App\View\Components;

use App\Models\Attendance;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class GuestList extends Component
{
    // @props(['booking', 'attendees' => collect([]), 'attendance' => null, 'showTools' => true])

    /**
     * Create a new component instance.
     */
    public function __construct(
        protected User $currentUser,
        public Booking $booking,
        public bool $showTools = true,
    ) {
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

    public function attendance(): Attendance
    {
        return $this->booking->attendees()->find($this->currentUser)?->attendance;
    }

    public function attendees(): Collection
    {
        $attendees = $this->booking->attendees()->with('user_accreditations');
        if ($this->booking->lead_instructor_id) {
            $attendees->whereNot('users.id', $this->booking->lead_instructor_id);
        }
        if ($this->currentUser->isGuest()) {
            $attendees->where('users.id', $this->currentUser->id);
        }
        return $attendees
            ->orderBy('booking_user.status')
            ->orderBy('users.name')
            ->get()
            ->groupBy('attendance.status');
    }
}
