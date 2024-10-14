<?php

namespace App\Forms;

use App\Enums\BookingAttendeeStatus;
use App\Models\Booking;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Carbon\Factory as CarbonFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BookingForm
{
    public Collection $instructors_attending;

    public CarbonFactory $dateFactory;

    public function __construct(
        public Booking $booking,
    ) {
        $this->instructors_attending = $this->getInstructorsAttending();
        $this->dateFactory = new CarbonFactory([
            'locale' => config('app.locale', 'en_GB'),
            'timezone' => $booking->timezone ?? CarbonTimeZone::UTC,
        ]);
    }

    public function route(string $name, ...$arguments): string
    {
        return route($name, [$this->booking, ...$arguments]);
    }

    protected function getStartAtAttribute(): Carbon
    {
        return $this->dateFactory->make($this->booking->start_at);
    }

    protected function getStartDateAttribute(): string
    {
        return $this->start_at->toDateString();
    }

    protected function getStartTimeAttribute(): string
    {
        return $this->start_at->format('H:i');
    }

    protected function getEndAtAttribute(): Carbon
    {
        return $this->dateFactory->make($this->booking->end_at);
    }

    protected function getEndTimeAttribute(): string
    {
        return $this->end_at->format('H:i');
    }

    protected function getActivitySuggestionsAttribute(): Collection
    {
        return Booking::distinct()
            ->orderBy('activity')->get(['activity'])->pluck('activity');
    }

    protected function getInstructorsAttending(): Collection
    {
        return $this->booking->attendees()
            ->wherePivot('status', BookingAttendeeStatus::Accepted)
            ->whereHas('qualifications')
            ->orderBy('users.name')
            ->get();
    }

    public function __get(string $name): mixed
    {
        $getName = Str::camel('get_'.$name.'_attribute');
        if (method_exists($this, $getName)) {
            return call_user_func([$this, $getName]);
        }

        return $this->booking->{$name};
    }

    public function __call(string $name, array $arguments): mixed
    {
        return call_user_func_array([$this->booking, $name], $arguments);
    }
}
