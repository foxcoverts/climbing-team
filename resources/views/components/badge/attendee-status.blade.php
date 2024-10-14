@use('App\Enums\BookingAttendeeStatus')
@props([
    'status',
    'color' => match ($status) {
        BookingAttendeeStatus::NeedsAction => 'sky',
        BookingAttendeeStatus::Tentative => 'yellow',
        BookingAttendeeStatus::Accepted => 'lime',
        default => 'pink',
    },
    'icon' => match ($status) {
        BookingAttendeeStatus::NeedsAction, BookingAttendeeStatus::Tentative => 'inbox',
        BookingAttendeeStatus::Accepted => 'inbox.check',
        default => 'inbox.cross',
    },
    'label',
])
<x-badge :$color :$icon :label="__('app.attendee.status.' . $status->value)" {{ $attributes }} />
