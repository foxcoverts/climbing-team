@use('App\Enums\AttendeeStatus')
@props([
    'status',
    'color' => match ($status) {
        AttendeeStatus::NeedsAction => 'sky',
        AttendeeStatus::Tentative => 'yellow',
        AttendeeStatus::Accepted => 'lime',
        default => 'pink',
    },
    'icon' => match ($status) {
        AttendeeStatus::NeedsAction, AttendeeStatus::Tentative => 'inbox',
        AttendeeStatus::Accepted => 'inbox.check',
        default => 'inbox.cross',
    },
    'label',
])
<x-badge :$color :$icon :label="__('app.attendee.status.' . $status->value)" {{ $attributes }} />
