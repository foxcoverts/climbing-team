<?php

namespace App\Forms;

use App\Enums\Accreditation;
use App\Enums\AttendeeStatus;
use App\Models\Booking;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingForm
{
    public function __construct(
        public Booking $booking,
    ) {
    }

    public function route(string $name, ...$arguments): string
    {
        return route($name, [$this->booking, ...$arguments]);
    }

    public function getActivitySuggestions(): Collection
    {
        return Booking::distinct()
            ->orderBy('activity')->get(['activity'])->pluck('activity');
    }

    protected function getInstructorsAttending(): Collection
    {
        return $this->booking->attendees()
            ->wherePivot('status', AttendeeStatus::Accepted)
            ->whereExists(function (Builder $query) {
                $query->select(DB::raw(1))
                    ->from('user_accreditations')
                    ->whereColumn('user_accreditations.user_id', 'users.id')
                    ->where('user_accreditations.accreditation', Accreditation::PermitHolder)
                    ->limit(1);
            })
            ->orderBy('users.name')
            ->get();
    }

    public function __get(string $name): mixed
    {
        $getName = Str::camel('get_' . $name);
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
